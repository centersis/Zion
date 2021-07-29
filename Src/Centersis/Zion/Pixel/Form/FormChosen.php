<?php

namespace Centersis\Zion\Pixel\Form;

use Centersis\Zion\Form\FormEscolha as FormEscolhaZion;
use Centersis\Zion\Pixel\Form\FormSetPixel;

class FormChosen extends FormEscolhaZion
{

    private $toolTipMsg;
    private $emColunaDeTamanho;
    private $offsetColuna;
    private $processarJS;
    private $complementoExterno;
    private $tipoFiltro;
    private $campoDependencia;
    private $metodoDependencia;
    private $classeDependencia;
    private $parametros;
    private $hashAjuda;
    private $formSetPixel;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        parent::__construct($acao, $nome, $identifica, $obrigatorio);
        parent::setChosen(true);
        $this->setMultiplo(false);
        $this->tipoFiltro = 'ValorFixo';
        $this->formSetPixel = new FormSetPixel();
    }

    public function setObrigatorio($obrigatorio)
    {
        parent::setObrigatorio($obrigatorio);
        return $this;
    }

    public function setSelecaoMaxima($selecaoMaxima)
    {
        parent::setSelecaoMaxima($selecaoMaxima);
        return $this;
    }

    public function setSelecaoMinima($selecaoMinima)
    {
        parent::setSelecaoMinima($selecaoMinima);
        return $this;
    }

    public function setMultiplo($multiplo)
    {
        parent::setMultiplo($multiplo);
        return $this;
    }

    public function setOrdena($ordena)
    {
        parent::setOrdena($ordena);
        return $this;
    }

    public function setArray($array)
    {
        parent::setArray($array);
        return $this;
    }

    public function setInicio($inicio)
    {
        parent::setInicio($inicio);
        return $this;
    }

    public function setTabela($tabela)
    {
        parent::setTabela($tabela);
        return $this;
    }

    public function setCampoCod($campoCod)
    {
        parent::setCampoCod($campoCod);
        return $this;
    }

    public function setCampoDesc($campoDesc)
    {
        parent::setCampoDesc($campoDesc);
        return $this;
    }

    public function setInstrucoes(array $instrucoes)
    {
        parent::setInstrucoes($instrucoes);
        return $this;
    }

    public function setOrderBy($orderBy)
    {
        parent::setOrderBy($orderBy);
        return $this;
    }

    public function setSqlCompleto($sqlCompleto)
    {
        parent::setSqlCompleto($sqlCompleto);
        return $this;
    }

    public function setIdConexao($idConexao)
    {
        parent::setIdConexao($idConexao);
        return $this;
    }

    public function setIgnoreCod($ignoreCod)
    {
        parent::setIgnoreCod($ignoreCod);
        return $this;
    }

    public function setCallback($callback)
    {
        parent::setCallback($callback);
        return $this;
    }

    public function setToolTipMsg($toolTipMsg)
    {
        $this->toolTipMsg = $this->formSetPixel->setToolTipMsg($toolTipMsg);
        return $this;
    }

    public function getToolTipMsg()
    {
        return $this->toolTipMsg;
    }

    public function setEmColunaDeTamanho($emColunaDeTamanho)
    {
        $this->emColunaDeTamanho = $this->formSetPixel->setEmColunaDeTamanho($emColunaDeTamanho);
        return $this;
    }

    public function getemColunaDeTamanho()
    {
        return $this->emColunaDeTamanho ? $this->emColunaDeTamanho : 12;
    }

    public function setOffsetColuna($offsetColuna)
    {
        $this->offsetColuna = $this->formSetPixel->setOffsetColuna($offsetColuna);
        return $this;
    }

    public function getOffsetColuna()
    {
        return $this->offsetColuna ? $this->offsetColuna : 3;
    }

    public function setProcessarJS($processarJS)
    {
        $this->processarJS = $this->formSetPixel->setProcessarJS($processarJS);
        return $this;
    }

    public function getProcessarJS()
    {
        return $this->processarJS;
    }

    public function setComplementoExterno($complementoExterno)
    {
        $this->complementoExterno = $this->formSetPixel->setComplementoExterno($complementoExterno);
        return $this;
    }

    public function getComplementoExterno()
    {
        return $this->complementoExterno;
    }

    public function setTipoFiltro($tipoFiltro)
    {
        $this->tipoFiltro = $this->formSetPixel->setTipoFiltro($tipoFiltro);
        return $this;
    }

    public function getTipoFiltro()
    {
        return $this->tipoFiltro;
    }

    public function setHashAjuda($hashAjuda)
    {
        $this->hashAjuda = $this->formSetPixel->setHashAjuda($hashAjuda);
        return $this;
    }

    public function getHashAjuda()
    {
        return $this->hashAjuda;
    }

    public function setFiltroPadrao($filtroPadrao)
    {
        parent::setFiltroPadrao($filtroPadrao);
        return $this;
    }

    public function setAliasSql($aliasSql)
    {
        parent::setAliasSql($aliasSql);
        return $this;
    }

    public function setDependencia($campoDependencia, $metodoDependencia, $classeDependencia, $parametros = [])
    {
        $this->campoDependencia = $campoDependencia;
        $this->metodoDependencia = $metodoDependencia;
        $this->classeDependencia = \str_replace('\\', '/', $classeDependencia);
        $this->parametros = $parametros;

        return $this;
    }

    public function getCampoDependencia()
    {
        return $this->campoDependencia;
    }

    public function getMetodoDependencia()
    {
        return $this->metodoDependencia;
    }

    public function getClasseDependencia()
    {
        return $this->classeDependencia;
    }

    public function getParametros()
    {
        return $this->parametros;
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

}
