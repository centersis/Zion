<?php

namespace Zion\Form;

use Zion\Exception\RuntimeException;

abstract class FormBasico
{

    private $id;
    private $nome;
    private $identifica;
    private $valor;
    private $valorPadrao;
    private $disabled;
    private $complemento;
    private $atributos;
    private $classCss = [];
    private $container;
    private $nomeForm;

    public function setId($id)
    {
        if (!empty($id)) {
            $this->id = \str_replace('[]', '', $id);
            return $this;
        } else {
            throw new RuntimeException("id: Nenhum valor informado.");
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setNome($nome)
    {
        if (!empty($nome)) {
            $this->nome = $nome;
            return $this;
        } else {
            throw new RuntimeException("nome: Nenhum valor informado.");
        }
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setIdentifica($identifica)
    {
        if (!empty($identifica)) {
            $this->identifica = $identifica;
            return $this;
        } else {
            throw new RuntimeException("identifica: Nenhum valor informado.");
        }
    }

    public function getIdentifica()
    {
        return $this->identifica;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValorPadrao($valorPadrao)
    {
        if ($valorPadrao === null or $valorPadrao === '') {
            $this->valorPadrao = null;
        } else {
            $this->valorPadrao = $valorPadrao;
        }

        return $this;
    }

    public function getValorPadrao()
    {
        return $this->valorPadrao;
    }

    public function setDisabled($disabled)
    {
        if ($disabled === true) {
            $this->disabled = $disabled;
        } else {
            $this->disabled = false;
        }
    }

    public function getDisabled()
    {
        return $this->disabled;
    }

    public function setComplemento($complemento)
    {
        if (!empty($complemento)) {
            $this->complemento = $complemento;
            return $this;
        } else {
            throw new RuntimeException("complemento: Nenhum valor informado.");
        }
    }

    public function getComplemento()
    {
        return $this->complemento;
    }

    public function setAtributos($atributos)
    {
        if (!empty($atributos)) {
            $this->atributos = $atributos;
            return $this;
        } else {
            throw new RuntimeException("atributos: Nenhum valor informado.");
        }
    }

    public function getAtributos()
    {
        return $this->atributos;
    }

    public function setClassCss($classCss)
    {
        $class = \trim($classCss);

        if (!empty($class)) {
            $this->classCss[$class] = $class;
            return $this;
        } else {
            throw new RuntimeException("classCss: Nenhum valor informado.");
        }
    }

    public function getClassCss()
    {
        return \implode(' ', $this->classCss);
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    public function getNomeForm()
    {
        return $this->nomeForm;
    }

    public function setNomeForm($nomeForm)
    {
        $this->nomeForm = $nomeForm;
        return $this;
    }

}
