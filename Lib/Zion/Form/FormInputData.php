<?php

/**
 * \Zion\Form\FormInputData()
 * 
 * @author The Sappiens Team
 * @copyright 2014
 * @version 2014
 * @access public
 */
 
namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;
use \Zion\Validacao\Data as Data;

class FormInputData extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao; 
    private $obrigatorio;
    private $dataMinima;
    private $dataMaxima;
    private $placeHolder;
    private $aliasSql;
    
    private $data;
    
    /**
     * FormInputData::__construct()
     * 
     * @return
     */
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'data';
        $this->acao = $acao;        
        $this->mostrarSegundos = false;
        
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
        
        $this->data = new Data();
    }
    
    /**
     * FormInputData::getTipoBase()
     * 
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }
    
    /**
     * FormInputData::getAcao()
     * 
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * FormInputData::setDataMinima()
     * 
     * @return
     */
    public function setDataMinima($dataMinima)
    {
        if($this->data->validaData($dataMinima) === true){

            if(isset($this->dataMaxima) and $this->data->verificaDiferencaDataHora($this->dataMaxima, $dataMinima) == 1) {
                throw new FormException("dataMinima nao pode ser maior que dataMaxima.");
            }

            $this->dataMinima = $dataMinima;        
            return $this;

        } else {
            throw new FormException("dataMinima: O valor informado nao e uma data/hora valida.");
        }
    }
    
    /**
     * FormInputData::getDataMinima()
     * 
     * @return
     */
    public function getDataMinima()
    {
        return $this->dataMinima;
    }
    
    /**
     * FormInputData::setDataMaxima()
     * 
     * @return
     */
    public function setDataMaxima($dataMaxima)
    {
        if($this->data->validaData($dataMaxima)){

            if(isset($this->dataMinima) and $this->data->verificaDiferencaDataHora($this->dataMinima, $dataMaxima) == -1) {
                throw new FormException("dataMinima nao pode ser maior que dataMaxima.");
            }

            $this->dataMaxima = $dataMaxima;
            return $this;

        } else {
            throw new FormException("dataMaxima: O valor informado nao e uma data/hora valida.");
        }
    }
    
    /**
     * FormInputData::getDataMaxima()
     * 
     * @return
     */
    public function getDataMaxima()
    {
        return $this->dataMaxima;
    }
    
    /**
     * FormInputData::setPlaceHolder()
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
     * FormInputData::getPlaceHolder()
     * 
     * @return
     */
    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }

    /**
     * FormInputData::setObrigarorio()
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
     * FormInputData::getObrigatorio()
     * 
     * @return
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }
    
    /**
     * FormInputData::getAliasSql()
     * 
     * @return string
     */
    public function getAliasSql(){
        return $this->aliasSql;
    }

    /**
     * FormInputData::setAliasSql()
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
     * Sobrecarga de Metodos Básicos
     */

    /**
     * FormInputData::setId()
     * 
     * @return
     */
    public function setId($id)
    {
        parent::setId($id);        
        return $this;
    }
    
    /**
     * FormInputData::setNome()
     * 
     * @return
     */
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }
    
    /**
     * FormInputData::setIdentifica()
     * 
     * @return
     */
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }
    
    /**
     * FormInputData::setValor()
     * 
     * @return
     */
    public function setValor($valor)
    {              
        parent::setValor($valor);
        return $this;
    }
    
    /**
     * FormInputData::setValorPadrao()
     * 
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }
    
    /**
     * FormInputData::setDisabled()
     * 
     * @return
     */
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }
    
    /**
     * FormInputData::setComplemento()
     * 
     * @return
     */
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    /**
     * FormInputData::setAtributos()
     * 
     * @return
     */
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }
    
    /**
     * FormInputData::setClassCss()
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