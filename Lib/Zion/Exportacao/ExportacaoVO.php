<?php
/*

    Sappiens Framework
    Copyright (C) 2014, BRA Consultoria

    Website do autor: www.braconsultoria.com.br/sappiens
    Email do autor: sappiens@braconsultoria.com.br

    Website do projeto, equipe e documentação: www.sappiens.com.br
   
    Este programa é software livre; você pode redistribuí-lo e/ou
    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
    publicada pela Free Software Foundation, versão 2.

    Este programa é distribuído na expectativa de ser útil, mas SEM
    QUALQUER GARANTIA; sem mesmo a garantia implícita de
    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
    detalhes.
 
    Você deve ter recebido uma cópia da Licença Pública Geral GNU
    junto com este programa; se não, escreva para a Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
    02111-1307, USA.

    Cópias da licença disponíveis em /Sappiens/_doc/licenca

*/

/**
 * 
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 5/11/2014
 * @version 1.0
 * @copyright 2014
 * 
 * 
 * 
 */

namespace Zion\Exportacao;

use \Zion\Exportacao\Exception\ExportacaoInvalidArgumentException as ExportacaoException;

class ExportacaoVO extends PDFVO {
    
    private $colunas; //Array - Array Com Conjunto de Atributos e Titulos a Serem Exibidos na Grid 
    private $alinhamento; //Array - Array Com Informações de Alinhamento em Cada Campo
    private $naoOrdenePor; //Array - Array Com Atrubutos que não devem ser ordenados na grid
    private $condicaoResultadoUnico; //String - Recebe uma estring com uma condição PHP para se obter uma particularidade para um unico resultado
    private $condicaoTodosResultados; //String - Recebe uma estring com uma condição PHP para se obter uma particularidade para uma linha de resultados
    private $objConverte; //Objeto - Recebe Um objeto de Converssão para os elementos da grid 	
    private $formatarComo; //Array que recebe o campo e como deve formata-lo
    private $sql;
    private $dadosRelatorio;
    private $tipoOrdenacao;
    private $quemOrdena;

    public function __construct()
    {
        parent::__construct();

        $this->colunas = [];
        $this->alinhamento = [];
        $this->naoOrdenePor = [];
        $this->condicaoResultadoUnico = [];
        $this->condicaoTodosResultados = [];
        $this->objConverte = [];
        $this->formatarComo = [];
        $this->selecao = true;
        $this->selecaoMultipla = false;
    }

    public function setColunas($valor)
    {
        if (!is_array($valor)) {
            throw new \Exception("Grid: Colunas informado inválido!");
        }

        $this->colunas = $valor;
    }

    public function getColunas()
    {
        return $this->colunas;
    }

    public function setCondicaoResultadoUnico($campo, $condicao, $class)
    {
        $this->condicaoResultadoUnico[$campo] = [$condicao, $class];
    }

    public function getCondicaoResultadoUnico()
    {
        return $this->condicaoResultadoUnico;
    }

    public function setCondicaoTodosResultados($condicao, $class)
    {
        $this->condicaoTodosResultados[] = [$condicao, $class];
    }

    public function getCondicaoTodosResultados()
    {
        return $this->condicaoTodosResultados;
    }

    public function setObjConverte($objeto, $metodo, $campo, $parametrosInternos = null, $paremetrosExternos = null, $ordem = 'IE')
    {
        if (!is_object($objeto)) {
            throw new \Exception("Grid: Objeto informado inválido!");
        }

        if (!is_string($metodo)) {
            throw new \Exception("Grid: Metodo informado inválido!");
        }

        if (!is_string($campo)) {
            throw new \Exception("Grid: Campo informado inválido!");
        }

        if (!empty($parametrosInternos)) {
            if (!is_array($parametrosInternos)) {
                throw new \Exception("Grid: ParametrosInternos informado inválido!");
            }
        }

        if (!empty($paremetrosExternos)) {
            if (!is_array($paremetrosExternos)) {
                throw new \Exception("Grid: ParametrosInternos informado inválido!");
            }
        }

        if (!in_array(strtoupper($ordem), ["IE", "EI"])) {
            throw new \Exception("Grid: Ordem informado inválido!");
        }

        $this->objConverte[$campo] = [$objeto, $metodo, $campo, $parametrosInternos, $paremetrosExternos, $ordem];
    }

    public function getObjetoConverte()
    {
        return $this->objConverte;
    }

    public function setNaoOrdenePor($valor)
    {
        if (!is_array($valor)) {
            throw new \Exception("Grid: NaoOrdenePor informado inválido!");
        }

        $this->naoOrdenePor = $valor;
    }

    public function getNaoOrdenePor()
    {
        return $this->naoOrdenePor;
    }

    public function setFormatarComo($Identificacao, $como)
    {
        if (!is_string($Identificacao)) {
            throw new \Exception("Grid: FormatarComo parametro 1 informado inválido!");
        }

        if (!is_string($como)) {
            throw new \Exception("Grid: FormatarComo parametro 2 informado inválido!");
        }

        $this->formatarComo[$Identificacao] = $como;
    }

    public function getFormatarComo()
    {
        return $this->formatarComo;
    }

    public function setDadosRelatorio($dadosRelatorio)
    {
        if(is_array($dadosRelatorio)){
            $this->dadosRelatorio = $dadosRelatorio;
        } else {
            throw new ExportacaoException("dadosRelatorio: O valor informado nao e um array valido.");
        }
    }
    
    public function getDadosRelatorio(){
        return $this->dadosRelatorio;
    }

    public function setSql($valor)
    {
        $this->sql = $valor;
    }

    public function getSql()
    {
        return $this->sql;
    }
    
    public function setTipoOrdenacao($valor)
    {
        $this->tipoOrdenacao = $valor;
    }

    /**
     * PaginacaoVO::getTipoOrdenacao()
     * 
     * @return
     */
    public function getTipoOrdenacao()
    {
        $Order = strtoupper($this->tipoOrdenacao);

        return ($Order == "ASC") ? "ASC" : "DESC";
    }

    public function setQuemOrdena($valor)
    {
        $this->quemOrdena = $valor;
    }

    public function getQuemOrdena()
    {
        return $this->quemOrdena;
    }
}
