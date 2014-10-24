<?php

/**
 * \Zion\Form\FormInputButton()
 * 
 * @author The Sappiens Team
 * @copyright Sappiens 2014
 * @version 2014
 * @access public
 */
 
namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;

class FormInputButton extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao;
    private $metodo;
    private $action;
    private $target;
    
    /**
     * FormInputButton::__construct()
     * 
     * @param mixed $acao
     * @param mixed $nome
     * @param mixed $identifica
     * @return
     */
    public function __construct($acao, $nome, $identifica)
    {
        $this->tipoBase = 'button';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setValor($identifica);
    }
    
    /**
     * FormInputButton::getTipoBase()
     * 
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }
    
    /**
     * FormInputButton::getAcao()
     * 
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }
    
    /**
     * FormInputButton::setMetodo()
     * 
     * @param mixed $metodo
     * @return
     */
    public function setMetodo($metodo)
    {
        if(!is_null($metodo)){
            $this->metodo = $metodo;        
            return $this;
        } else {
            throw new FormException("metodo: Nenhum valor informado.");
        }
    }
    
    /**
     * FormInputButton::getMetodo()
     * 
     * @return
     */
    public function getMetodo()
    {
        return $this->metodo;
    }
    
    /**
     * FormInputButton::setAction()
     * 
     * @param mixed $action
     * @return
     */
    public function setAction($action)
    {
        if(!is_null($action)){
            $this->action = $action;
            return $this;
        } else {
            throw new FormException("action: Nenhum valor informado.");
        }

    }
    
    /**
     * FormInputButton::getAction()
     * 
     * @return
     */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
     * FormInputButton::setTarget()
     * 
     * @param mixed $target
     * @return
     */
    public function setTarget($target)
    {
        if(!is_null($target)){
            $this->target = $target;
            return $this;
        } else {
            throw new FormException("target: Nenhum valor informado.");
        }
    }
    
    /**
     * FormInputButton::getTarget()
     * 
     * @return
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Sobrecarga de Metodos Básicos
     */    
    /**
     * FormInputButton::setId()
     * 
     * @param mixed $id
     * @return
     */
    public function setId($id)
    {
        parent::setId($id);        
        return $this;
    }
    
    /**
     * FormInputButton::setNome()
     * 
     * @param mixed $nome
     * @return
     */
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }
    
    /**
     * FormInputButton::setIdentifica()
     * 
     * @param mixed $identifica
     * @return
     */
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }
    
    /**
     * FormInputButton::setValor()
     * 
     * @param mixed $valor
     * @return
     */
    public function setValor($valor)
    {              
        parent::setValor($valor);
        return $this;
    }
    
    /**
     * FormInputButton::setValorPadrao()
     * 
     * @param mixed $valorPadrao
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }
    
    /**
     * FormInputButton::setDisabled()
     * 
     * @param mixed $disabled
     * @return
     */
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }
    
    /**
     * FormInputButton::setComplemento()
     * 
     * @param mixed $complemento
     * @return
     */
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    /**
     * FormInputButton::setAtributos()
     * 
     * @param mixed $atributos
     * @return
     */
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }
    
    /**
     * FormInputButton::setClassCss()
     * 
     * @param mixed $classCss
     * @return
     */
    public function setClassCss($classCss)
    {
        parent::setClassCss($classCss);
        return $this;
    }
}