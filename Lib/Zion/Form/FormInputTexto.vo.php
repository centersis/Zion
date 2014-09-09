<?php
include_once './FormBasico.vo.php';

class FormInputTextoVo extends FormBasicoVo
{
    private $tipoBasico;
    private $tipo;
    private $largura;
    private $maximoCaracteres;
    private $minimoCaracteres;
    private $valorMaximo;
    private $valorMinimo;
    private $maiusculoMinusculo;
    private $mascara;
    private $obrigatorio;
    private $converterHtml;
    private $autoTrim;
    private $placeHolder;
    private $autoComplete;
    
    public function __construct($tipo)
    {
        $this->tipoBasico = 'texto';
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

    public function setLargura($largura)
    {
        $this->largura = $largura;
        return $this;
    }
    
    public function getLargura()
    {
        return $this->largura;
    }
    
    public function setMaximoCaracteres($maximoCaracteres)
    {
        $this->maximoCaracteres = $maximoCaracteres;
        return $this;
    }
    
    public function getMaximoCaracteres()
    {
        return $this->maximoCaracteres;
    }
    
    public function setMinimoCaracteres($minimoCaracteres)
    {
        $this->minimoCaracteres = $minimoCaracteres;
        return $this;
    }
    
    public function getMinimoCaracteres()
    {
        return $this->minimoCaracteres;
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
    
    public function setValorMinimo($valorMinimo)
    {
        $this->valorMinimo = $valorMinimo;
        return $this;
    }
    
    public function getValorMinimo()
    {
        return $this->valorMinimo;
    }
    
    public function setValorMaximo($valorMaximo)
    {
        $this->valorMaximo = $valorMaximo;
        return $this;
    }
    
    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }
    
    public function setMascara($mascara)
    {
        $this->mascara = $mascara;
        return $this;
    }
    
    public function getMascara()
    {
        return $this->mascara;
    }
    
    public function setObrigarorio($obrigatorio)
    {
        $this->obrigatorio = $obrigatorio;        
        return $this;
    }
    
    public function getObrigatorio()
    {
        return $this->obrigatorio;
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
    
    public function setAutoComplete($autoComplete)
    {
        $this->autoComplete = $autoComplete;
        return $this;
    }
    
    public function getAutoComplete()
    {
        return $this->autoComplete;
    }
}