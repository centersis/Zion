<?php

/**
 * \Zion\Form\FormBasico()
 * 
 * @author The Sappiens Team
 * @copyright Sappiens 2014
 * @version 2014
 * @access public
 */
 
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
    private $container;

    /**
     * FormBasico::setId()
     * 
     * @param mixed $id
     * @return
     */
    public function setId($id)
    {
        if(!empty($id)){
            $this->id = $id;
            return $this;
        } else {
            throw new FormException("id: Nenhum valor informado.");
        }
    }

    /**
     * FormBasico::getId()
     * 
     * @return
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * FormBasico::setNome()
     * 
     * @param mixed $nome
     * @return
     */
    public function setNome($nome)
    {
        if(!empty($nome)){
             $this->nome = $nome;
            return $this;
        } else {
            throw new FormException("nome: Nenhum valor informado.");
        }
    }

    /**
     * FormBasico::getNome()
     * 
     * @return
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * FormBasico::setIdentifica()
     * 
     * @param mixed $identifica
     * @return
     */
    public function setIdentifica($identifica)
    {
        if(!empty($identifica)){
             $this->identifica = $identifica;
            return $this;
        } else {
            throw new FormException("identifica: Nenhum valor informado.");
        }
    }

    /**
     * FormBasico::getIdentifica()
     * 
     * @return
     */
    public function getIdentifica()
    {
        return $this->identifica;
    }
  
    /**
     * FormBasico::setValor()
     * 
     * @param mixed $valor
     * @return
     */
    public function setValor($valor)
    {              
             $this->valor = $valor;
            return $this;
    }

    /**
     * FormBasico::getValor()
     * 
     * @return
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * FormBasico::setValorPadrao()
     * 
     * @param mixed $valorPadrao
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        if(!empty($valorPadrao)){
             $this->valorPadrao = $valorPadrao;
            return $this;
        } else {
            throw new FormException("valorPadrao: Nenhum valor informado.");
        }
    }

    /**
     * FormBasico::getValorPadrao()
     * 
     * @return
     */
    public function getValorPadrao()
    {
        return $this->valorPadrao;
    }

    /**
     * FormBasico::setDisabled()
     * 
     * @param mixed $disabled
     * @return
     */
    public function setDisabled($disabled)
    {
        if(!empty($disabled)){
             $this->disabled = $disabled;
            return $this;
        } else {
            throw new FormException("disabled: Nenhum valor informado.");
        }
    }

    /**
     * FormBasico::getDisabled()
     * 
     * @return
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * FormBasico::setComplemento()
     * 
     * @param mixed $complemento
     * @return
     */
    public function setComplemento($complemento)
    {
        if(!empty($complemento)){
             $this->complemento = $complemento;
            return $this;
        } else {
            throw new FormException("complemento: Nenhum valor informado.");
        }
    }

    /**
     * FormBasico::getComplemento()
     * 
     * @return
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * FormBasico::setAtributos()
     * 
     * @param mixed $atributos
     * @return
     */
    public function setAtributos($atributos)
    {
        if(!empty($atributos)){
             $this->atributos = $atributos;
            return $this;
        } else {
            throw new FormException("atributos: Nenhum valor informado.");
        }
    }

    /**
     * FormBasico::getAtributos()
     * 
     * @return
     */
    public function getAtributos()
    {
        return $this->atributos;
    }

    /**
     * FormBasico::setClassCss()
     * 
     * @param mixed $classCss
     * @return
     */
    public function setClassCss($classCss)
    {
        if(!empty($classCss)){
             $this->classCss = $classCss;
            return $this;
        } else {
            throw new FormException("classCss: Nenhum valor informado.");
        }
    }
    
    /**
     * FormBasico::getClassCss()
     * 
     * @return
     */
    public function getClassCss()
    {
        return $this->classCss;
    }

    
    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }
}