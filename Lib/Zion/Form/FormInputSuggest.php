<?php

namespace Zion\Form;

class FormInputSuggest extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao;
    private $largura;
    private $maiusculoMinusculo;
    private $converterHtml;
    private $autoTrim;
    private $placeHolder;
    
    public function __construct($acao)
    {
        $this->tipoBase = 'suggest';
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

    public function setLargura($largura)
    {
        $this->largura = $largura;
        return $this;
    }
    
    public function getLargura()
    {
        return $this->largura;
    }  
    
    public function setMaiusculoMinusculo($maiusculoMinusculo)
    {
        $this->maiusculoMinusculo = $maiusculoMinusculo;
        return $this;
    }
    
    public function getMaiusculoMinusculo()
    {
        return $this->maiusculoMinusculo;
    } 
    
    public function setConverterHtml($converterHtml)
    {
        $this->converterHtml = $converterHtml;
        return $this;
    }
    
    public function getConverterHtml()
    {
        return $this->converterHtml;
    }
    
    public function setAutoTrim($autoTrim)
    {
        $this->autoTrim = $autoTrim;
        return $this;
    }
    
    public function getAutoTrim()
    {
        return $this->autoTrim;
    }
    
    public function setPlaceHolder($placeHolder)
    {
        $this->placeHolder = $placeHolder;
        return $this;
    }
    
    public function getPlaceHolder()
    {
        return $this->placeHolder;
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