<?php

namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;
use \Zion\Validacao\Data as Data;

class FormInputDateTime extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao; 
    private $dataMinima;
    private $dataMaxima;
    
    private $data;
    
    public function __construct($acao)
    {
        $this->tipoBase = 'dateTime';
        $this->acao = $acao;
        $this->data = new Data();
    }
    
    public function getTipoBase()
    {
        return $this->tipoBase;
    }
    
    public function getAcao()
    {
        return $this->acao;
    }

    public function setDataMinima($dataMinima)
    {
        if($this->data->validaData($dataMinima) === true){
            
            if(isset($this->dataMaxima) and ($dataMinima > $this->dataMaxima)) {
                throw new FormException("dataMinima nao pode ser maior que dataMaxima.");
                return;
            }

            $this->dataMinima = $dataMinima;        
            return $this;

        } else {
            throw new FormException("dataMinima: O valor informado nao e uma data/hora valida.");
        }
    }
    
    public function getDataMinima()
    {
        return $this->dataMinima;
    }
    
    public function setDataMaxima($dataMaxima)
    {
        if($this->data->validaData($dataMaxima)){
            
            if(isset($this->dataMinima) and ($dataMaxima < $this->dataMinima)) {
                throw new FormException("dataMaxima nao pode ser menor que dataMinima.");
                return;
            }

            $this->dataMaxima = $dataMaxima;
            return $this;

        } else {
            throw new FormException("dataMaxima: O valor informado nao e uma data/hora valida.");
        }
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