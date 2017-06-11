<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
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
    public function pdo()
    {
        return $this->sgbdConnection->getConnection();
    }
}
