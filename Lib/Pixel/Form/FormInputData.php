<?php

namespace Pixel\Form;

class FormInputData extends \Zion\Form\FormInputData
{
    private $iconFA;
    private $toolTipMsg;
    private $emColunaDeTamanho;
    private $layoutPixel;
    private $processarJS;
    private $tipoFiltro;
    
    private $formSetPixel;
        
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        parent::__construct($acao, $nome, $identifica, $obrigatorio);
        
        $this->formSetPixel = new \Pixel\Form\FormSetPixel();
        
        $this->setIconFA('fa-calendar');

    }

    public function setDataMinima($dataMinima)
    {
        parent::setDataMinima($dataMinima);
        return $this;
    }
    
    public function setDataMaxima($dataMaxima)
    {
        parent::setDataMaxima($dataMaxima);
        return $this;
    }
    
    public function setPlaceHolder($placeHolder)
    {
        parent::setPlaceHolder($placeHolder);
        return $this;
    }

    public function setObrigarorio($obrigatorio)
    {
        parent::setObrigarorio($obrigatorio);
        return $this;
    }
    
    public function setLabel($label)
    {
        parent::setLabel($label);
        return $this;
    }

    public function setAliasSql($aliasSql){
        parent::setAliasSql($aliasSql);
        return $this;
    }

    public function setIconFA($iconFA)
    {
        $this->iconFA = $this->formSetPixel->setIconFA($iconFA);
        return $this;
    }

    public function getIconFA()
    {
        return $this->iconFA;
    }
    
    public function setToolTipMsg($toolTipMsg)
    {
        $this->toolTipMsg = $this->formSetPixel->setToolTipMsg($toolTipMsg);
        return $this;
    }

    public function getToolTipMsg()
    {
        return $this->toolTipMsg;
    }
    
    public function setEmColunaDeTamanho($emColunaDeTamanho)
    {        
        $this->emColunaDeTamanho = $this->formSetPixel->setEmColunaDeTamanho($emColunaDeTamanho);
        return $this;
    }

    public function getemColunaDeTamanho()
    {
        return $this->emColunaDeTamanho;
    }
    
    public function setLayoutPixel($layoutPixel)
    {
        $this->layoutPixel = $this->formSetPixel->setLayoutPixel($layoutPixel);
        return $this;
    }
    
    public function getLayoutPixel()
    {
        return $this->layoutPixel;
    }
    
        public function setProcessarJS($processarJS)
    {
        $this->processarJS = $this->formSetPixel->setProcessarJS($processarJS);
        return $this;
    }
    
    public function getProcessarJS()
    {
        return $this->processarJS;
    }
    
    public function seTipoFiltro($tipoFiltro)
    {
        $this->tipoFiltro = $this->formSetPixel->setTipoFiltro($tipoFiltro);
        return $this;
    }
    
    public function getTipoFiltro()
    {
        return $this->tipoFiltro;
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