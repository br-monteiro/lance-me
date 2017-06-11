<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Base da interface com as bibliotecas de conexao
 */
namespace LanceMeCore\Database\SGBD;

abstract class SGBDAbstract
{

    protected $config;
    protected $connection;

    abstract public function run();

    abstract protected function rules();

    abstract protected function connect();

    abstract public function getConnection();
}
