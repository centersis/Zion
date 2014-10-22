<?php/** *   @author Pablo Vanni - pablovanni@gmail.com *   @since 18/11/2005 *   Última Atualização: 13/10/2014 *   Autualizada Por: Pablo Vanni - pablovanni@gmail.com *   @name Cria uma grid de resultados com paginação */namespace Pixel\Grid;class GridVO extends \Zion\Paginacao\PaginacaoVO{    private $titulos; //Array - Array Com Conjunto de Titulos a Serem Exibidos na Grid     private $listados; //Array - Array Com Conjunto de Atributos a Serem Exibidos na Grid      private $alinhamento; //Array - Array Com Informações de Alinhamento em Cada Campo    private $naoOrdenePor; //Array - Array Com Atrubutos que não devem ser ordenados na grid    private $condicaoResultadoUnico; //String - Recebe uma estring com uma condição PHP para se obter uma particularidade para um unico resultado    private $condicaoTodosResultados; //String - Recebe uma estring com uma condição PHP para se obter uma particularidade para uma linha de resultados    private $objConverte; //Objeto - Recebe Um objeto de Converssão para os elementos da grid 	    private $formatarComo; //Array que recebe o campo e como deve formata-lo    private $selecao; //Bool - Informa se a grid deve ter coluna de seleção    private $selecaoMultipla; //Bool - Informa se a grid deve ser apresentada com seleção Multipla ou simples    public function __construct()    {        parent::__construct();        $this->titulos = [];        $this->listados = [];        $this->alinhamento = [];        $this->naoOrdenePor = [];        $this->condicaoResultadoUnico = "";        $this->condicaoTodosResultados = "";        $this->objConverte = [];        $this->formatarComo = [];        $this->selecao = true;        $this->selecaoMultipla = false;    }    public function setTitulos($valor)    {        if (!is_array($valor)) {            throw new \Exception("Grid: Titulos informado inválido!");        }        $this->titulos = $valor;    }    public function getTitulos()    {        return $this->titulos;    }    public function setListados($valor)    {        if (!is_array($valor)) {            throw new \Exception("Grid: Listados informado inválido!");        }        $this->listados = $valor;    }    public function getListados()    {        return $this->listados;    }    public function setAlinhamento($valor)    {        if (!is_array($valor)) {            throw new \Exception("Grid: Alinhamento informado inválido!");        }        $this->alinhamento = $valor;    }    public function getAlinhamento($valor)    {        if (!key_exists($valor, $this->alinhamento)) {            return "";        }        switch (strtoupper($this->alinhamento[$valor])) {            case "DIREITA": return " sis_al_direita ";            case "CENTRO": return " sis_al_center ";            default: return "";        }    }    public function setCondicaoResultadoUnico($campo, $condicao, $class)    {        $this->condicaoResultadoUnico[$campo] = [$condicao, $class];    }    public function getCondicaoResultadoUnico()    {        return $this->condicaoResultadoUnico;    }    public function setCondicaoTodosResultados($condicao, $class)    {        $this->condicaoTodosResultados[] = [$condicao, $class];    }    public function getCondicaoTodosResultados()    {        return $this->condicaoTodosResultados;    }    public function setObjConverte($objeto, $metodo, $campo, $parametrosInternos = null, $paremetrosExternos = null, $ordem = 'IE')    {        if (!is_object($objeto)) {            throw new \Exception("Grid: Objeto informado inválido!");        }        if (!is_string($metodo)) {            throw new \Exception("Grid: Metodo informado inválido!");        }        if (!is_string($campo)) {            throw new \Exception("Grid: Campo informado inválido!");        }        if (!empty($parametrosInternos)) {            if (!is_array($parametrosInternos)) {                throw new \Exception("Grid: ParametrosInternos informado inválido!");            }        }        if (!empty($paremetrosExternos)) {            if (!is_array($paremetrosExternos)) {                throw new \Exception("Grid: ParametrosInternos informado inválido!");            }        }        if (!in_array(strtoupper($ordem), ["IE", "EI"])) {            throw new \Exception("Grid: Ordem informado inválido!");        }        $this->objConverte[$campo] = [$objeto, $metodo, $campo, $parametrosInternos, $paremetrosExternos, $ordem];    }    public function getObjetoConverte()    {        return $this->objConverte;    }    public function setNaoOrdenePor($valor)    {        if (!is_array($valor)) {            throw new \Exception("Grid: NaoOrdenePor informado inválido!");        }        $this->naoOrdenePor = $valor;    }    public function getNaoOrdenePor()    {        return $this->naoOrdenePor;    }    public function setFormatarComo($Identificacao, $como)    {        if (!is_string($Identificacao)) {            throw new \Exception("Grid: FormatarComo parametro 1 informado inválido!");        }        if (!is_string($como)) {            throw new \Exception("Grid: FormatarComo parametro 2 informado inválido!");        }        $this->formatarComo[$Identificacao] = $como;    }    public function getFormatarComo()    {        return $this->formatarComo;    }    public function getSelecao()    {        return $this->selecao;    }    public function setSelecao($selecao)    {        $this->selecao = $selecao;    }    public function getSelecaoMultipla()    {        return $this->selecaoMultipla;    }    public function setSelecaoMultipla($selecaoMultipla)    {        $this->selecaoMultipla = $selecaoMultipla;    }}