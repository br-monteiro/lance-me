<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Model de Endereços
 */
namespace App\Models;

use LanceMeCore\Database\ModelAbstract;
use LanceMeCore\Http\Header;
use App\Models\UsuariosModel;

class EnderecosModel extends ModelAbstract
{

    const URL_PADRAO = 'http://lance.me/';

    private $header;
    private $entidade = 'enderecos';
    private $url;

    public function __construct()
    {
        parent::__construct();

        $this->header = new Header();
    }

    public function verificaUrlPorId($idUrl)
    {
        $stmt = $this->pdo()->prepare("SELECT url FROM {$this->entidade} WHERE id = ?");
        $stmt->execute([$idUrl]);
        $url = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($url) {
            $this->header->setHttpHeader(301);
            header("Location: " . $url['url']);
            return true;
        }

        $this->header->setHttpHeader(404);
    }

    /**
     * Registra uma nova URL
     * @param string $userId
     * @return boolean
     */
    public function adicionaEndereco($userId)
    {
        // valida os dados enviados
        $this->validaDadosDeEntrtada();
        $this->contentType();

        $usuarioModel = new UsuariosModel();
        // verifica se o usuario e valido
        $usuario = $usuarioModel->consultaUsuarioPorNome($userId);

        if (!$usuario) {
            $this->header->setHttpHeader(205);
            return false;
        }

        $stmt = $this->pdo()->prepare("INSERT INTO {$this->entidade} (url, shortUrl, usuarios_id) VALUES (?, ?, ?);");

        $shortUrl = self::URL_PADRAO . $this->geraCodigo();

        if ($stmt->execute([$this->url, $shortUrl, $usuario['id']])) {

            $this->header->setHttpHeader(201);

            $this->toJson([
                "id" => $this->pdo()->lastInsertId(),
                "hits" => 0,
                "url" => $this->url,
                "shortUrl" => $shortUrl
            ]);

            return true;
        }
    }

    /**
     * Retorna statisticas de acesso
     */
    public function retornaStats()
    {
        $stats = $this->retornaStatsDeAcesso();
        $topDez = $this->retornaTopDez();

        $this->contentType()->toJson([
            "hists" => $stats['qtdHits'],
            "urlCount" => $stats['qtdRegistros'],
            "topUrls" => $topDez
        ]);
    }

    /**
     * Retorna statisticas de acesso
     */
    public function retornaStatsPorUsuario($userId)
    {
        $usuarioModel = new UsuariosModel();
        // verifica se o usuario e valido
        $usuario = $usuarioModel->consultaUsuarioPorNome($userId);

        if (!$usuario) {
            $this->contentType();
            $this->header->setHttpHeader(404);
            return false;
        }

        $stats = $this->retornaStatsDeAcesso($usuario['id']);
        $topDez = $this->retornaTopDez($usuario['id']);

        $this->contentType()->toJson([
            "hists" => $stats['qtdHits'],
            "urlCount" => $stats['qtdRegistros'],
            "topUrls" => $topDez
        ]);
    }

    /**
     * Retorna o stats de url pra o id informado
     * @param int $idUrl
     * @return boolean
     */
    public function retornaStatsPorId($idUrl)
    {
        $stats = $this->retornaRegistroPorId($idUrl);
        $this->contentType();

        if (!$stats) {
            $this->header->setHttpHeader(404);
            return false;
        }

        $this->toJson([
            "id" => $stats['id'],
            "hits" => (int) $stats['hits'],
            "url" => $stats['url'],
            "shortUrl" => $stats['shortUrl']
        ]);
    }

    /**
     * Deleta um registro do Banco de Dados de acordo com o ID informado
     * @param int $idUrl
     * @return boolean
     */
    public function deletaUrlPorId($idUrl)
    {
        $stats = $this->retornaRegistroPorId($idUrl);
        $this->contentType();

        if (!$stats) {
            $this->header->setHttpHeader(404);
            return false;
        }

        $stmt = $this->pdo()->prepare("DELETE FROM {$this->entidade} WHERE id = ?");

        if (!$stmt->execute([$idUrl])) {
            $this->header->setHttpHeader(500);
        }
    }

    /**
     * Retorna o registro de acordo com o id informado
     * @param int $idUrl
     * @return boolean | array
     */
    private function retornaRegistroPorId($idUrl)
    {
        $stmt = $this->pdo()->prepare("SELECT * FROM {$this->entidade} WHERE id = ? ;");
        $stmt->execute([$idUrl]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Retorna os dez registros mais acessados
     * @param int $userId
     * @return array Resultado com os Top 10 mais acessados
     */
    private function retornaTopDez($userId = null)
    {
        $whereUsuario = $userId ? ' WHERE usuarios_id = ' . $userId : null;

        $stmt = $this->pdo()->prepare("SELECT id, hits, url, shortUrl FROM {$this->entidade} {$whereUsuario} ORDER BY hits DESC LIMIT 10");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Retorna as estatisticas de acesso ao sistema
     * @param int $userId
     * @return array
     */
    private function retornaStatsDeAcesso($userId = null)
    {
        $whereUsuario = $userId ? ' WHERE usuarios_id = ' . $userId : null;

        $stmt = $this->pdo()->prepare("SELECT COUNT(*) as qtdRegistros, SUM(hits) AS qtdHits FROM {$this->entidade} {$whereUsuario}");
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Valida os Dados enviados
     * @return $this
     */
    private function validaDadosDeEntrtada()
    {
        $arrInput = $this->getInput();

        $this->url = filter_var($arrInput['url'], FILTER_SANITIZE_URL, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

        if (!$this->url) {

            $this->header->setHttpHeader(205);
        }

        return $this;
    }

    /**
     * Gera o codigo deidentificaçao da shotUrl
     * @param int $length tamanho da string a ser gerada
     * @return string Codigo gerado
     */
    public function geraCodigo($length = 6)
    {
        $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $pass = '';
        mt_srand(10000000 * (double) microtime());
        for ($i = 0; $i < $length; $i++) {
            $pass .= $salt[mt_rand(0, strlen($salt) - 1)];
        }
        return $pass;
    }
}
