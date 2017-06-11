<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Biblioteca de Conexao com o MySQL
 */
namespace LanceMeCore\Database\SGBD;

use LanceMeCore\Database\SGBD\SGBDAbstract;

class Mysql extends SGBDAbstract
{

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function run()
    {
        $this->rules();
        $this->connect();
    }

    /**
     * Regras de Conexao com o banco de Dados
     * @return boolean
     * @throws \Exception
     */
    protected function rules()
    {
        if (is_array($this->config)) {

            if (empty($this->config['server'])) {
                throw new \Exception('Você não informou o servidor!');
            }

            if (empty($this->config['dbname'])) {
                throw new \Exception('Você não informou o banco de dados!');
            }

            if (empty($this->config['username'])) {
                throw new \Exception('Você não informou o usuário!');
            }

            if (!isset($this->config['password'])) {
                throw new \Exception('Você não informou a senha!');
            }

            if (!isset($this->config['options']) or ! is_array($this->config['options'])) {
                throw new \Exception('Você não informou as opções ou não é '
                . 'um array, você precisa informar isso mesmo que vazio!');
            }

            // interrompe a execuçao do script
            return true;
        }

        throw new \Exception('Configuração de conexão com o Banco de Dados Inválida.');
    }

    /**
     * Conecta com o Banco de Dados
     * @return boolean
     * @throws \Exception
     */
    protected function connect()
    {
        if ($this->connection) {
            return true;
        }

        try {

            $this->connection = new \PDO(
                'mysql:host=' . $this->config['server']
                . ';dbname=' . $this->config['dbname'], $this->config['username'], $this->config['password'], $this->config['options']
            );
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {

            throw new \Exception('Erro ao conectar com o Banco de Dados. Código: ' . $e->getCode() . '! Mensagem: ' . $e->getMessage());
        }
    }

    /**
     * Retorna a referencia com o Objeto PDO
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
