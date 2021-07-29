<?php

namespace Zion\Pixel\Form;

use Zion\Form\FormInputHidden as FormInputHiddenZion;
use Zion\Pixel\Form\FormSetPixel;

class FormInputHidden extends FormInputHiddenZion
{

    private $formSetPixel;

    public function __construct($acao, $nome)
    {
        parent::__construct($acao, $nome);

        $this->formSetPixel = new FormSetPixel();
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

    public function setAliasSql($aliasSql)
    {
        parent::setAliasSql($aliasSql);
        return $this;
    }

}
