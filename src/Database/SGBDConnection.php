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

use App\Config\ConfigDatabase;
use LanceMeCore\Database\SGBD\SGBDAbstract;

class SGBDConnection
{

    private $config;
    private $instance;

    public function __construct()
    {
        $configDatabase = new ConfigDatabase();

        $this->config = $configDatabase->db;
        $this->rules();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function rules()
    {
        if (!isset($this->config['sgbd'])) {
            throw new \Exception("Não foi possível detectar o SGBD. "
            . "Verifique o arquivo de configuração de conexão.");
        }

        $className = "\LanceMeCore\Database\SGBD\\" . ucfirst(strtolower($this->config['sgbd']));

        if (!class_exists($className)) {
            throw new \Exception("Não foi possível Encontrar a Class {$className}. "
            . "Verifique o arquivo de configuração de conexão.");
        }

        $this->instance = new $className($this->config);

        if (!($this->instance instanceof SGBDAbstract)) {
            throw new \Exception("A Class {$this->config['sgbd']} não é filha de SgbdAbstract. "
            . "Verifique o arquivo de configuração de conexão.");
        }

        return $this;
    }

    /**
     * Executa a conexao com o Banco de Dados
     */
    private function run()
    {
        $this->instance->run();
    }

    /**
     * Retorna a referencia ao objeto PDO
     * @return \PDO
     */
    final public function getConnection()
    {
        $this->run();

        return $this->instance->getConnection();
    }
}
