<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Model de Endereços
 */
namespace App\Models;

use LanceMeCore\Database\ModelAbstract;
use LanceMeCore\Http\Header;

class EnderecosModel extends ModelAbstract
{

    private $header;
    private $entidade = 'enderecos';

    public function __construct()
    {
        parent::__construct();

        $this->header = new Header();
    }

    public function verificaUrlPorId($idUrl)
    {
        $stmt = $this->pdo()->prepare("SELECT url FROM {$this->entidade} WHERE id = ?");
        $stmt->execute([$idUrl]);
        $url = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($url) {
            $this->header->setHttpHeader(301);
            header("Location: " . $url['url']);
            return true;
        }

        $this->header->setHttpHeader(404);
    }
}
