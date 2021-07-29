<?phpnamespace Centersis\Zion\Paginacao;abstract class PaginacaoVO{    private $tipoOrdenacao;    private $quemOrdena;    private $qsOrdenacao;    private $sql;    private $totalRegistros;    private $tabelaMestra;    private $sqlContador;    private $filtroAtivo;    private $limitAtivo;    private $chave;    private $aliasOrdena;    private $metodoFiltra;    private $qLinhas;    private $paginaAtual;    private $irParaPagina;    private $alterarLinhas;    private $processarNumeroPaginas;    private $modoImpressao;    private $paginar;    private $container;    public function __construct()    {        $this->filtroAtivo = false;        $this->limitAtivo = true;        $this->qLinhas = 0;        $this->paginaAtual = 1;        $this->irParaPagina = true;        $this->alterarLinhas = true;        $this->processarNumeroPaginas = true;        $this->paginar = true;    }    public function setTipoOrdenacao($valor)    {        $this->tipoOrdenacao = $valor;    }    public function getTipoOrdenacao()    {        $order = \strtoupper($this->tipoOrdenacao);        return \in_array($order, ['ASC', 'DESC', 'NILL']) ? $order : 'DESC';    }    public function getTipoOrdenacaoSql()    {        $order = \strtoupper($this->tipoOrdenacao);        return \in_array($order, ['ASC', 'DESC']) ? $order : 'DESC';    }    public function setQuemOrdena($valor)    {        $this->quemOrdena = $valor;    }    public function getQuemOrdena()    {        return $this->quemOrdena;    }    public function setQsOrdenacao($qsOrdenacao)    {        $this->qsOrdenacao = $qsOrdenacao;    }    public function getQsOrdenacao()    {        return $this->qsOrdenacao;    }    public function setSql($valor)    {        $this->sql = $valor;    }    public function getSql()    {        return $this->sql;    }    public function setTotalRegistros($valor)    {        $this->totalRegistros = $valor;        return $this;    }    public function getTotalRegistros()    {        return $this->totalRegistros;    }    public function setTabelaMestra($valor)    {        $this->tabelaMestra = $valor;    }    public function getTabelaMestra()    {        return $this->tabelaMestra;    }    public function setSqlContador($valor)    {        $this->sqlContador = $valor;    }    public function getSqlContador()    {        return $this->sqlContador;    }    public function setFiltroAtivo($valor)    {        $this->filtroAtivo = $valor;    }    public function getFiltroAtivo()    {        return $this->filtroAtivo;    }    public function setLimitAtivo($valor)    {        $this->limitAtivo = $valor;    }    public function getLimitAtivo()    {        return $this->limitAtivo ? true : false;    }    public function setChave($valor)    {        $this->chave = \strtolower($valor);    }    public function getChave()    {        return $this->chave;    }    public function setAliasOrdena($valor)    {        if (\is_array($valor)) {            $valor = \array_change_key_case($valor);        } else {            $valor = \strtolower($valor);        }        $this->aliasOrdena = $valor;    }    public function getAliasOrdena()    {        return $this->aliasOrdena;    }    public function setMetodoFiltra($valor)    {        $this->metodoFiltra = $valor;    }    public function getMetodoFiltra()    {        return (empty($this->metodoFiltra)) ? "sisFiltrarPadrao" : $this->metodoFiltra;    }    public function setQLinhas($valor)    {        $this->qLinhas = $valor;    }    public function getQLinhas()    {        return(\is_numeric($this->qLinhas)) ? $this->qLinhas : 0;    }    public function setPaginaAtual($valor)    {        $this->paginaAtual = $valor;    }    public function getPaginaAtual()    {        return (empty($this->paginaAtual)) ? 1 : $this->paginaAtual;    }    public function setIrParaPagina($valor)    {        $this->irParaPagina = (bool) $valor;    }    public function getIrParaPagina()    {        return $this->irParaPagina;    }    public function setAlterarLinhas($valor)    {        $this->alterarLinhas = (bool) $valor;    }    public function getAlterarLinhas()    {        return $this->alterarLinhas;    }    public function setProcessarNumeroPaginas($valor)    {        $this->processarNumeroPaginas = $valor;    }    public function getProcessarNumeroPaginas()    {        return $this->processarNumeroPaginas;    }    public function addVariaveisPaginacao(Array $variaveis, $metodoEnvio = 'GET')    {        Parametros::setParametros($metodoEnvio, $variaveis);    }    public function getModoImpressao()    {        return $this->modoImpressao;    }    public function setModoImpressao($modoImpressao)    {        $this->modoImpressao = $modoImpressao;        return $this;    }    public function getContainer()    {        return $this->container;    }    public function setContainer($container)    {        $this->container = $container;        return $this;    }}