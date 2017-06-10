<?php
/**
 * @author Edson B S MOnteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Class que armazena o esquema de rotas definidas pelo desenvolvedor
 */
namespace LanceMeCore\RouterSystem;

class RouteMap
{

    /**
     * @var array Map de rotas
     */
    private $arrRouteMap = [];

    /**
     * Verifica se a definiçao da rota e valida
     * @param \LanceMeCore\RouterSystem\arra $definicaoDaRota
     * @return $this
     * @throws Exception
     */
    private function verificaRota(arra $definicaoDaRota)
    {
        if (count($definicaoDaRota) != 3) {
            throw new Exception("A definiçao da rota nao e valida");
        }

        return $this;
    }

    /**
     * Registra uma rota para o metodo GET
     * @param array $definicaoDaRota Array de definiçao da Rota
     * @return $this
     */
    public function rotaGet(array $definicaoDaRota)
    {
        // valida a rota
        $this->verificaRota($definicaoDaRota);
        // registra a rota
        $this->arrRouteMap['GET'] = [
            'rota' => $definicaoDaRota[0],
            'controller' => $definicaoDaRota[1],
            'action' => $definicaoDaRota[2]
        ];

        return $this;
    }

    /**
     * Registra uma rota para o metodo POST
     * @param array $definicaoDaRota Array de definiçao da Rota
     * @return $this
     */
    public function rotaPost(array $definicaoDaRota)
    {
        // valida a rota
        $this->verificaRota($definicaoDaRota);
        // registra a rota
        $this->arrRouteMap['POST'] = [
            'rota' => $definicaoDaRota[0],
            'controller' => $definicaoDaRota[1],
            'action' => $definicaoDaRota[2]
        ];

        return $this;
    }
}
