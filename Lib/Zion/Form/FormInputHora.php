<?php
namespace Zion\Form;

use Zion\Exception\RuntimeException;
use Zion\Form\FormBasico;
use Zion\Validacao\Data;

class FormInputHora extends FormBasico implements FilterableInput
{
    private $tipoBase;
    private $acao;
    private $obrigatorio;
    private $horaMinima;
    private $horaMaxima;
    private $placeHolder;
    private $mostrarSegundos;
    private $aliasSql;
    private $categoriaFiltro;
    private $filtroPadrao;

    private $hora;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'hora';
        $this->acao = $acao;
        $this->mostrarSegundos = false;

        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigatorio($obrigatorio);
        $this->filtroPadrao = '=';
        $this->categoriaFiltro = FilterableInput::GREATER_THAN;

        $this->hora = Data::instancia();
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function setHoraMinima($horaMinima)
    {
        if($this->hora->validaHora($horaMinima) === true){

            if(isset($this->horaMaxima) and $this->hora->verificaDiferencaDataHora($this->horaMaxima, $horaMinima) == 1) {
                throw new RuntimeException("horaMinima não pode ser maior que horaMaxima.");
            }

            $this->horaMinima = $horaMinima;
            return $this;

        } else {
            throw new RuntimeException("horaMinima: O valor informado não é uma hora válida.");
        }
    }

    public function getHoraMinima()
    {
        return $this->horaMinima;
    }

    public function setHoraMaxima($horaMaxima)
    {
        if($this->hora->validaHora($horaMaxima)){

            if(isset($this->horaMinima) and $this->hora->verificaDiferencaDataHora($this->horaMinima, $horaMaxima) == -1) {
                throw new RuntimeException("horaMinima não pode ser maior que horaMaxima.");
            }

            $this->horaMaxima = $horaMaxima;
            return $this;

        } else {
            throw new RuntimeException("horaMaxima: O valor informado não é uma hora válida.");
        }
    }

    public function getHoraMaxima()
    {
        return $this->horaMaxima;
    }

    public function setPlaceHolder($placeHolder)
    {
        if (!empty($placeHolder)) {
            $this->placeHolder = $placeHolder;
            return $this;
        } else {
            throw new RuntimeException("placeHolder: Nenhum valor informado");
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
            throw new RuntimeException("obrigatorio: Valor não booleano");
        }
    }

    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    public function setMostrarSegundos($mostrarSegundos)
    {
        if (\is_bool($mostrarSegundos)) {
            $this->mostrarSegundos = $mostrarSegundos;
            return $this;
        } else {
            throw new RuntimeException("mostrarSegundos: Valor não booleano");
        }
    }

    public function getMostrarSegundos()
    {
        return $this->mostrarSegundos;
    }

    public function setAliasSql($aliasSql)
    {
        if (!\is_null($aliasSql)) {
            $this->aliasSql = $aliasSql;
            return $this;
        } else {
            throw new RuntimeException("aliasSql: Nenhum valor informado");
        }
    }

    public function getAliasSql(){
        return $this->aliasSql;
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

    public function setCategoriaFiltro($tipo)
    {
        $this->categoriaFiltro = $tipo;

        return $this;
    }

    public function getCategoriaFiltro()
    {
        return $this->categoriaFiltro;
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
