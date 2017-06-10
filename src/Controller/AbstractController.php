<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Classe Mae dos Controllers
 */
namespace LanceMeCore\Controller;

class AbstractController
{
    protected $parametros = [];
    
    public function __construct(array $parametros)
    {
        $this->parametros = $parametros;
    }

    /**
     * Retorna os parametros passados na URI
     * @param string $indice Nome do parametro a ser retornado
     * @return mixed
     */
    protected function getParams($indice = null) {

        if (array_key_exists($indice, $this->parametros)) {
            return $this->parametros[$indice];
        }
        
        return null;
    }
}
