<?php

namespace Pixel\Form;

class FormInputTexto extends \Zion\Form\FormInputTexto
{
    private $prefixo;
    private $mascara;
    private $iconFA;
    private $toolTipMsg;
    private $emColunaDeTamanho;
    private $layoutPixel;
    private $labelAntes;
    //private $labelDepois;
    private $processarJS;
    private $tipoFiltro;

    private $formSetPixel;
    
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        parent::__construct($acao, $nome, $identifica, $obrigatorio); 
        
        $this->tipoFiltro = 'texto';
        $this->formSetPixel = new \Pixel\Form\FormSetPixel();
    }
    
    public function setLargura($largura)
    {
        parent::setLargura($largura);
        return $this;
    }

    public function setMaximoCaracteres($maximoCaracteres)
    {
        parent::setMaximoCaracteres($maximoCaracteres);
        return $this;
    }

    public function setMinimoCaracteres($minimoCaracteres)
    {
        parent::setMinimoCaracteres($minimoCaracteres);
        return $this;
    }

    public function setCaixa($caixa)
    {
        parent::setCaixa($caixa);
        return $this;
    }

    public function setValorMinimo($valorMinimo)
    {
        parent::setValorMinimo($valorMinimo);
        return $this;
    }

    public function setValorMaximo($valorMaximo)
    {
        parent::setValorMaximo($valorMaximo);
        return $this;
    }

    public function setAliasSql($aliasSql){
        parent::setAliasSql($aliasSql);
        return $this;
    }

    public function setPrefixo($prefixo)
    {
        $this->prefixo = $prefixo;
        return $this;
    }

    public function getPrefixo()
    {
        return $this->prefixo;
    }
    
    public function setMascara($mascara)
    {
        $this->mascara = $this->formSetPixel->setMascara($mascara);
        return $this;
    }

    public function getMascara()
    {
        return $this->mascara;
    }

    public function setObrigarorio($obrigatorio)
    {
        parent::setObrigarorio($obrigatorio);
        return $this;
    }

    public function setConverterHtml($converterHtml)
    {
        parent::setConverterHtml($converterHtml);
        return $this;
    }

    public function setAutoTrim($autoTrim)
    {
        parent::setAutoTrim($autoTrim);
        return $this;
    }

    public function setPlaceHolder($placeHolder)
    {
        parent::setPlaceHolder($placeHolder);
        return $this;
    }

    public function setAutoComplete($autoComplete)
    {
        parent::setAutoComplete($autoComplete);
        return $this;
    }

    public function setDeveSerIgualA($deveSerIgualA)
    {
        parent::setDeveSerIgualA($deveSerIgualA);
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

    public function setLabelAntes($labelAntes)
    {
        $this->labelAntes = $this->formSetPixel->setLabelAntes($labelAntes);
        return $this;
    }
    
    public function getLabelAntes()
    {
        return $this->labelAntes;
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
    
    public function setTipoFiltro($tipoFiltro)
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

    public function setLabelDepois($label)
    {
        $this->labelDepois = $this->formSetPixel->setLabelDepois($label);
        return $this;
    }

    public function getLabelDepois()
    {
        return $this->labelDepois;
    }     

}
