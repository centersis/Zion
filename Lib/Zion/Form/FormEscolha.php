<?php

/**
 *
 *    Sappiens Framework
 *    Copyright (C) 2014, BRA Consultoria
 *
 *    Website do autor: www.braconsultoria.com.br/sappiens
 *    Email do autor: sappiens@braconsultoria.com.br
 *
 *    Website do projeto, equipe e documentação: www.sappiens.com.br
 *   
 *    Este programa é software livre; você pode redistribuí-lo e/ou
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *    publicada pela Free Software Foundation, versão 2.
 *
 *    Este programa é distribuído na expectativa de ser útil, mas SEM
 *    QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *    detalhes.
 * 
 *    Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *    junto com este programa; se não, escreva para a Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *    02111-1307, USA.
 *
 *    Cópias da licença disponíveis em /Sappiens/_doc/licenca
 *
 */
/**
 * \Zion\Form\FormEscolha()
 * 
 * @author The Sappiens Team
 * @copyright Sappiens 2014
 * @version 2014
 * @access public
 */

namespace Zion\Form;

use \Zion\Form\Exception\FormException as FormException;

class FormEscolha extends \Zion\Form\FormBasico
{

    private $tipoBase;
    private $acao;
    private $obrigatorio;
    private $selecaoMaxima;
    private $selecaoMinima;
    private $multiplo;
    private $expandido;
    private $chosen;
    private $ordena;
    private $array;
    private $inicio;
    private $tabela;
    private $campoCod;
    private $campoDesc;
    private $orderBy;
    private $sqlCompleto;
    private $idConexao;
    private $aliasSql;
    private $ignoreCod;
    private $callback;

    /**
     * FormEscolha::__construct()
     * 
     * @param mixed $acao
     * @return
     */
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'escolha';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
        $this->expandido = false;
        $this->multiplo = false;
        $this->chosen = false;
        $this->ordena = 'ASC';
        $this->inicio = 'Selecione...';
    }

    /**
     * FormEscolha::getTipoBase()
     * 
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    /**
     * FormEscolha::getAcao()
     * 
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }

    public function setObrigarorio($obrigatorio)
    {
        if (is_bool($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
            return $this;
        } else {
            throw new FormException("obrigatorio: Valor não booleano");
        }
    }

    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    public function setSelecaoMaxima($selecaoMaxima)
    {
        if (is_numeric($selecaoMaxima)) {
            if (isset($this->selecaoMinima) and $selecaoMaxima < $this->selecaoMinima) {
                throw new FormException("selecaoMaxima não pode ser menor que selecao mínima.");
            }
            $this->selecaoMaxima = $selecaoMaxima;
            return $this;
        } else {
            throw new FormException("selecaoMaxima: O valor informado deve ser do tipo numérico.");
        }
    }

    public function getSelecaoMaxima()
    {
        return $this->selecaoMaxima;
    }

    public function setSelecaoMinima($selecaoMinima)
    {
        if (is_numeric($selecaoMinima)) {
            if (isset($this->selecaoMaxima) and $selecaoMinima > $this->selecaoMaxima) {
                throw new FormException("selecaoMinima não pode ser maior que seleção máxima.");
            }
            $this->selecaoMinima = $selecaoMinima;
            return $this;
        } else {
            throw new FormException("selecaoMinima: O valor informado deve ser do tipo numérico.");
        }
    }

    public function getSelecaoMinima()
    {
        return $this->selecaoMinima;
    }

    /**
     * FormEscolha::getMultiplo()
     * 
     * @return
     */
    public function getMultiplo()
    {
        return $this->multiplo;
    }

    /**
     * FormEscolha::setMultiplo()
     * 
     * @param mixed $multiplo
     * @return
     */
    public function setMultiplo($multiplo)
    {
        if (!is_null($multiplo)) {
            $this->multiplo = $multiplo;
            return $this;
        } else {
            throw new FormException("multiplo: Valor não booleano");
        }
    }

    /**
     * FormEscolha::getExpandido()
     * 
     * @return
     */
    public function getExpandido()
    {
        return $this->expandido;
    }

    /**
     * FormEscolha::setExpandido()
     * 
     * @param mixed $expandido
     * @return
     */
    public function setExpandido($expandido)
    {
        if (!is_null($expandido)) {
            $this->expandido = $expandido;
            return $this;
        } else {
            throw new FormException("expandido: Valor não booleano");
        }
    }

    protected function setChosen($chosen)
    {
        $this->chosen = $chosen;
    }

    public function getChosen()
    {
        return $this->chosen;
    }

    /**
     * FormEscolha::getOrdena()
     * 
     * @return
     */
    public function getOrdena()
    {
        return $this->ordena;
    }

    /**
     * FormEscolha::setOrdena()
     * 
     * @param mixed $ordena
     * @return
     */
    public function setOrdena($ordena)
    {
        if (!is_null($ordena)) {
            $this->ordena = $ordena;
            return $this;
        } else {
            throw new FormException("ordena: Nenhum Valor foi informado.");
        }
    }

    /**
     * FormEscolha::getArray()
     * 
     * @return
     */
    public function getArray()
    {
        return $this->array;
    }

    /**
     * FormEscolha::setArray()
     * 
     * @param mixed $array
     * @return
     */
    public function setArray($array)
    {
        if (\is_array($array)) {
            $this->array = $array;
            return $this;
        } else {
            throw new FormException("array: O valor informado não é um array.");
        }
    }

    /**
     * FormEscolha::getInicio()
     * 
     * @return
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * FormEscolha::setInicio()
     * 
     * @param mixed $inicio
     * @return
     */
    public function setInicio($inicio)
    {
        if (!is_null($inicio)) {
            $this->inicio = $inicio;
            return $this;
        } else {
            throw new FormException("inicio: Nenhum Valor foi informado.");
        }
    }

    /**
     * FormEscolha::getTabela()
     * 
     * @return
     */
    public function getTabela()
    {
        return $this->tabela;
    }

    /**
     * FormEscolha::setTabela()
     * 
     * @param mixed $tabela
     * @return
     */
    public function setTabela($tabela)
    {
        if (!is_null($tabela)) {
            $this->tabela = $tabela;
            return $this;
        } else {
            throw new FormException("tabela: Nenhum Valor foi informado.");
        }
    }

    /**
     * FormEscolha::getCampoCod()
     * 
     * @return
     */
    public function getCampoCod()
    {
        return $this->campoCod;
    }

    /**
     * FormEscolha::setCampoCod()
     * 
     * @param mixed $campoCod
     * @return
     */
    public function setCampoCod($campoCod)
    {
        if (!\is_null($campoCod)) {
            $this->campoCod = \strtolower($campoCod);
            return $this;
        } else {
            throw new FormException("campoCod: Nenhum Valor foi informado.");
        }
    }

    /**
     * FormEscolha::getCampoDesc()
     * 
     * @return
     */
    public function getCampoDesc()
    {
        return $this->campoDesc;
    }

    /**
     * FormEscolha::setCampoDesc()
     * 
     * @param mixed $campoDesc
     * @return
     */
    public function setCampoDesc($campoDesc)
    {
        if (!is_null($campoDesc)) {
            $this->campoDesc = \strtolower($campoDesc);
            return $this;
        } else {
            throw new FormException("campoDesc: Nenhum Valor foi informado.");
        }
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderBy($orderBy)
    {
        if (!empty($orderBy)) {
            if (\is_array($orderBy)) {
                $this->orderBy[] = $orderBy;
                return $this;
            } else {
                throw new FormException("setOrderBy: Se informado o valor deve ser um array.");
            }
        }
    }

    /**
     * FormEscolha::getSqlCompleto()
     * 
     * @return
     */
    public function getSqlCompleto()
    {
        return $this->sqlCompleto;
    }

    /**
     * FormEscolha::setSqlCompleto()
     * 
     * @param mixed $sqlCompleto
     * @return
     */
    public function setSqlCompleto($sqlCompleto)
    {
        if (!is_null($sqlCompleto)) {
            $this->sqlCompleto = $sqlCompleto;
            return $this;
        } else {
            throw new FormException("sqlCompleto: Nenhum Valor foi informado.");
        }
    }

    /**
     * FormEscolha::getIdConexao()
     * 
     * @return
     */
    public function getIdConexao()
    {
        return $this->idConexao;
    }

    /**
     * FormEscolha::setIdConexao()
     * 
     * @param mixed $idConexao
     * @return
     */
    public function setIdConexao($idConexao)
    {
        if (!is_null($idConexao)) {
            $this->idConexao = $idConexao;
            return $this;
        } else {
            throw new FormException("idConexao: Nenhum Valor foi informado.");
        }
    }

    /**
     * FormEscolha::getAliasSql()
     * 
     * @return string
     */
    public function getAliasSql()
    {
        return $this->aliasSql;
    }

    /**
     * FormEscolha::setAliasSql()
     * 
     * @param string $aliasSql
     *
     */
    public function setAliasSql($aliasSql)
    {
        if (!is_null($aliasSql)) {
            $this->aliasSql = $aliasSql;
            return $this;
        } else {
            throw new FormException("aliasSql: Nenhum valor informado");
        }
    }

    public function setIgnoreCod($ignoreCod)
    {
        if (is_array($ignoreCod)) {
            $this->ignoreCod = $ignoreCod;
            return $this;
        } else {
            throw new FormException("ignoreCod: O valor informado não é um array.");
        }
    }

    public function getIgnoreCod()
    {
        return $this->ignoreCod;
    }
    
    public function setCallback($callback)
    {
        if (\is_string($callback)) {            
            $this->callback = $callback;
            return $this;
        } else {
            throw new FormException("callback: O valor informado não é uma string.");
        }
    }

    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Sobrecarga de Metodos Básicos
     */

    /**
     * FormEscolha::setId()
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
     * FormEscolha::setNome()
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
     * FormEscolha::setIdentifica()
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
     * FormEscolha::setValor()
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
     * FormEscolha::setValorPadrao()
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
     * FormEscolha::setDisabled()
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
     * FormEscolha::setComplemento()
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
     * FormEscolha::setAtributos()
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
     * FormEscolha::setClassCss()
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
