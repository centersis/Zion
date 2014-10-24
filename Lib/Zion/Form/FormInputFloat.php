<?php

/**
 * \Zion\Form\FormInputFloat()
 * 
 * @author The Sappiens Team
 * @copyright 2014
 * @version 2014
 * @access public
 */
 
namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;
use \Zion\Validacao\Numero as Numero;

class FormInputFloat extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao; 
    private $largura;
    private $valorMaximo;
    private $valorMinimo;
    private $prefixo;
    private $obrigatorio;
    private $placeHolder;
    private $aliasSql;

    private $numero;
    
    /**
     * FormInputFloat::__construct()
     * 
     * @return
     */
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'float';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
        
        $this->numero = new Numero();
    }
    
    /**
     * FormInputFloat::getTipoBase()
     * 
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }
    
    /**
     * FormInputFloat::getAcao()
     * 
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }
    
    /**
     * FormInputFloat::setLargura()
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
     * FormInputFloat::getLargura()
     * 
     * @return
     */
    public function getLargura()
    {
        return $this->largura;
    }
    
    /**
     * FormInputFloat::setValorMaximo()
     * 
     * @return
     */
    public function setValorMaximo($valorMaximo)
    {
        if($this->numero->isFloat($valorMaximo) === true){

            if(isset($this->valorMinimo) and ($valorMaximo < $this->valorMinimo)) {
                throw new FormException("valorMaximo nao pode ser menor que valorMinimo.");
            }

            $this->valorMaximo = $valorMaximo;
            return $this;
        } else {
            throw new FormException("valorMaximo: O valor informado nao e float.");
        }
    }
    
    /**
     * FormInputFloat::getValorMaximo()
     * 
     * @return
     */
    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }
    
    /**
     * FormInputFloat::setValorMinimo()
     * 
     * @return
     */
    public function setValorMinimo($valorMinimo)
    {
        if($this->numero->isFloat($valorMinimo) === true){

            if(isset($this->valorMaximo) and ($valorMinimo > $this->valorMaximo)) {
                throw new FormException("valorMinimo nao pode ser maior que valorMaximo.");
            }

            $this->valorMinimo = $valorMinimo;
            return $this;
        } else {
            throw new FormException("valorMinimo: O valor informado nao e float.");
        }
    }
    
    /**
     * FormInputFloat::getValorMinimo()
     * 
     * @return
     */
    public function getValorMinimo()
    {
        return $this->valorMinimo;
    }
    
    /**
     * FormInputFloat::setPrefixo()
     * 
     * @return
     */
    public function setPrefixo($prefixo)
    {
        if(!is_null($prefixo)){
            $this->prefixo = $prefixo;
            return $this;
        } else {
            throw new FormException("prefixo: Nenhum valor informado.");
        }
    }
    
    /**
     * FormInputFloat::getPrefixo()
     * 
     * @return
     */
    public function getPrefixo()
    {
        return $this->prefixo;
    }
    
    /**
     * FormInputFloat::setObrigarorio()
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
     * FormInputFloat::getObrigatorio()
     * 
     * @return
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }
    
    /**
     * FormInputFloat::setPlaceHolder()
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
     * FormInputFloat::getPlaceHolder()
     * 
     * @return
     */
    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }

    /**
     * FormInputFloat::setAliasSql()
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
     * FormInputFloat::getAliasSql()
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
     * FormInputFloat::setId()
     * 
     * @return
     */
    public function setId($id)
    {
        parent::setId($id);        
        return $this;
    }
    
    /**
     * FormInputFloat::setNome()
     * 
     * @return
     */
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }
    
    /**
     * FormInputFloat::setIdentifica()
     * 
     * @return
     */
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }
    
    /**
     * FormInputFloat::setValor()
     * 
     * @return
     */
    public function setValor($valor)
    {              
        parent::setValor($valor);
        return $this;
    }
    
    /**
     * FormInputFloat::setValorPadrao()
     * 
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }
    
    /**
     * FormInputFloat::setDisabled()
     * 
     * @return
     */
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }
    
    /**
     * FormInputFloat::setComplemento()
     * 
     * @return
     */
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    /**
     * FormInputFloat::setAtributos()
     * 
     * @return
     */
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }
    
    /**
     * FormInputFloat::setClassCss()
     * 
     * @return
     */
    public function setClassCss($classCss)
    {
        parent::setClassCss($classCss);
        return $this;
    }
}