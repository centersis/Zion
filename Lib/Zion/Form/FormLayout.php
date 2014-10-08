<?php

namespace Zion\Form;

class FormLayout
{

    private $tipoBase;
    private $conteudo;

    public function __construct($nome, $conteudo)
    {
        $this->tipoBase = 'layout';
        $this->setNome($nome);
        $this->setConteudo($conteudo);
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
        return $this;
    }

    public function getConteudo()
    {
        return $this->conteudo;
    }

}
