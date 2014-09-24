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

    private $frameworkBase;
    private $aplicacaoBase;    

    public function inicio()
    {
        require_once "UniversalClassLoader.php";

        $loader = new \Zion\ClassLoader\UniversalClassLoader();
        $loader->registerNamespace("Zion", $this->LIBRARY_PATH);
        $loader->registerNamespace("Teste", 'C:/xampp/htdocs/');
        $loader->registerSufixes($this->SUFIXES);
        $loader->register();
    }
    
    public function getFrameworkBase()
    {
        return $this->frameworkBase;
    }

    public function setFrameworkBase($frameworkBase)
    {
        $this->frameworkBase = $frameworkBase;
        return $this;
    }

    public function getAplicacaoBase()
    {
        return $this->aplicacaoBase;
    }

    public function setAplicacaoBase($aplicacaoBase)
    {
        $this->aplicacaoBase = $aplicacaoBase;
        return $this;
    }

    public function getSufixos()
    {
        return $this->sufixos;
    }

    public function setSufixos(array $sufixos)
    {
        $this->sufixos = $sufixos;
        return $this;
    }

}
