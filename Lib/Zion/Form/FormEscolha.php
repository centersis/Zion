<?php

namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;

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
        if(!is_null($multiplo)){
            $this->multiplo = $multiplo;
            return $this;
        } else {
            throw new FormException("multiplo: Valor nao booleano");
        }
    }
    
    public function getExpandido()
    {
        return $this->expandido;
    }

    public function setExpandido($expandido)
    {
        if(!is_null($expandido)){
            $this->expandido = $expandido;
            return $this;
        } else {
            throw new FormException("expandido: Valor nao booleano");
        }
    }
    
    public function getOrdena()
    {
        return $this->ordena;
    }

    public function setOrdena($ordena)
    {
        if(!is_null($ordena)){
            $this->ordena = $ordena;
            return $this;
        } else {
            throw new FormException("ordena: Nenhum Valor foi informado.");
        }
    }

    public function getArray()
    {
        return $this->array;
    }

    public function setArray($array)
    {
        if(is_array($array)){
            $this->array = $array;
            return $this;
        } else {
            throw new FormException("array: O valor informado nao e um array.");
        }
    }

    public function getInicio()
    {
        return $this->inicio;
    }

    public function setInicio($inicio)
    {
        if(!is_null($inicio)){
            $this->inicio = $inicio;
            return $this;
        } else {
            throw new FormException("inicio: Nenhum Valor foi informado.");
        }
    }

    public function getTabela()
    {
        return $this->tabela;
    }

    public function setTabela($tabela)
    {
        if(!is_null($tabela)){
            $this->tabela = $tabela;
            return $this;
        } else {
            throw new FormException("tabela: Nenhum Valor foi informado.");
        }
    }

    public function getCampoCod()
    {
        return $this->campoCod;
    }

    public function setCampoCod($campoCod)
    {
        if(!is_null($campoCod)){
            $this->campoCod = $campoCod;
            return $this;
        } else {
            throw new FormException("campoCod: Nenhum Valor foi informado.");
        }
    }

    public function getCampoDesc()
    {
        return $this->campoDesc;
    }

    public function setCampoDesc($campoDesc)
    {
        if(!is_null($campoDesc)){
            $this->campoDesc = $campoDesc;
            return $this;
        } else {
            throw new FormException("campoDesc: Nenhum Valor foi informado.");
        }
    }

    public function getWhere()
    {
        return $this->where;
    }

    public function setWhere($where)
    {
        if(!is_null($where)){
            $this->where = $where;
            return $this;
        } else {
            throw new FormException("where: Nenhum Valor foi informado.");
        }
    }

    public function getSqlCompleto()
    {
        return $this->sqlCompleto;
    }

    public function setSqlCompleto($sqlCompleto)
    {
        if(!is_null($sqlCompleto)){
            $this->sqlCompleto = $sqlCompleto;
            return $this;
        } else {
            throw new FormException("sqlCompleto: Nenhum Valor foi informado.");
        }
    }
    
    public function getIdConexao()
    {
        return $this->idConexao;
    }

    public function setIdConexao($idConexao)
    {
        if(!is_null($idConexao)){
            $this->idConexao = $idConexao;
            return $this;
        } else {
            throw new FormException("idConexao: Nenhum Valor foi informado.");
        }
    }

    /**
     * Sobrecarga de Metodos Básicos
     */
    public function setId($id)
    {
        if(!empty($id)){
            parent::setId($id);        
            return $this;
        } else {
            throw new FormException("id: Nenhum valor informado.");
        }
    }
    
    public function setNome($nome)
    {
        if(!empty($nome)){
             parent::setNome($nome);
            return $this;
        } else {
            throw new FormException("nome: Nenhum valor informado.");
        }
    }
    
    public function setIdentifica($identifica)
    {
        if(!empty($identifica)){
             parent::setIdentifica($identifica);
            return $this;
        } else {
            throw new FormException("identifica: Nenhum valor informado.");
        }
    }
    
    public function setValor($valor)
    {              
        if(!empty($valor)){
             parent::setValor($valor);
            return $this;
        } else {
            throw new FormException("valor: Nenhum valor informado.");
        }
    }
    
    public function setValorPadrao($valorPadrao)
    {
        if(!empty($valorPadrao)){
             parent::setValorPadrao($valorPadrao);
            return $this;
        } else {
            throw new FormException("valorPadrao: Nenhum valor informado.");
        }
    }
    
    public function setDisabled($disabled)
    {
        if(!empty($disabled)){
             parent::setDisabled($disabled);
            return $this;
        } else {
            throw new FormException("disabled: Nenhum valor informado.");
        }
    }
    
    public function setComplemento($complemento)
    {
        if(!empty($complemento)){
             parent::setComplemento($complemento);
            return $this;
        } else {
            throw new FormException("complemento: Nenhum valor informado.");
        }
    }

    public function setAtributos($atributos)
    {
        if(!empty($atributos)){
             parent::setAtributos($atributos);
            return $this;
        } else {
            throw new FormException("atributos: Nenhum valor informado.");
        }
    }
    
    public function setClassCss($classCss)
    {
        if(!empty($classCss)){
             parent::setClassCss($classCss);
            return $this;
        } else {
            throw new FormException("classCss: Nenhum valor informado.");
        }
    }

}
