<?php

namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;
use \Zion\Validacao\Numero as Numero;

class FormInputFloat extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao; 
    private $valorMaximo;
    private $valorMinimo;
    private $prefixo;
    
    private $numero;
    
    public function __construct($acao)
    {
        $this->tipoBase = 'float';
        $this->acao = $acao;
        $this->numero = new Numero();
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
        if($this->numero->isFloat($valorMaximo) === true){

            if(isset($this->valorMinimo) and ($valorMaximo < $this->valorMinimo)) {
                throw new FormException("valorMaximo nao pode ser menor que valorMinimo.");
                return;
            }

            $this->valorMaximo = $valorMaximo;
            return $this;
        } else {
            throw new FormException("valorMaximo: O valor informado nao e float.");
        }
    }
    
    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }
    
    public function setValorMinimo($valorMinimo)
    {
        if($this->numero->isFloat($valorMinimo) === true){

            if(isset($this->valorMaximo) and ($valorMinimo > $this->valorMaximo)) {
                throw new FormException("valorMinimo nao pode ser maior que valorMaximo.");
                return;
            }

            $this->valorMinimo = $valorMinimo;
            return $this;
        } else {
            throw new FormException("valorMinimo: O valor informado nao e float.");
        }
    }
    
    public function getValorMinimo()
    {
        return $this->valorMinimo;
    }
    
    public function setPrefixo($prefixo)
    {
        if(!is_null($prefixo)){
            $this->prefixo = $prefixo;
            return $this;
        } else {
            throw new FormException("prefixo: Nenhum valor informado.");
        }
    }
    
    public function getPrefixo()
    {
        return $this->prefixo;
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