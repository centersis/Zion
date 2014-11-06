<?php

namespace Pixel\Form;

class FormChosen extends \Zion\Form\FormEscolha
{

    private $toolTipMsg;
    private $emColunaDeTamanho;
    private $layoutPixel;
   
    private $formSetPixel;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        parent::__construct($acao, $nome, $identifica, $obrigatorio);
        parent::setChosen(true);
        $this->setMultiplo(false);
        $this->formSetPixel = new \Pixel\Form\FormSetPixel();
    }

    public function setObrigarorio($obrigatorio)
    {
        parent::setObrigarorio($obrigatorio);
        return $this;
    }
    
    public function setSelecaoMaxima($selecaoMaxima)
    {
        parent::setSelecaoMaxima($selecaoMaxima);
        return $this;
    }
    
    public function setSelecaoMinima($selecaoMinima)
    {
        parent::setSelecaoMinima($selecaoMinima);
        return $this;
    }
    
    public function setMultiplo($multiplo)
    {
        parent::setMultiplo($multiplo);
        return $this;
    }

    public function setOrdena($ordena)
    {
        parent::setOrdena($ordena);
        return $this;
    }

    public function setArray($array)
    {
        parent::setArray($array);
        return $this;
    }

    public function setInicio($inicio)
    {
        parent::setInicio($inicio);
        return $this;
    }

    public function setTabela($tabela)
    {
        parent::setTabela($tabela);
        return $this;
    }

    public function setCampoCod($campoCod)
    {
        parent::setCampoCod($campoCod);
        return $this;
    }

    public function setCampoDesc($campoDesc)
    {
        parent::setCampoDesc($campoDesc);
        return $this;
    }

    public function setWhere($where)
    {
        parent::setWhere($where);
        return $this;
    }

    public function setSqlCompleto($sqlCompleto)
    {
        parent::setSqlCompleto($sqlCompleto);
        return $this;
    }

    public function setIdConexao($idConexao)
    {
        parent::setIdConexao($idConexao);
        return $this;
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
