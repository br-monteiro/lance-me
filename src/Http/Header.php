<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.3
 * 
 * LAUS DEO
 * 
 * Modifica o cabeçalho da requisiçao HTTP
 */
namespace LanceMeCore\Http;

class Header
{

    public function setHttpHeader($codigo)
    {
        http_response_code($codigo);
    }

    /**
     * Seta o cabeçalho da requisiçao HTTP para o erro 404
     */
    public function erro404()
    {
        $this->setHttpHeader(404);
        echo '404 Not Found.';
        exit;
    }
}
