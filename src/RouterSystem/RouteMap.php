<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.3
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
     * @param array $definicaoDaRota
     * @return $this
     * @throws Exception
     */
    private function verificaEstruturaDaRota(array $definicaoDaRota)
    {
        if (count($definicaoDaRota) != 3) {
            throw new \Exception("A definiçao da rota nao e valida");
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
        $this->verificaEstruturaDaRota($definicaoDaRota);

        // explode a rota para facilitar o armazenamento em tabela de espalhamento
        // o que facilita na hora de recuperar esta definiçao de rota para consulta
        $arrRotaExplodida = explode('/', $definicaoDaRota[0]);

        // registra a rota
        // por padrao as rotas definidas sao registradas seguindo a classificaçao:
        // MetodoHTTP -> tamanhoDoArrayDeRotas -> indiceDinamico
        $this->arrRouteMap['GET'][count($arrRotaExplodida)][] = $this->propriedadesDoArrayDeRotas($definicaoDaRota, $arrRotaExplodida);

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
        $this->verificaEstruturaDaRota($definicaoDaRota);

        // explode a rota para facilitar o armazenamento em tabela de espalhamento
        // o que facilita na hora de recuperar esta definiçao de rota para consulta
        $arrRotaExplodida = explode('/', $definicaoDaRota[0]);

        // registra a rota
        // por padrao as rotas definidas sao registradas seguindo a classificaçao:
        // MetodoHTTP -> tamanhoDoArrayDeRotas -> indiceDinamico
        $this->arrRouteMap['POST'][count($arrRotaExplodida)][] = $this->propriedadesDoArrayDeRotas($definicaoDaRota, $arrRotaExplodida);

        return $this;
    }

    /**
     * Registra uma rota para o metodo DELETE
     * @param array $definicaoDaRota Array de definiçao da Rota
     * @return $this
     */
    public function rotaDelete(array $definicaoDaRota)
    {
        // valida a rota
        $this->verificaEstruturaDaRota($definicaoDaRota);

        // explode a rota para facilitar o armazenamento em tabela de espalhamento
        // o que facilita na hora de recuperar esta definiçao de rota para consulta
        $arrRotaExplodida = explode('/', $definicaoDaRota[0]);

        // registra a rota
        // por padrao as rotas definidas sao registradas seguindo a classificaçao:
        // MetodoHTTP -> tamanhoDoArrayDeRotas -> indiceDinamico
        $this->arrRouteMap['DELETE'][count($arrRotaExplodida)][] = $this->propriedadesDoArrayDeRotas($definicaoDaRota, $arrRotaExplodida);

        return $this;
    }

    /**
     * Configura das propriedades da rota a ser registradas
     * @param array $definicaoDaRota
     * @param array $arrRotaExplodida
     * @return array
     */
    private function propriedadesDoArrayDeRotas(array $definicaoDaRota, array $arrRotaExplodida)
    {
        return [
            'rota' => $definicaoDaRota[0],
            'exec' => $definicaoDaRota[1],
            'regras' => $definicaoDaRota[2],
            'arrRota' => $arrRotaExplodida
        ];
    }

    /**
     * Retorna o array de rotas registradas
     * @return array arrRouteMap
     */
    public function getArrRouteMap()
    {
        return $this->arrRouteMap;
    }
}
