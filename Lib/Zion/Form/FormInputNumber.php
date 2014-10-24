<?php

/**
 * \Zion\Form\FormInputNumber()
 * 
 * @author The Sappiens Team
 * @copyright 2014
 * @version 2014
 * @access public
 */
 
namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;

class FormInputNumber extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao;
    private $largura;
    private $maximoCaracteres;
    private $minimoCaracteres;
    private $valorMaximo;
    private $valorMinimo;
    private $obrigatorio;
    private $placeHolder;
    
    /**
     * FormInputNumber::__construct()
     * 
     * @return
     */
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'number';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
    }
    
    /**
     * FormInputNumber::getTipoBase()
     * 
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }
    
    /**
     * FormInputNumber::getAcao()
     * 
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }
    
    /**
     * FormInputNumber::setLargura()
     * 
     * @return
     */
    public function setLargura($largura)
    {
        if (preg_match('/^[0-9]{1,}[%]{1}$|^[0-9]{1,}[px]{2}$|^[0-9]{1,}$/', $largura)) {
            $this->largura = $largura;
            return $this;
        } else {
            throw new FormException("largura: O valor nao esta nos formatos aceitos: 10%; 10px; ou 10");
        }
    }

    /**
     * FormInputNumber::getLargura()
     * 
     * @return
     */
    public function getLargura()
    {
        return $this->largura;
    }
    
    /**
     * FormInputNumber::setMaximoCaracteres()
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
     * FormInputNumber::getMaximoCaracteres()
     * 
     * @return
     */
    public function getMaximoCaracteres()
    {
        return $this->maximoCaracteres;
    }

    /**
     * FormInputNumber::setMinimoCaracteres()
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
     * FormInputNumber::getMinimoCaracteres()
     * 
     * @return
     */
    public function getMinimoCaracteres()
    {
        return $this->minimoCaracteres;
    }
    
    /**
     * FormInputNumber::setValorMinimo()
     * 
     * @return
     */
    public function setValorMinimo($valorMinimo)
    {
        if(is_numeric($valorMinimo)){

            if(isset($this->valorMaximo) and ($valorMinimo > $this->valorMaximo)) {
                throw new FormException("valorMinimo nao pode ser maior que valorMaximo.");
            }

            $this->valorMinimo = $valorMinimo;
            return $this;
        } else {
            throw new FormException("valorMinimo: Valor nao numerico");
        }
    }
    
    /**
     * FormInputNumber::getValorMinimo()
     * 
     * @return
     */
    public function getValorMinimo()
    {
        return $this->valorMinimo;
    }
    
    /**
     * FormInputNumber::setValorMaximo()
     * 
     * @return
     */
    public function setValorMaximo($valorMaximo)
    {
        if(is_numeric($valorMaximo)){

            if(isset($this->valorMinimo) and ($valorMaximo < $this->valorMinimo)) {
                throw new FormException("valorMaximo nao pode ser menor que valorMinimo.");
            }

            $this->valorMaximo = $valorMaximo;
            return $this;
        } else {
            throw new FormException("valorMaximo: Valor nao numerico");
        }
    }
    
    /**
     * FormInputNumber::getValorMaximo()
     * 
     * @return
     */
    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }
    
    /**
     * FormInputNumber::setObrigarorio()
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
     * FormInputNumber::getObrigatorio()
     * 
     * @return
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }
    
    /**
     * FormInputNumber::setPlaceHolder()
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
     * FormInputNumber::getPlaceHolder()
     * 
     * @return
     */
    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }
    
    /**
     * Sobrecarga de Metodos Básicos
     */    
    /**
     * FormInputNumber::setId()
     * 
     * @return
     */
    public function setId($id)
    {
        parent::setId($id);        
        return $this;
    }
    
    /**
     * FormInputNumber::setNome()
     * 
     * @return
     */
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }
    
    /**
     * FormInputNumber::setIdentifica()
     * 
     * @return
     */
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }
    
    /**
     * FormInputNumber::setValor()
     * 
     * @return
     */
    public function setValor($valor)
    {              
        parent::setValor($valor);
        return $this;
    }
    
    /**
     * FormInputNumber::setValorPadrao()
     * 
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }
    
    /**
     * FormInputNumber::setDisabled()
     * 
     * @return
     */
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }
    
    /**
     * FormInputNumber::setComplemento()
     * 
     * @return
     */
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    /**
     * FormInputNumber::setAtributos()
     * 
     * @return
     */
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }
    
    /**
     * FormInputNumber::setClassCss()
     * 
     * @return
     */
    public function setClassCss($classCss)
    {
        parent::setClassCss($classCss);
        return $this;
    }
}