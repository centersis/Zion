<?php

abstract class FormBasicoVo
{
    private $id;
    private $nome;
    private $identifica;
    private $valor;
    private $valorPadrao;
    private $disabled;
    private $complemento;
    private $atributos;
    private $classCss;    
        
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
    
    public function setIdentifica($identifica)
    {
        $this->identifica = $identifica;        
        return $this;
    }
    
    public function getIdentifica()
    {
        return $this->identifica;
    }
    
    public function setValor($valor)
    {
        $this->valor = $valor;        
        return $this;
    }
    
    public function getValor()
    {
        return $this->valor;
    }
    
    public function setValorPadrao($valorPadrao)
    {
        $this->valorPadrao = $valorPadrao;        
        return $this;
    }
    
    public function getValorPadrao()
    {
        return $this->valorPadrao;
    }
    
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;        
        return $this;
    }
    
    public function getDisabled()
    {
        return $this->disabled;
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
    
    public function setAtributos($atributos)
    {
        $this->atributos = $atributos;        
        return $this;
    }
    
    public function getAtributos()
    {
        return $this->atributos;
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
}