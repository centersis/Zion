<?php

/** Loader 
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 08/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Carregamento automático de classes, baseado no Autoload Component do Symfony
 * de acordo com as diretrizes PSR-0 e PSR-4.
 * 
 */
 namespace Zion\ClassLoader;
 
use \Zion\ClassLoader\UniversalClassLoader;

class Loader
{
    public $LIBRARY_PATH        = 'C:/xampp/htdocs/Zion/Lib';

    public $SUFIXES             = array("", ".vo", ".class", ".interface");

    public function __construct()
    {

        require_once "UniversalClassLoader.php";

        $loader = new \Zion\ClassLoader\UniversalClassLoader();
        $loader->registerNamespace("Zion", $this->LIBRARY_PATH);
        $loader->registerSufixes($this->SUFIXES);
        $loader->register();
    }

}
?>