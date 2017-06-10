<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Class onde sao definidas as rotas do sistema
 */
namespace App\Route;

use LanceMeCore\RouterSystem\RouteMap;

class Route
{

    /**
     * @var RouteMap
     */
    private $routeMap;

    public function __construct(RouteMap $routeMap)
    {
        $this->routeMap = $routeMap;
    }

    /**
     * Registra as rotas no sistma
     */
    public function registra()
    {
        $this->routeMap->rotaGet(['/teste', 'TesteController@index', []]);
    }

    /**
     * Retorna a referencia para o objeto RouteMap
     * @return RouteMap
     */
    public function getRouteMap()
    {
        return $this->routeMap;
    }
}
