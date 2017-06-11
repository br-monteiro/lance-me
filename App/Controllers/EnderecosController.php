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
use App\Models\EnderecosModel;

class EnderecosController extends AbstractController implements ControllerInterface
{

    private $enderecosModel;

    public function __construct(array $parametros)
    {
        parent::__construct($parametros);

        $this->enderecosModel = new EnderecosModel();
    }

    public function index()
    {
        $this->enderecosModel->retornaStats();
    }

    public function redirecionar()
    {
        $idUrl = $this->getParams('id');
        $this->enderecosModel->verificaUrlPorId($idUrl);
    }
    
    public function novo(){
        $userId = $this->getParams('userid');
        $this->enderecosModel->adicionaEndereco($userId);
    }
}
