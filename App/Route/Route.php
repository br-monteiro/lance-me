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
        $this->routeMap->rotaGet(['/urls/{id}', 'EnderecosController@redirecionar', [
                'id' => '/\d+/'
        ]]);

        $this->routeMap->rotaDelete(['/urls/{id}', 'EnderecosController@deletaUrlPorId', [
                'id' => '/\d+/'
        ]]);

        $this->routeMap->rotaPost(['/users/{userId}/urls', 'EnderecosController@novo', [
                'userId' => '/\w+/'
        ]]);

        $this->routeMap->rotaGet(['/users/{userId}/stats', 'EnderecosController@statsPorUsuario', [
                'userId' => '/\w+/'
        ]]);

        $this->routeMap->rotaPost(['/users', 'UsuariosController@novo', []]);

        $this->routeMap->rotaGet(['/stats', 'EnderecosController@index', []]);

        $this->routeMap->rotaGet(['/stats/{id}', 'EnderecosController@statsPorId', [
                'id' => '/\d+/'
        ]]);

        $this->routeMap->rotaDelete(['/user/{userId}', 'UsuariosController@deleteUsuarioPorId', [
                'userId' => '/\w+/'
        ]]);
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
