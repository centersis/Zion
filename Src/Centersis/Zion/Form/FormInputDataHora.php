<?php

namespace Centersis\Zion\Form;
use Zion\Exception\ErrorException;
use Zion\Validacao\Data;

class FormInputDataHora extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao;
    private $obrigatorio;
    private $dataHoraMinima;
    private $dataHoraMaxima;
    private $placeHolder;
    private $aliasSql;
    private $filtroPadrao;

    private $data;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'dataHora';
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

    public function setDataHoraMinima($dataHoraMinima)
    {
        if($this->data->validaData($dataHoraMinima) === true){

            if(isset($this->dataHoraMaxima) and $this->data->verificaDiferencaDataHora($this->dataHoraMaxima, $dataHoraMinima) == 1) {
                throw new ErrorException("dataHoraMinima não pode ser maior que dataHoraMaxima.");
            }

            $this->dataHoraMinima = $dataHoraMinima;
            return $this;

        } else {
            throw new ErrorException("dataHoraMinima: O valor informado não é uma data/hora válida.");
        }
    }

    public function getDataHoraMinima()
    {
        return $this->dataHoraMinima;
    }

    public function setDataHoraMaxima($dataHoraMaxima)
    {
        if($this->data->validaData($dataHoraMaxima)){

            if(isset($this->dataHoraMinima) and $this->data->verificaDiferencaDataHora($this->dataHoraMinima, $dataHoraMaxima) == -1) {
                throw new ErrorException("dataHoraMinima não pode ser maior que dataHoraMaxima.");
            }

            $this->dataHoraMaxima = $dataHoraMaxima;
            return $this;

        } else {
            throw new ErrorException("dataHoraMaxima: O valor informado não é uma data/hora válida.");
        }
    }

    public function getDataHoraMaxima()
    {
        return $this->dataHoraMaxima;
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

    public function getAliasSql(){
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
        $valor = $this->data->converteDataHora(parent::getValor());

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
