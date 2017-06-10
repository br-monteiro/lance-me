<?php
/**
 * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
 * @version 0.0.1
 * 
 * LAUS DEO
 * 
 * Interface de Controller
 */
namespace LanceMeCore\Interfaces;

interface ControllerInterface
{

    public function __construct(array $parametros);

    public function index();
}
