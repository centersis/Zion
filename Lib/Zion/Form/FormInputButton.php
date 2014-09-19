<?php

namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;

class FormInputButton extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao;
    private $metodo;
    private $action;
    private $target;
    
    public function __construct($acao)
    {
        $this->tipoBase = 'button';
        $this->acao = $acao;
    }
    
    public function getTipoBase()
    {
        return $this->tipoBase;
    }
    
    public function getAcao()
    {
        return $this->acao;
    }
    
    public function setMetodo($metodo)
    {
        if(!is_null($metodo)){
            $this->metodo = $metodo;        
            return $this;
        } else {
            throw new FormException("metodo: Nenhum valor informado.");
        }
    }
    
    public function getMetodo()
    {
        return $this->metodo;
    }
    
    public function setAction($action)
    {
        if(!is_null($action)){
            $this->action = $action;
            return $this;
        } else {
            throw new FormException("action: Nenhum valor informado.");
        }

    }
    
    public function getAction()
    {
        return $this->action;
    }
    
    public function setTarget($target)
    {
        if(!is_null($target)){
            $this->target = $target;
            return $this;
        } else {
            throw new FormException("target: Nenhum valor informado.");
        }
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
        if(!empty($id)){
            parent::setId($id);        
            return $this;
        } else {
            throw new FormException("id: Nenhum valor informado.");
        }
    }
    
    public function setNome($nome)
    {
        if(!empty($nome)){
             parent::setNome($nome);
            return $this;
        } else {
            throw new FormException("nome: Nenhum valor informado.");
        }
    }
    
    public function setIdentifica($identifica)
    {
        if(!empty($identifica)){
             parent::setIdentifica($identifica);
            return $this;
        } else {
            throw new FormException("identifica: Nenhum valor informado.");
        }
    }
    
    public function setValor($valor)
    {              
        if(!empty($valor)){
             parent::setValor($valor);
            return $this;
        } else {
            throw new FormException("valor: Nenhum valor informado.");
        }
    }
    
    public function setValorPadrao($valorPadrao)
    {
        if(!empty($valorPadrao)){
             parent::setValorPadrao($valorPadrao);
            return $this;
        } else {
            throw new FormException("valorPadrao: Nenhum valor informado.");
        }
    }
    
    public function setDisabled($disabled)
    {
        if(!empty($disabled)){
             parent::setDisabled($disabled);
            return $this;
        } else {
            throw new FormException("disabled: Nenhum valor informado.");
        }
    }
    
    public function setComplemento($complemento)
    {
        if(!empty($complemento)){
             parent::setComplemento($complemento);
            return $this;
        } else {
            throw new FormException("complemento: Nenhum valor informado.");
        }
    }

    public function setAtributos($atributos)
    {
        if(!empty($atributos)){
             parent::setAtributos($atributos);
            return $this;
        } else {
            throw new FormException("atributos: Nenhum valor informado.");
        }
    }
    
    public function setClassCss($classCss)
    {
        if(!empty($classCss)){
             parent::setClassCss($classCss);
            return $this;
        } else {
            throw new FormException("classCss: Nenhum valor informado.");
        }
    }
}