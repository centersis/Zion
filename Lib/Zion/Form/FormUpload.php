<?php

namespace Zion\Form;

use \Zion\Form\Exception\FormException as FormException;

class FormUpload extends FormBasico
{

    private $tipoBase;
    private $acao;
    private $multiple;
    private $form;

    /**
     * FormInputTexto::__construct()
     * 
     * @param mixed $acao
     * @param mixed $nome
     * @param mixed $identifica
     * @param mixed $obrigatorio
     * @return
     */
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'upload';        
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
    }

    /**
     * FormInputTexto::getTipoBase()
     * 
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    /**
     * FormInputTexto::getAcao()
     * 
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }

    public function getMultiple()
    {
        return $this->multiple;
    }
    
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;
        return $this;
    }
    
    public function getForm()
    {
        return $this->form;
    }

    public function setForm($form)
    {
        if (!is_null($form)) {
            $this->form = $form;
            return $this;
        } else {
            throw new FormException("form: Nenhum valor informado");
        }
    }
    
    /**
     * FormInputTexto::setObrigarorio()
     * 
     * @param mixed $obrigatorio
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
     * FormInputTexto::getObrigatorio()
     * 
     * @return
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    /**
     * Sobrecarga de Metodos Básicos
     */

    /**
     * FormInputTexto::setId()
     * 
     * @param mixed $id
     * @return
     */
    public function setId($id)
    {
        parent::setId($id);
        return $this;
    }

    /**
     * FormInputTexto::setNome()
     * 
     * @param mixed $nome
     * @return
     */
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }

    /**
     * FormInputTexto::setIdentifica()
     * 
     * @param mixed $identifica
     * @return
     */
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }

    /**
     * FormInputTexto::setValor()
     * 
     * @param mixed $valor
     * @return
     */
    public function setValor($valor)
    {
        parent::setValor($valor);
        return $this;
    }

    /**
     * FormInputTexto::setValorPadrao()
     * 
     * @param mixed $valorPadrao
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }

    /**
     * FormInputTexto::setDisabled()
     * 
     * @param mixed $disabled
     * @return
     */
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }

    /**
     * FormInputTexto::setComplemento()
     * 
     * @param mixed $complemento
     * @return
     */
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    /**
     * FormInputTexto::setAtributos()
     * 
     * @param mixed $atributos
     * @return
     */
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }

    /**
     * FormInputTexto::setClassCss()
     * 
     * @param mixed $classCss
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
