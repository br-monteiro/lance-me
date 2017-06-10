<?php
/**
 * Projeto de um simples encurtador de URL
 * @author Edson B S MOnteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 */

// modificanco o separador de diretorio
define('DS', DIRECTORY_SEPARATOR);
// incluindo o autoload do composer
require_once '..' . DS . 'vendor' . DS . 'autoload.php';

try {
    
    $app = new \LanceMeCore\Bootstrap\InitApp();
    $app->run();
    
} catch (Exception $ex) {
    echo $ex->getMessage();
}
