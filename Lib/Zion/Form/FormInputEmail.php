<?php

/**
 * \Zion\Form\FormInputEmail()
 * 
 * @author The Sappiens Team
 * @copyright 2014
 * @version 2014
 * @access public
 */

namespace Zion\Form;

use \Zion\Form\Exception\FormException as FormException;

class FormInputEmail extends FormBasico
{

    private $tipoBase;
    private $acao;
    private $maximoCaracteres;
    private $minimoCaracteres;
    private $obrigatorio;
    private $placeHolder;
    private $aliasSql;

    /**
     * FormInputEmail::__construct()
     * 
     * @return
     */
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'email';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
    }

    /**
     * FormInputEmail::getTipoBase()
     * 
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    /**
     * FormInputEmail::getAcao()
     * 
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * FormInputEmail::setMaximoCaracteres()
     * 
     * @return
     */
    public function setMaximoCaracteres($maximoCaracteres)
    {
        if (is_numeric($maximoCaracteres)) {

            if (isset($this->minimoCaracteres) and ( $maximoCaracteres < $this->minimoCaracteres)) {
                throw new FormException("maximoCaracteres nao pode ser menor que minimoCaracteres.");
            }

            $this->maximoCaracteres = $maximoCaracteres;
            return $this;
        } else {
            throw new FormException("maximoCaracteres: Valor nao numerico.");
        }
    }

    /**
     * FormInputEmail::getMaximoCaracteres()
     * 
     * @return
     */
    public function getMaximoCaracteres()
    {
        return $this->maximoCaracteres;
    }

    /**
     * FormInputEmail::setMinimoCaracteres()
     * 
     * @return
     */
    public function setMinimoCaracteres($minimoCaracteres)
    {
        if (is_numeric($minimoCaracteres)) {

            if (isset($this->maximoCaracteres) and ( $minimoCaracteres > $this->maximoCaracteres)) {
                throw new FormException("minimoCaracteres nao pode ser maior que maximoCaracteres.");
            }

            $this->minimoCaracteres = $minimoCaracteres;
            return $this;
        } else {
            throw new FormException("minimoCaracteres: Valor nao numerico.");
        }
    }

    /**
     * FormInputEmail::getMinimoCaracteres()
     * 
     * @return
     */
    public function getMinimoCaracteres()
    {
        return $this->minimoCaracteres;
    }

    /**
     * FormInputEmail::setObrigarorio()
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
     * FormInputEmail::getObrigatorio()
     * 
     * @return
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    /**
     * FormInputEmail::setPlaceHolder()
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
     * FormInputEmail::getPlaceHolder()
     * 
     * @return
     */
    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }
    
    /**
     * FormInputEmail::getAliasSql()
     * 
     * @return string
     */
    public function getAliasSql(){
        return $this->aliasSql;
    }

    /**
     * FormInputEmail::setAliasSql()
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
     * FormInputEmail::setId()
     * 
     * @return
     */
    public function setId($id)
    {
        parent::setId($id);
        return $this;
    }

    /**
     * FormInputEmail::setNome()
     * 
     * @return
     */
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }

    /**
     * FormInputEmail::setIdentifica()
     * 
     * @return
     */
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }

    /**
     * FormInputEmail::setValor()
     * 
     * @return
     */
    public function setValor($valor)
    {
        parent::setValor($valor);
        return $this;
    }

    /**
     * FormInputEmail::setValorPadrao()
     * 
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }

    /**
     * FormInputEmail::setDisabled()
     * 
     * @return
     */
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }

    /**
     * FormInputEmail::setComplemento()
     * 
     * @return
     */
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    /**
     * FormInputEmail::setAtributos()
     * 
     * @return
     */
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }

    /**
     * FormInputEmail::setClassCss()
     * 
     * @return
     */
    public function setClassCss($classCss)
    {
        parent::setClassCss($classCss);
        return $this;
    }

}
