<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.2
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
        // todo
    }

    public function novo()
    {
        $this->usuariosModel->adicionaUsuario();
    }

    public function deleteUsuarioPorId()
    {
        $userId = $this->getParams('userId');
        $this->usuariosModel->deleteUsuarioPorId($userId);
    }
}
