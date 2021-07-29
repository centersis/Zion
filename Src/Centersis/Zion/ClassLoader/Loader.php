<?php

namespace Centersis\Zion\ClassLoader;

class Loader
{
    private $nameSpaces;

    public function __construct()
    {
        $this->nameSpaces = [];
    }

    /**
     * Registra os namespaces setados em $nameSpaces
     * @return object
     */
    public function inicio()
    {
        require_once "UniversalClassLoader.php";

        $loader = new \Zion\ClassLoader\UniversalClassLoader();

        foreach ($this->nameSpaces as $name => $caminho) {

            $loader->registerNamespace($name, $caminho);
        }

        $loader->register();
    }

    /**
     * Seta nome e caminho para um namespace
     * @param string $name
     * @param string $caminho
     * @return \Zion\ClassLoader\Loader
     */
    public function setNameSpaces($name, $caminho)
    {
        $this->nameSpaces[$name] = $caminho;
        return $this;
    }

}
