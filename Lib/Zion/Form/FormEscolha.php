<?php

namespace Zion\Form;

class FormEscolha extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao;
    private $multiplo;
    private $expandido;
    private $ordena;
    private $array;
    private $inicio;
    private $tabela;
    private $campoCod;
    private $campoDesc;
    private $where;
    private $sqlCompleto;
    private $idConexao;

    public function __construct($acao)
    {
        $this->tipoBase = 'escolha';
        $this->acao = $acao;
        $this->expandido = false;
        $this->multiplo = false;
        $this->ordena = 'ASC';
        $this->inicio = 'Selecione...';
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function getMiltiplo()
    {
        return $this->multiplo;
    }

    public function setMultiplo($multiplo)
    {
        $this->multiplo = $multiplo;
        return $this;
    }
    
    public function getExpandido()
    {
        return $this->expandido;
    }

    public function setExpandido($expandido)
    {
        $this->expandido = $expandido;
        return $this;
    }
    
    public function getOrdena()
    {
        return $this->ordena;
    }

    public function setOrdena($ordena)
    {
        $this->ordena = $ordena;
        return $this;
    }

    public function getArray()
    {
        return $this->array;
    }

    public function setArray($array)
    {
        $this->array = $array;
        return $this;
    }

    public function getInicio()
    {
        return $this->inicio;
    }

    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
        return $this;
    }

    public function getTabela()
    {
        return $this->tabela;
    }

    public function setTabela($tabela)
    {
        $this->tabela = $tabela;
        return $this;
    }

    public function getCampoCod()
    {
        return $this->campoCod;
    }

    public function setCampoCod($campoCod)
    {
        $this->campoCod = $campoCod;
        return $this;
    }

    public function getCampoDesc()
    {
        return $this->campoDesc;
    }

    public function setCampoDesc($campoDesc)
    {
        $this->campoDesc = $campoDesc;
        return $this;
    }

    public function getWhere()
    {
        return $this->where;
    }

    public function setWhere($where)
    {
        $this->where = $where;
        return $this;
    }

    public function getSqlCompleto()
    {
        return $this->sqlCompleto;
    }

    public function setSqlCompleto($sqlCompleto)
    {
        $this->sqlCompleto = $sqlCompleto;
        return $this;
    }
    
    public function getIdConexao()
    {
        return $this->idConexao;
    }

    public function setIdConexao($idConexao)
    {
        $this->idConexao = $idConexao;
        return $this;
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

}
