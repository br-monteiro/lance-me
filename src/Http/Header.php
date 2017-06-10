<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Modifica o cabeçalho da requisiçao HTTP
 */
namespace LanceMeCore\Http;

class Header
{

    public function setHttpHeader($codigo, $mesagem = null)
    {
        http_response_code($codigo);
        echo $mesagem ? : null;
    }

    /**
     * Seta o cabeçalho da requisiçao HTTP para o erro 404
     */
    public function erro404($mensagem = null)
    {
        $this->setHttpHeader(404);
        echo '404 ' . $mensagem ? : 'Not Found.';
        exit;
    }
}
