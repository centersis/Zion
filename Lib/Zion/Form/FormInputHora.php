<?php

namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;
use \Zion\Validacao\Data as Data;

class FormInputHora extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao; 
    private $horaMinima;
    private $horaMaxima;
    private $placeHolder;
    private $obrigatorio;
    private $mostrarSegundos;
    
    private $hora;
    
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'hora';
        $this->acao = $acao;        
        $this->mostrarSegundos = false;
        
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
        
        $this->hora = new Data();
    }
    
    public function getTipoBase()
    {
        return $this->tipoBase;
    }
    
    public function getAcao()
    {
        return $this->acao;
    }

    public function setHoraMinima($horaMinima)
    {
        if($this->hora->validaHora($horaMinima) === true){

            if(isset($this->horaMaxima) and $this->hora->verificaDiferencaDataHora($this->horaMaxima, $horaMinima) == 1) {
                throw new FormException("horaMinima nao pode ser maior que horaMaxima.");
            }

            $this->horaMinima = $horaMinima;        
            return $this;

        } else {
            throw new FormException("horaMinima: O valor informado nao e uma hora valida.");
        }
    }
    
    public function getHoraMinima()
    {
        return $this->horaMinima;
    }
    
    public function setHoraMaxima($horaMaxima)
    {
        if($this->hora->validaHora($horaMaxima)){

            if(isset($this->horaMinima) and $this->hora->verificaDiferencaDataHora($this->horaMinima, $horaMaxima) == -1) {
                throw new FormException("horaMinima nao pode ser maior que horaMaxima.");
            }

            $this->horaMaxima = $horaMaxima;
            return $this;

        } else {
            throw new FormException("horaMaxima: O valor informado nao e uma hora valida.");
        }
    }
    
    public function getHoraMaxima()
    {
        return $this->horaMaxima;
    }
    
    public function setPlaceHolder($placeHolder)
    {
        if (!empty($placeHolder)) {
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

    public function setObrigarorio($obrigatorio)
    {
        if (is_bool($obrigatorio)) {
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
    
    public function setMostrarSegundos($mostrarSegundos)
    {
        if (is_bool($mostrarSegundos)) {
            $this->mostrarSegundos = $mostrarSegundos;
            return $this;
        } else {
            throw new FormException("mostrarSegundos: Valor nao booleano");
        }
    }
    
    public function getMostrarSegundos()
    {
        return $this->mostrarSegundos;
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