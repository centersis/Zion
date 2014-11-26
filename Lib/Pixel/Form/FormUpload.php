<?php

namespace Pixel\Form;

class FormUpload extends \Zion\Form\FormUpload
{

    private $iconFA;
    private $toolTipMsg;
    private $emColunaDeTamanho;
    private $layoutPixel;
    private $processarJS;
    private $codigoReferencia;
    private $alturaMaxima;
    private $larguraMaxima;
    private $thumbnail;
    private $alturaMaximaTB;
    private $larguraMaximaTB;
    private $extensoesPermitidas;
    private $extensoesNaoPermitidas;
    private $tamanhoMaximoEmBytes;
    private $minimoArquivos;
    private $maximoArquivos;
    private $formSetPixel;

    public function __construct($acao, $nome, $identifica, $tratarComo)
    {
        parent::__construct($acao, $nome, $identifica, $tratarComo);

        $this->formSetPixel = new \Pixel\Form\FormSetPixel();

        $this->setIconFA('fa-file');
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

    public function setProcessarJS($processarJS)
    {
        $this->processarJS = $this->formSetPixel->setProcessarJS($processarJS);
        return $this;
    }

    public function getProcessarJS()
    {
        return $this->processarJS;
    }
    
    public function getCodigoReferencia()
    {
        return $this->codigoReferencia;
    }

    public function setCodigoReferencia($codigoReferencia)
    {
        $this->codigoReferencia = $codigoReferencia;
        return $this;
    }    
    
    public function getAlturaMaxima()
    {
        return $this->alturaMaxima;
    }

    public function setAlturaMaxima($alturaMaxima)
    {
        $this->alturaMaxima = $alturaMaxima;
        return $this;
    }

    public function getLarguraMaxima()
    {
        return $this->larguraMaxima;
    }

    public function setLarguraMaxima($larguraMaxima)
    {
        $this->larguraMaxima = $larguraMaxima;
        return $this;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function getAlturaMaximaTB()
    {
        return $this->alturaMaximaTB;
    }

    public function setAlturaMaximaTB($alturaMaximaTB)
    {
        $this->alturaMaximaTB = $alturaMaximaTB;
        return $this;
    }

    public function getLarguraMaximaTB()
    {
        return $this->larguraMaximaTB;
    }

    public function setLarguraMaximaTB($larguraMaximaTB)
    {
        $this->larguraMaximaTB = $larguraMaximaTB;
        return $this;
    }

    public function getExtensoesPermitidas()
    {
        return $this->extensoesPermitidas;
    }

    public function setExtensoesPermitidas($extensoesPermitidas)
    {
        $this->extensoesPermitidas = $extensoesPermitidas;
        return $this;
    }

    public function getExtensoesNaoPermitidas()
    {
        return $this->extensoesNaoPermitidas;
    }

    public function setExtensoesNaoPermitidas($extensoesNaoPermitidas)
    {
        $this->extensoesNaoPermitidas = $extensoesNaoPermitidas;
        return $this;
    }

    public function getTamanhoMaximoEmBytes()
    {
        return $this->tamanhoMaximoEmBytes;
    }

    public function setTamanhoMaximoEmBytes($tamanhoMaximoEmBytes)
    {
        $this->tamanhoMaximoEmBytes = $tamanhoMaximoEmBytes;
        return $this;
    }

    public function getMinimoArquivos()
    {
        return $this->minimoArquivos;
    }

    public function setMinimoArquivos($minimoArquivos)
    {
        $this->minimoArquivos = $minimoArquivos;
        return $this;
    }

    public function getMaximoArquivos()
    {
        return $this->maximoArquivos;
    }

    public function setMaximoArquivos($maximoArquivos)
    {
        $this->maximoArquivos = $maximoArquivos;
        return $this;
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

}
