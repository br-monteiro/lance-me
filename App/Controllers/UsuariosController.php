<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 */
namespace App\Controllers;

use LanceMeCore\Controller\AbstractController;
use LanceMeCore\Interfaces\ControllerInterface;
use App\Models\UsuariosModel;

class UsuariosController extends AbstractController implements ControllerInterface
{

    private $usuariosModel;

    public function __construct(array $parametros)
    {
        parent::__construct($parametros);

        $this->usuariosModel = new UsuariosModel();
    }

    public function index()
    {
        dump($this->getParams('id'));
    }

    public function criar()
    {
        $this->usuariosModel->adicionaUsuario();
    }
}
