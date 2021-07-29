<?php
 
namespace Centersis\Zion\Form;

use Centersis\Zion\Exception\ErrorException;
use Centersis\Zion\Form\FormBasico;

class FormInputButton extends FormBasico
{
    private $tipoBase;
    private $acao;
    private $metodo;
    private $action;
    private $target;
    private $label;
    
    public function __construct($acao, $nome, $identifica)
    {
        $this->tipoBase = 'button';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setValor($identifica);
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
            throw new ErrorException("metodo: Nenhum valor informado.");
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
            throw new ErrorException("action: Nenhum valor informado.");
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
            throw new ErrorException("target: Nenhum valor informado.");
        }
    }
    
    public function getTarget()
    {
        return $this->target;
    }

    public function setLabel($label)
    {
        if(!is_null($label)){
            $this->label = $label;
            return $this;
        } else {
            throw new ErrorException("label: Nenhum valor informado.");
        }

    }
    
    public function getLabel()
    {
        return $this->label;
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
    
    public function setContainer($container)
    {
        parent::setContainer($container);
        return $this;
    }
}