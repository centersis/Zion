<?php

namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;
use \Zion\Form\Form as Form;

class FormInputTexto extends FormBasico
{
    private $tipoBase;
    private $acao;
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
    
    public function __construct($acao)
    {
        $this->tipoBase = 'texto';
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
        if(preg_match('/^[0-9]{1,}[%]{1}$|^[0-9]{1,}[px]{2}$|^[0-9]{1,}$/', $largura)){
            $this->largura = $largura;
            return $this;
        } else {
           throw new FormException("largura: O valor nao esta nos formatos aceitos: 10%; 10px; ou 10");
        }
    }
    
    public function getLargura()
    {
        return $this->largura;
    }
    
    public function setMaximoCaracteres($maximoCaracteres)
    {
        if(is_numeric($maximoCaracteres)){
            $this->maximoCaracteres = $maximoCaracteres;
            return $this;
        } else {
            throw new FormException("maximoCaracteres: Valor nao numerico.");
        }
    }
    
    public function getMaximoCaracteres()
    {
        return $this->maximoCaracteres;
    }
    
    public function setMinimoCaracteres($minimoCaracteres)
    {
        if(is_numeric($maximoCaracteres)){
            $this->minimoCaracteres = $minimoCaracteres;
            return $this;
        } else {
            throw new FormException("minimoCaracteres: Valor nao numerico.");
        }

    }
    
    public function getMinimoCaracteres()
    {
        return $this->minimoCaracteres;
    }   
    
    public function setMaiusculoMinusculo($maiusculoMinusculo)
    {
        if(strtoupper($maiusculoMinusculo) == "ALTA" or strtoupper($maiusculoMinusculo) == "BAIXA"){
            $this->maiusculoMinusculo = $maiusculoMinusculo;
            return $this;
        } else {
            throw new FormException("maiusculoMinusculo: Valor desconhecido: ". $maiusculoMinusculo);
        }
    }
    
    public function getMaiusculoMinusculo()
    {
        return $this->maiusculoMinusculo;
    } 
    
    public function setValorMinimo($valorMinimo)
    {
        if(!is_numeric($valorMinimo)){
            $this->valorMinimo = $valorMinimo;
            return $this;
        } else {
            throw new FormException("valorMinimo: Valor nao numerico");
        }
    }
    
    public function getValorMinimo()
    {
        return $this->valorMinimo;
    }
    
    public function setValorMaximo($valorMaximo)
    {
        if(!is_numeric($valorMaximo)){
            $this->valorMaximo = $valorMaximo;
            return $this;
        } else {
            throw new FormException("valorMaximo: Valor nao numerico");
        }
    }
    
    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }
    
    public function setMascara($mascara)
    {
        if(!empty($mascara)){
            $this->mascara = $mascara;
            return $this;
        } else {
            throw new FormException("mascara: Nenhum valor informado");
        }
    }
    
    public function getMascara()
    {
        return $this->mascara;
    }
    
    public function setObrigarorio($obrigatorio)
    {
        if(is_bool($obrigatorio)){
            $this->obrigatorio = $obrigatorio;
            return $this;
        } else {
            throw new FormException("obrigatorio: Valor nao booleano");
        }
    }
    
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }
    
    public function setConverterHtml($converterHtml)
    {
        if(is_bool($converterHtml)){
            $this->converterHtml = $converterHtml;
            return $this;
        } else {
            throw new FormException("converterHtml: Valor nao booleano");
        }
    }

    public function getConverterHtml()
    {
        return $this->converterHtml;
    }
    
    public function setAutoTrim($autoTrim)
    {
        if(is_bool($autoTrim)){
            $this->autoTrim = $autoTrim;
            return $this;
        } else {
            throw new FormException("autoTrim: Valor nao booleano");
        }

    }
    
    public function getAutoTrim()
    {
        return $this->autoTrim;
    }
    
    public function setPlaceHolder($placeHolder)
    {
        if(!empty($placeHolder)){
            $this->placeHolder = $placeHolder;
            return $this;
        } else {
            throw new FormException("placeHolder: Nenhum valor informado");
        }
    }
    
    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }
    
    public function setAutoComplete($autoComplete)
    {
        if(is_bool($autoComplete)){
            $this->autoComplete = $autoComplete;
            return $this;
        } else {
            throw new FormException("autoComplete: Valor desconhecido: ". $autoComplete);
        }
    }
    
    public function getAutoComplete()
    {
        return $this->autoComplete;
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