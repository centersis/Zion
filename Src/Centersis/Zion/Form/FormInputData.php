<?php

namespace Centersis\Zion\Form;

use Centersis\Zion\Exception\ErrorException;
use Centersis\Zion\Validacao\Data;

class FormInputData extends \Zion\Form\FormBasico
{

    private $tipoBase;
    private $acao;
    private $obrigatorio;
    private $dataMinima;
    private $dataMaxima;
    private $placeHolder;
    private $aliasSql;
    private $filtroPadrao;
    private $data;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'data';
        $this->acao = $acao;
        $this->mostrarSegundos = false;

        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigatorio($obrigatorio);
        $this->filtroPadrao = '=';

        $this->data = Data::instancia();
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function setDataMinima($dataMinima)
    {
        if ($this->data->validaData($dataMinima) === true) {

            if (isset($this->dataMaxima) and $this->data->verificaDiferencaDataHora($this->dataMaxima, $dataMinima) == 1) {
                throw new ErrorException("dataMinima não pode ser maior que dataMaxima.");
            }

            $this->dataMinima = $dataMinima;
            return $this;
        } else {
            throw new ErrorException("dataMinima: O valor informado não é uma data/hora válida.");
        }
    }

    public function getDataMinima()
    {
        return $this->dataMinima;
    }

    public function setDataMaxima($dataMaxima)
    {
        if ($this->data->validaData($dataMaxima)) {

            if (isset($this->dataMinima) and $this->data->verificaDiferencaDataHora($this->dataMinima, $dataMaxima) == -1) {
                throw new ErrorException("dataMinima não pode ser maior que dataMaxima.");
            }

            $this->dataMaxima = $dataMaxima;
            return $this;
        } else {
            throw new ErrorException("dataMaxima: O valor informado não é uma data/hora válida.");
        }
    }

    public function getDataMaxima()
    {
        return $this->dataMaxima;
    }

    public function setPlaceHolder($placeHolder)
    {
        if (!empty($placeHolder)) {
            $this->placeHolder = $placeHolder;
            return $this;
        } else {
            throw new ErrorException("placeHolder: Nenhum valor informado");
        }
    }

    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }

    public function setObrigatorio($obrigatorio)
    {
        if (\is_bool($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
            return $this;
        } else {
            throw new ErrorException("obrigatorio: Valor não booleano");
        }
    }

    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    public function getAliasSql()
    {
        return $this->aliasSql;
    }

    public function setAliasSql($aliasSql)
    {
        if (!\is_null($aliasSql)) {
            $this->aliasSql = $aliasSql;
            return $this;
        } else {
            throw new ErrorException("aliasSql: Nenhum valor informado");
        }
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

    public function getValor()
    {
        $valor = parent::getValor();

        if (strlen($valor) == 10) {
            $valorConvertido = $this->data->converteData($valor);
            
            if ($valorConvertido != '') {
                $valor = $valorConvertido;
            }
        }

        return $valor;
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
