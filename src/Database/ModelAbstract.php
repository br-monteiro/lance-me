<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.3
 * 
 * LAUS DEO
 * 
 * Interface de conexao com o banco
 */
namespace LanceMeCore\Database;

use LanceMeCore\Database\SGBDConnection;

class ModelAbstract
{

    private $sgbdConnection;

    public function __construct()
    {
        $this->sgbdConnection = new SGBDConnection();
    }

    /**
     * Referencia para o Objeto PDO
     * @return \PDO
     */
    protected function pdo()
    {
        return $this->sgbdConnection->getConnection();
    }

    protected function toJson($json)
    {
        echo json_encode($json);
    }

    protected function getInput()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    protected function contentType()
    {
        header('Content-type:application/json;charset=utf-8');
        return $this;
    }
}
