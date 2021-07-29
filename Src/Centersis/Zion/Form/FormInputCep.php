<?php

namespace Centersis\Zion\Form;

use Centersis\Zion\Exception\ErrorException;

class FormInputCep extends FormBasico
{

    private $tipoBase;
    private $acao;
    private $obrigatorio;
    private $maximoCaracteres;
    private $minimoCaracteres;
    private $placeHolder;
    private $aliasSql;
    private $filtroPadrao;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'cep';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigatorio($obrigatorio);
        $this->setMaximoCaracteres(10);
        $this->filtroPadrao = '=';
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function setMaximoCaracteres($maximoCaracteres)
    {
        if (is_numeric($maximoCaracteres)) {

            if (isset($this->minimoCaracteres) and ( $maximoCaracteres < $this->minimoCaracteres)) {
                throw new ErrorException("maximoCaracteres não pode ser menor que minimoCaracteres.");
            }

            $this->maximoCaracteres = $maximoCaracteres;
        } else {
            throw new ErrorException("maximoCaracteres: Valor não numerico.");
        }

        return $this;
    }

    public function getMaximoCaracteres()
    {
        return $this->maximoCaracteres;
    }

    public function setMinimoCaracteres($minimoCaracteres)
    {
        if (\is_numeric($minimoCaracteres)) {

            if (isset($this->maximoCaracteres) and ( $minimoCaracteres > $this->maximoCaracteres)) {
                throw new ErrorException("minimoCaracteres não pode ser maior que maximoCaracteres.");
            }

            $this->minimoCaracteres = $minimoCaracteres;
        } else {
            throw new ErrorException("minimoCaracteres: Valor não numerico.");
        }

        return $this;
    }

    public function getMinimoCaracteres()
    {
        return $this->minimoCaracteres;
    }

    public function setObrigatorio($obrigatorio)
    {
        if (\is_bool($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
        } else {
            throw new ErrorException("obrigatorio: Valor não booleano");
        }

        return $this;
    }

    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    public function setPlaceHolder($placeHolder)
    {
        if (!empty($placeHolder)) {
            $this->placeHolder = $placeHolder;
        } else {
            throw new ErrorException("placeHolder: Nenhum valor informado");
        }

        return $this;
    }

    public function getAliasSql()
    {
        return $this->aliasSql;
    }

    public function setAliasSql($aliasSql)
    {
        if (!\is_null($aliasSql)) {
            $this->aliasSql = $aliasSql;
        } else {
            throw new ErrorException("aliasSql: Nenhum valor informado");
        }

        return $this;
    }

    public function getPlaceHolder()
    {
        return $this->placeHolder;
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

    public function setContainer($container)
    {
        parent::setContainer($container);
        return $this;
    }

    public function setFiltroPadrao($filtroPadrao)
    {
        $this->filtroPadrao = $filtroPadrao;

        return $this;
    }

    public function getFiltroPadrao()
    {
        return $this->filtroPadrao;
    }

}
