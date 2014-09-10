<?php

namespace Zion\Form;

class FormInputDateVo extends FormBasicoVo
{
    private $tipoBasico;
    private $tipo;    
    private $dataMinima;
    private $dataMaxima;
    
    public function __construct($tipo)
    {
        $this->tipoBasico = 'date';
        $this->tipo = $tipo;
    }
    
    public function getTipoBasico()
    {
        return $this->tipoBasico;
    }
    
    public function getTipo()
    {
        return $this->tipo;
    }
    
    public function setDataMinima($dataMinima)
    {
        $this->dataMinima = $dataMinima;
        return $this;
    }
    
    public function getDataMinima()
    {
        return $this->dataMinima;
    }
    
    public function setDataMaxima($dataMaxima)
    {
        $this->dataMaxima = $dataMaxima;
        return $this;
    }
    
    public function getDataMaxima()
    {
        return $this->dataMaxima;
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