<?php

namespace Zion\Form;

class FormTag
{

    private $id;
    private $nome;
    private $action;
    private $autoComplete;
    private $enctype;
    private $method;
    private $novalidate;
    private $target;
    private $complemento;
    private $classCss;
    private $hearder;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function getAutoComplete()
    {
        return $this->autoComplete;
    }

    public function setAutoComplete($autoComplete)
    {
        $this->autoComplete = $autoComplete;
        return $this;
    }

    public function getEnctype()
    {
        return $this->enctype;
    }

    public function setEnctype($enctype)
    {
        $this->enctype = $enctype;
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function getNovalidate()
    {
        return $this->novalidate;
    }

    public function setNovalidate($novalidate)
    {
        $this->novalidate = $novalidate;
        return $this;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    public function getComplemento()
    {
        return $this->complemento;
    }

    public function setClassCss($classCss)
    {
        $this->classCss = $classCss;
        return $this;
    }

    public function getClassCss()
    {
        return $this->classCss;
    }
    
    public function setHearder($hearder)
    {
        $this->hearder = $hearder;
        return $this;
    }

    public function getHearder()
    {
        return $this->hearder;
    }

}
