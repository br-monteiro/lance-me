<?php
/**
 * Projeto de um simples encutador de URL
 * @author Edson B S MOnteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 */

// modificanco o separador de diretorio
define('DS', DIRECTORY_SEPARATOR);
// incluindo o autoload do composer
require_once '..' . DS . 'vendor' . DS . 'autoload.php';

$t = new LanceMeCore\RouterSystem\RouteSystemConfig();
$x = new LanceMeCore\Bootstrap\InitApp();
dump($t, $x);