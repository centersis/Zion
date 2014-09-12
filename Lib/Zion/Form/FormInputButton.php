<?php

namespace Zion\Form;

class FormInputButton extends \Zion\Form\FormBasico
{
    private $tipoHtml;
    private $acao;
    private $metodo;
    private $action;
    private $target;
    
    public function __construct($acao)
    {
        $this->tipoHtml = 'button';
        $this->acao = $acao;
    }
    
    public function getTipoHtml()
    {
        return $this->tipoHtml;
    }
    
    public function getAcao()
    {
        return $this->acao;
    }
    
    public function setMetodo($metodo)
    {
        $this->metodo = $metodo;
        return $this;
    }
    
    public function getMetodo()
    {
        return $this->metodo;
    }
    
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
    
    public function getAction()
    {
        return $this->action;
    }
    
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }
    
    public function getTarget()
    {
        return $this->target;
    }
    
    /**
     * Sobrecarga de Metodos Básicos
     */    
        public function setId($id)
    {
        parent::setId($id);        
        return $this;
    }
    
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }
    
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);       
        return $this;
    }
    
    public function setValor($valor)
    {
        parent::setValor($valor);      
        return $this;
    }
    
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);        
        return $this;
    }
    
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);     
        return $this;
    }
    
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);       
        return $this;
    }
    
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);       
        return $this;
    }
    
    public function setClassCss($classCss)
    {
        parent::setClassCss($classCss);       
        return $this;
    }
}