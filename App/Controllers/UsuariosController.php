<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Controller Teste
 */
namespace App\Controllers;

use LanceMeCore\Controller\AbstractController;
use LanceMeCore\Interfaces\ControllerInterface;

class TesteController extends AbstractController implements ControllerInterface
{
    public function __construct(array $parametros)
    {
        parent::__construct($parametros);
    }

    public function index()
    {
        dump($this->getParams('id'));
    }
}
