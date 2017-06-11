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

        $usuarioModel = new UsuariosModel();
        // verifica se o usuario e valido
        $usuario = $usuarioModel->consultaUsuarioPorNome($userId);

        if (!$usuario) {
            $this->contentType();
            $this->header->setHttpHeader(205);
            return false;
        }

        $stmt = $this->pdo()->prepare("INSERT INTO {$this->entidade} (url, shortUrl, usuarios_id) VALUES (?, ?, ?);");

        $shortUrl = self::URL_PADRAO . $this->geraCodigo();

        if ($stmt->execute([$this->url, $shortUrl, $usuario['id']])) {

            $this->header->setHttpHeader(201);

            $this->contentType()->toJson([
                "id" => $this->pdo()->lastInsertId(),
                "hits" => 0,
                "url" => $this->url,
                "shortUrl" => $shortUrl
            ]);

            return true;
        }
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
