<?php
namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;

abstract class FormBasico
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
        if(!empty($id)){
            $this->id = $id;
            return $this;
        } else {
            throw new FormException("id: Nenhum valor informado.");
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setNome($nome)
    {
        if(!empty($nome)){
             $this->nome = $nome;
            return $this;
        } else {
            throw new FormException("nome: Nenhum valor informado.");
        }
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setIdentifica($identifica)
    {
        if(!empty($identifica)){
             $this->identifica = $identifica;
            return $this;
        } else {
            throw new FormException("identifica: Nenhum valor informado.");
        }
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
        if(!empty($valorPadrao)){
             $this->valorPadrao = $valorPadrao;
            return $this;
        } else {
            throw new FormException("valorPadrao: Nenhum valor informado.");
        }
    }

    public function getValorPadrao()
    {
        return $this->valorPadrao;
    }

    public function setDisabled($disabled)
    {
        if(!empty($disabled)){
             $this->disabled = $disabled;
            return $this;
        } else {
            throw new FormException("disabled: Nenhum valor informado.");
        }
    }

    public function getDisabled()
    {
        return $this->disabled;
    }

    public function setComplemento($complemento)
    {
        if(!empty($complemento)){
             $this->complemento = $complemento;
            return $this;
        } else {
            throw new FormException("complemento: Nenhum valor informado.");
        }
    }

    public function getComplemento()
    {
        return $this->complemento;
    }

    public function setAtributos($atributos)
    {
        if(!empty($atributos)){
             $this->atributos = $atributos;
            return $this;
        } else {
            throw new FormException("atributos: Nenhum valor informado.");
        }
    }

    public function getAtributos()
    {
        return $this->atributos;
    }

    public function setClassCss($classCss)
    {
        if(!empty($classCss)){
             $this->classCss = $classCss;
            return $this;
        } else {
            throw new FormException("classCss: Nenhum valor informado.");
        }
    }
    
    public function getClassCss()
    {
        return $this->classCss;
    }

}