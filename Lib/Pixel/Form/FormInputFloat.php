<?php

namespace Pixel\Form;

class FormInputFloat extends \Zion\Form\FormInputFloat
{
    private $iconFA;
    private $toolTipMsg;
    private $emColunaDeTamanho;
    private $layoutPixel;

    private $formSetPixel;
    
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        parent::__construct($acao, $nome, $identifica, $obrigatorio);
        
        $this->formSetPixel = new \Pixel\Form\FormSetPixel();
        
        $this->setIconFA('fa-calculator');
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
        parent::setLargura($largura);
        return $this;
    }
    
    public function setValorMaximo($valorMaximo)
    {
        parent::setValorMaximo($valorMaximo);
        return $this;
    }
    
    public function setValorMinimo($valorMinimo)
    {
        parent::setValorMinimo($valorMinimo);
        return $this;
    }
    
    public function setPrefixo($prefixo)
    {
        parent::setPrefixo($prefixo);
        return $this;
    }
    
    public function setObrigarorio($obrigatorio)
    {
        parent::setObrigarorio($obrigatorio);
        return $this;
    }
    
    public function setPlaceHolder($placeHolder)
    {
        parent::setPlaceHolder($placeHolder);
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

    public function getEmColunaDeTamanho()
    {
        return $this->emColunaDeTamanho ? $this->emColunaDeTamanho : 12;
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