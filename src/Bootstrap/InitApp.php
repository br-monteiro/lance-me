<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Classe responsavel pela inicializaçao do sistema
 */
namespace LanceMeCore\Bootstrap;

use LanceMeCore\RouterSystem\Router;
use LanceMeCore\Interfaces\ControllerInterface;

class InitApp
{

    /**
     * Reverecnia para o roteador
     * @var Router
     */
    private $router;

    /**
     * Nome do Controller
     * @var string
     */
    private $controller;

    /**
     * Nome da Action
     * @var string
     */
    private $action;

    /**
     * Parametros passados pela URI
     * @var array
     */
    private $parametros = [];

    /**
     * Separaçao do nome Controller e da Action
     * @var array
     */
    private $arrExec = [];

    public function __construct()
    {
        $this->router = new Router();

        $this->explodeExec()
            ->validaController()
            ->validaAction()
            ->setParametros();
    }

    private function setParametros()
    {
        $this->parametros = $this->router->getArrParametros();
        return $this;
    }

    /**
     * Explode a string com o nome do Controller e da Action
     * @return $this
     */
    private function explodeExec()
    {
        $this->arrExec = explode('@', $this->router->getExec());
        return $this;
    }

    /**
     * Valida o Controller Passado registrado para a Rota
     * @return $this
     * @throws Exception
     */
    private function validaController()
    {
        if (!isset($this->arrExec[0])) {
            throw new Exception("Controller indefinido no registro da Rota.");
        }

        if (!class_exists('\App\Controllers\\' . $this->arrExec[0])) {
            throw new \Exception("Classe definida como Controller nao econtrada.");
        }

        $this->controller = $this->arrExec[0];

        return $this;
    }

    private function validaAction()
    {
        // verifica se existe uma action especifica para ser executada
        // caso nao exista, seta como defaut a action 'index'
        if (!isset($this->arrExec[1])) {
            $this->action = 'index';
            return $this;
        }

        $this->action = $this->arrExec[1];
        return $this;
    }

    /**
     * Verifica se o Controller eh valido
     * @param ControllerInterface $controllerObj
     * @return boolean
     * @throws Exception
     */
    private function controllerEhValido($controllerObj)
    {

        if (!$controllerObj instanceof ControllerInterface) {
            throw new \Exception('Controller Invalido. '
            . 'Todo Controller deve implementar a interface ControllerInterface');
        }

        return $this;
    }

    /**
     * Verifica se o Metodo existe no Controller informado
     * @param ControllerInterface $controller
     * @return $this
     * @throws Exception
     */
    private function actionEhValida(ControllerInterface $controller)
    {
        if (!method_exists($controller, $this->action)) {
            throw new \Exception('Action Invalida. '
            . 'Verifique se o nome da Action registrada para Rota existe na class '
            . '\App\Controllers\\' . $this->controller);
        }

        return $this;
    }

    /**
     * Inicia o sistema
     */
    public function run()
    {
        $controller = '\App\Controllers\\' . $this->controller;
        $controller = new $controller($this->parametros);

        $this->controllerEhValido($controller)
            ->actionEhValida($controller);

        $action = $this->action;

        // executa o metodo definido como action
        $controller->$action();
    }
}
