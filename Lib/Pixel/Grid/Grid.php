<?php/** * *    Sappiens Framework *    Copyright (C) 2014, BRA Consultoria * *    Website do autor: www.braconsultoria.com.br/sappiens *    Email do autor: sappiens@braconsultoria.com.br * *    Website do projeto, equipe e documentação: www.sappiens.com.br *    *    Este programa é software livre; você pode redistribuí-lo e/ou *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme *    publicada pela Free Software Foundation, versão 2. * *    Este programa é distribuído na expectativa de ser útil, mas SEM *    QUALQUER GARANTIA; sem mesmo a garantia implícita de *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais *    detalhes. *  *    Você deve ter recebido uma cópia da Licença Pública Geral GNU *    junto com este programa; se não, escreva para a Free Software *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA *    02111-1307, USA. * *    Cópias da licença disponíveis em /Sappiens/_doc/licenca * */namespace Pixel\Grid;use Zion\Paginacao\Parametros;class Grid extends GridVO{    private $meusDadosConverte;    public function __construct()    {        parent::__construct();    }    /**     * Cria uma instrução em html com um link de ordenação     * @param string $campoDescricao     * @param string $campoTb     * @return string     */    public function ordena($campoDescricao, $campoTb)    {        $quemOrdena = parent::getQuemOrdena();        $metodoFiltra = parent::getMetodoFiltra();        $paginaAtual = parent::getPaginaAtual();        $naoOrdenePor = parent::getNaoOrdenePor();        //Iniciar ordenando Ascendente         $tipoOrdenacao = (empty(\filter_input(\INPUT_GET, 'to'))) ? 'ASC' : parent::getTipoOrdenacao();        //Verifica Se o Não Permite Ordenação        if (\in_array($campoTb, $naoOrdenePor)) {            return [                'tipoOrdenacao' => \NULL,                'metodoFiltra' => \NULL,                'campoDescricao' => $campoDescricao];        }        if ($campoTb == $quemOrdena) {            if ($tipoOrdenacao == "DESC") {                $novoTipo = "ASC";            } else {                $novoTipo = "DESC";            }            //Seta Quem ordena            Parametros::setParametros("Full", ["qo" => $campoTb, "pa" => $paginaAtual]);            //Muda o Tipo de Ordenação do Link            $qS = Parametros::addQueryString(Parametros::getQueryString(), ["to" => $novoTipo]);        } else {            //Seta quem ordena e o tipo de ordenacao            Parametros::setParametros("Full", ["qo" => $campoTb, "to" => $tipoOrdenacao, "pa" => $paginaAtual]);            //Recupera QS            $qS = Parametros::getQueryString();        }        //Ordenação        if (!empty($novoTipo)) {            $tipoOrdenacao = $novoTipo;        }        return [            'tipoOrdenacao' => $tipoOrdenacao,            'metodoFiltra' => $metodoFiltra . '(\'' . $qS . '\')',            'campoDescricao' => $campoDescricao];    }    public function converteValor($linha, $dadosConverte)    {        $getDadosConverte = (\is_array($dadosConverte) ? $dadosConverte : $this->meusDadosConverte);        $objeto = $dadosConverte[0]; //Esta sendo exucutada pelo eval        $metodo = $dadosConverte[1];        $campo = $dadosConverte[2];        $pI = (empty($getDadosConverte[3])) ? array() : $getDadosConverte[3];        $pE = (empty($getDadosConverte[4])) ? array() : $getDadosConverte[4];        $ordem = $dadosConverte[5];        if (!empty($pI)) {            foreach ($pI as $valor) {                $arrayPI[] = $linha[$valor];            }        }        if ($ordem == "IE") {            $arParametros = (empty($arrayPI)) ? $pE : \array_merge($arrayPI, $pE);        } else {            $arParametros = (empty($arrayPI)) ? $pE : \array_merge($pE, $arrayPI);        }        if (!\is_array($arParametros)) {            return $linha[$campo];        } else {            $parametros = '';            foreach ($arParametros as $valores) {                $parametros .= "'" . $valores . "',";            }            $parametrosSeparados = \substr($parametros, 0, -1);            eval('$retorno = $objeto->' . $metodo . '(' . $parametrosSeparados . ');');            return $retorno;        }    }        public function verificaComplementoTD($linha, $dadosConverte)    {        $getDadosConverte = $dadosConverte;                $objeto = $dadosConverte[0]; //Esta sendo exucutada pelo eval        $metodo = $dadosConverte[1];        $pI = (empty($getDadosConverte[2])) ? array() : $getDadosConverte[2];        $pE = (empty($getDadosConverte[3])) ? array() : $getDadosConverte[3];        $ordem = $dadosConverte[4];        if (!empty($pI)) {            foreach ($pI as $valor) {                $arrayPI[] = $linha[$valor];            }        }        if ($ordem == "IE") {            $arParametros = (empty($arrayPI)) ? $pE : \array_merge($arrayPI, $pE);        } else {            $arParametros = (empty($arrayPI)) ? $pE : \array_merge($pE, $arrayPI);        }        if (!\is_array($arParametros)) {            return '';        } else {            $parametros = '';            foreach ($arParametros as $valores) {                $parametros .= "'" . $valores . "',";            }            $parametrosSeparados = \substr($parametros, 0, -1);            eval('$retorno = $objeto->' . $metodo . '(' . $parametrosSeparados . ');');            return $retorno;        }    }    public function resultadoEval($linha, $evalCod)    {        eval($evalCod[0]);        return $r;    }    public function setMeusDadosConverte($dadosConverte)    {        $this->meusDadosConverte = $dadosConverte;    }}