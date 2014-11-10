<?php

/**
 * \Zion\Form\FormInputHora()
 * 
 * @author The Sappiens Team
 * @copyright 2014
 * @version 2014
 * @access public
 */
 
namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;
use \Zion\Validacao\Data as Data;

class FormInputHora extends \Zion\Form\FormBasico
{
   /**
    * @var mixed $tipoBase
    */
    private $tipoBase;
    private $acao; 
    private $obrigatorio;
    private $horaMinima;
    private $horaMaxima;
    private $placeHolder;
    private $mostrarSegundos;
    private $aliasSql;
    
    private $hora;
    
    /**
     * FormInputHora::__construct()
     * 
     * @return
     */
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
    
    /**
     * FormInputHora::getTipoBase()
     * 
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }
    
    /**
     * FormInputHora::getAcao()
     * 
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * FormInputHora::setHoraMinima()
     * 
     * @return
     */
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
    
    /**
     * FormInputHora::getHoraMinima()
     * 
     * @return
     */
    public function getHoraMinima()
    {
        return $this->horaMinima;
    }
    
    /**
     * FormInputHora::setHoraMaxima()
     * 
     * @return
     */
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
    
    /**
     * FormInputHora::getHoraMaxima()
     * 
     * @return
     */
    public function getHoraMaxima()
    {
        return $this->horaMaxima;
    }
    
    /**
     * FormInputHora::setPlaceHolder()
     * 
     * @return
     */
    public function setPlaceHolder($placeHolder)
    {
        if (!empty($placeHolder)) {
            $this->placeHolder = $placeHolder;
            return $this;
        } else {
            throw new FormException("placeHolder: Nenhum valor informado");
        }
    }

    /**
     * FormInputHora::getPlaceHolder()
     * 
     * @return
     */
    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }

    /**
     * FormInputHora::setObrigarorio()
     * 
     * @return
     */
    public function setObrigarorio($obrigatorio)
    {
        if (is_bool($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
            return $this;
        } else {
            throw new FormException("obrigatorio: Valor nao booleano");
        }
    }

    /**
     * FormInputHora::getObrigatorio()
     * 
     * @return
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }
    
    /**
     * FormInputHora::setMostrarSegundos()
     * 
     * @return
     */
    public function setMostrarSegundos($mostrarSegundos)
    {
        if (is_bool($mostrarSegundos)) {
            $this->mostrarSegundos = $mostrarSegundos;
            return $this;
        } else {
            throw new FormException("mostrarSegundos: Valor nao booleano");
        }
    }
    
    /**
     * FormInputHora::getMostrarSegundos()
     * 
     * @return
     */
    public function getMostrarSegundos()
    {
        return $this->mostrarSegundos;
    }

    /**
     * FormInputHora::setAliasSql()
     * 
     * @param string $aliasSql
     *
     */
    public function setAliasSql($aliasSql)
    {
        if (!is_null($aliasSql)) {
            $this->aliasSql = $aliasSql;
            return $this;
        } else {
            throw new FormException("aliasSql: Nenhum valor informado");
        }
    }

    /**
     * FormInputHora::getAliasSql()
     * 
     * @return string
     */
    public function getAliasSql(){
        return $this->aliasSql;
    }

    /**
     * Sobrecarga de Metodos Básicos
     */

    /**
     * FormInputHora::setId()
     * 
     * @return
     */
    public function setId($id)
    {
        parent::setId($id);        
        return $this;
    }
    
    /**
     * FormInputHora::setNome()
     * 
     * @return
     */
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }
    
    /**
     * FormInputHora::setIdentifica()
     * 
     * @return
     */
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }
    
    /**
     * FormInputHora::setValor()
     * 
     * @return
     */
    public function setValor($valor)
    {              
        parent::setValor($valor);
        return $this;
    }
    
    /**
     * FormInputHora::setValorPadrao()
     * 
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }
    
    /**
     * FormInputHora::setDisabled()
     * 
     * @return
     */
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }
    
    /**
     * FormInputHora::setComplemento()
     * 
     * @return
     */
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    /**
     * FormInputHora::setAtributos()
     * 
     * @return
     */
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }
    
    /**
     * FormInputHora::setClassCss()
     * 
     * @return
     */
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