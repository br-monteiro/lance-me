<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Configuraçao das credenciais de conexao com o Banco de Dados
 */
namespace App\Config;

class ConfigDatabase
{

    public $db = [
        'sgbd' => 'mysql',
        'server' => 'localhost',
        'dbname' => 'lanceme',
        'username' => 'webapp',
        'password' => 'webapp',
        'options' => [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"],
    ];

}
