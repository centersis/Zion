<?php

namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;

class FormInputNumber extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao;
    private $valorMaximo;
    private $valorMinimo;
    
    public function __construct($acao)
    {
        $this->tipoBase = 'number';
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
    
    public function setValorMaximo($valorMaximo)
    {
        if(is_numeric($valorMaximo)){
            $this->valorMaximo = $valorMaximo;
            return $this;
        } else {
            throw new FormException("valorMaximo: O valor informado nao e numerico.");
        }
    }
    
    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }
    
    public function setValorMinimo($valorMinimo)
    {
        if(is_numeric($valorMinimo)){
            $this->valorMinimo = $valorMinimo;
            return $this;
        } else {
            throw new FormException("valorMinimo: O valor informado nao e numerico.");
        }
    }
    
    public function getValorMinimo()
    {
        return $this->valorMinimo;
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