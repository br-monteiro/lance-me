<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Roteador do sistema
 */
namespace LanceMeCore\RouterSystem;

use LanceMeCore\RouterSystem\RouteSystemConfig;
use LanceMeCore\RouterSystem\RouteMap;
use App\Route\Route;
use LanceMeCore\Http\Header;

class Router
{

    private $routeConfig;
    private $route;
    private $exec;
    private $arrParametros = [];

    public function __construct()
    {
        $this->routeConfig = new RouteSystemConfig();
        $routerMap = new RouteMap();
        $this->route = new Route($routerMap);
        $this->route->registra();

        $this->procuraRota();
    }

    /**
     * Verifica se rota foi registrada
     */
    private function procuraRota()
    {
        $metodo = $this->routeConfig->getMethod();
        $arrUriDividida = $this->routeConfig->getArrUriDividida();
        $arrRouteMap = $this->route->getRouteMap()->getArrRouteMap();
        $tamanhoArrayRotaRequisitada = count($arrUriDividida);

        // verifica se existe alguma rota registrada para o metodo HTTP da requisiçao
        if (!array_key_exists($metodo, $arrRouteMap)) {
            $httpHeader = new Header();
            $httpHeader->erro404();
        }

        // procura um indice do tamanho da rota enviada
        if (!array_key_exists($tamanhoArrayRotaRequisitada, $arrRouteMap[$metodo])) {
            $httpHeader = new Header();
            $httpHeader->erro404();
        }

        foreach ($arrRouteMap[$metodo][$tamanhoArrayRotaRequisitada] as $rm) {

            // verifica se ha rotas registradas - sem parametro
            if (empty(array_diff_assoc($rm['arrRota'], $arrUriDividida))) {
                // encontrou uma rota simples (sem exigencia de parametros)
                // seta para o atributo o valor o nome do Controller e da Action
                $this->exec = $rm['exec'];
                break;
            }

            // verifica se a rota registrada exige algum parametro
            if ($this->necessidadeDeParametros($rm['rota'])) {
                if (!$this->validaParametros($rm, $arrUriDividida)) {
                    continue;
                }

                $this->exec = $rm['exec'];
            }
        }

        // nao econtrou a rota!
        if (!$this->exec) {
            $httpHeader = new Header();
            $httpHeader->erro404();
        }

    }

    /**
     * Verifica se a rota necessita de parametros
     * @param string $rota
     * @return boolean
     */
    private function necessidadeDeParametros($rota)
    {
        $existeParamentros = strpos($rota, '{');

        if ($existeParamentros !== false) {
            return true;
        }

        return false;
    }

    /**
     * Valida os parametros enviados pela URI
     * @param array $arrRouteMap
     * @param array $arrUriDividida
     * @return boolean
     */
    private function validaParametros(array $arrRouteMap, array $arrUriDividida)
    {
        $arrRM = $arrRouteMap['arrRota'];

        // percorre as rotas registradas
        for ($i = 0; $i < count($arrRM); $i++) {

            // verifica se o indice atual eh um parametro
            if ($this->necessidadeDeParametros($arrRM[$i])) {

                $matches = null;
                $nomeDoParametro = $this->extraiNomeDoParametro($arrRM[$i]);

                // verifica se o valor passado atende as regras de parametros da rota
                preg_match($arrRouteMap['regras'][$nomeDoParametro], $arrUriDividida[$i], $matches);


                // caso o parametro passado na URI atenda as regras da RegEx,
                // entao registra o valor no array de parametros
                if (!empty($matches)) {
                    $this->arrParametros[$nomeDoParametro] = $arrUriDividida[$i];
                    continue;
                }

                return false;
            }

            if ($arrRM[$i] != $arrUriDividida[$i]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retira os caracteres '{','}' da string setada como parametro
     * @param string $valor
     * @return string Nome do Atributo
     */
    private function extraiNomeDoParametro($valor)
    {
        $valor = str_replace('{', '', $valor);
        $valor = str_replace('}', '', $valor);
        return $valor;
    }

    /**
     * Retorna a string contendo o nome do Controller e Action a serem executados
     * @return string Controller@action
     */
    public function getExec()
    {
        return $this->exec;
    }
    
    /**
     * Retorna o array de parametros passados na URI
     * @return array
     */
    public function getArrParametros()
    {
        return $this->arrParametros;
    }
}
