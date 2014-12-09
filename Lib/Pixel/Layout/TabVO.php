<?php

namespace Pixel\Layout;

class TabVO
{

    private $id;
    private $titulo;
    private $ativa;
    private $conteudo;
    private $onClick;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
        return $this;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setAtiva($ativa)
    {
        $this->ativa = $ativa;
        return $this;
    }

    public function getAtiva()
    {
        return $this->ativa;
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

    public function setOnClick($onClick)
    {
        $this->onClick = $onClick;
        return $this;
    }

    public function getOnClick()
    {
        return $this->onClick;
    }

}
