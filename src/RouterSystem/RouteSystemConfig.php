<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.2
 * 
 * LAUS DEO
 * 
 * Classe que trata as rotas
 */
namespace LanceMeCore\RouterSystem;

class RouteSystemConfig
{

    /**
     * @var string Armazena o valor bruto da entrada de REQUEST_URI
     */
    private $uriRaw;

    /**
     * @var string Armazena o valor do verbo HTTP usado na requisiçao
     */
    private $method;

    /**
     *
     * @var array Armazena o resultado da funçao explode aplicada em $this->uriRaw
     */
    private $arrUriDividida;

    public function __construct()
    {
        $this->setUriRaw()
            ->setMethod()
            ->trataUri();
    }

    /**
     * Obtem o valor de REQUEST_URI
     * @return $this
     */
    private function setUriRaw()
    {
        $this->uriRaw = $_SERVER['REQUEST_URI'];
        return $this;
    }

    /**
     * Retorna o valor de uriRaw
     * @return string
     */
    public function getUriRaw()
    {
        return $this->uriRaw;
    }

    /**
     * Obtem o valor de REQUEST_METHOD
     * @return $this
     */
    private function setMethod()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        return $this;
    }

    /**
     * Retorna o metodo da requisiçao HTTP
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    private function trataUri()
    {
        $this->arrUriDividida = explode('/', $this->uriRaw);
    }
    
    public function getArrUriDividida()
    {
        return $this->arrUriDividida;
    }
}
