<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Model de Usuarios
 */
namespace App\Models;

use LanceMeCore\Database\ModelAbstract;
use LanceMeCore\Http\Header;

class UsuariosModel extends ModelAbstract
{

    private $header;
    private $entidade = 'usuarios';
    private $nome;

    public function __construct()
    {
        parent::__construct();

        $this->header = new Header();
    }

    /**
     * Cria um novo usuario
     */
    public function adicionaUsuario()
    {
        $this->validaDadosDeEntrtada();
        $this->evitaDuplicidadeDeUsuarios();

        $stmt = $this->pdo()->prepare("INSERT INTO {$this->entidade} (nome) VALUES(?);");

        if ($stmt->execute([$this->nome])) {
            $this->header->setHttpHeader(201);
            $this->contentType()->toJson(['id' => $this->nome]);
        }
    }

    /**
     * Valida os Dados enviados
     * @return $this
     */
    private function validaDadosDeEntrtada()
    {
        $arrInput = $this->getInput();

        $this->nome = filter_var($arrInput['nome'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

        if (!$this->nome) {
            $this->header->setHttpHeader(205);
        }

        return $this;
    }

    /**
     * Consulta usuario por Nome
     * @param string $nomeDoUsuario
     * @return boolean | array
     */
    final public function consultaUsuarioPorNome($nomeDoUsuario)
    {
        $stmt = $this->pdo()->prepare("SELECT * FROM {$this->entidade} WHERE nome = ?");
        $stmt->execute([$nomeDoUsuario]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    private function evitaDuplicidadeDeUsuarios()
    {
        $stmt = $this->pdo()->prepare("SELECT id FROM {$this->entidade} WHERE nome = ?");
        $stmt->execute([$this->nome]);
        if ($stmt->fetch(\PDO::FETCH_ASSOC)) {
            $this->header->setHttpHeader(409);
            exit();
        }

        return $this;
    }
}
