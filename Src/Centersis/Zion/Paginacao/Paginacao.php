<?phpnamespace Centersis\Zion\Paginacao;use Centersis\Zion\Paginacao\PaginacaoVO;use Centersis\Zion\Banco\Conexao;class Paginacao extends PaginacaoVO {    private $con;    /**     * Paginacao::__construct()     *      * @return     */    public function __construct($con = NULL) {        parent::__construct();        if (!$con) {            $this->con = Conexao::conectar();        } else {            $this->con = $con;        }    }    /**     * 	Retorna um ResultSet com um numero determinado de QLinhas     * 	@param QLinhas Inteiro - Número de QLinhas a retotnar no RS     * 	@param Sql String - Query SQL que irá selecionar os dados     * 	@param PaginaAtual Inteiro - Página atual dos QLinhas     * 	@param Chave Inteiro - Campo Chave pelo qual deve ser ordenado os resultados     * 	@param QuemOrdena Inteiro - Número de QLinhas a retotnar no RS     * 	@param TipoOrdenacao String - Número de QLinhas a retotnar no RS     * 	@param Ordena String - Número de QLinhas a retotnar no RS     * 	@return ResultSet     */    public function rsPaginado() {        $qLinhas = parent::getQLinhas();        $sql = parent::getSql();        $paginaAtual = parent::getPaginaAtual();        $chave = parent::getChave();        $aliases = parent::getAliasOrdena();        $aliasOrdena = (is_array($aliases) ? $aliases[$chave] . '.' : (empty($aliases) ? NULL : $aliases . '.'));        $quemOrdena = parent::getQuemOrdena();        $tipoOrdenacao = parent::getTipoOrdenacao();        $limitAtivo = parent::getLimitAtivo();        $modoImpressao = parent::getModoImpressao();        //Extremo dos Proximos QLinhas        $inicio = ($paginaAtual == 1) ? 0 : (($paginaAtual * $qLinhas) - $qLinhas);        $queryStringOrdenacao = parent::getQsOrdenacao();        $arrayQso = Parametros::queryStringParaArray($queryStringOrdenacao);        if (is_array($quemOrdena)) {            foreach ($quemOrdena as $cqo => $vqo) {                if (!array_key_exists($cqo, $arrayQso)) {                    $arrayQso[$cqo] = $vqo;                }            }        }        //Verifica Ordenção        if (!empty($quemOrdena) and $tipoOrdenacao <> 'NILL') {            if (is_string($sql)) {                $ordem = $this->interpretaOrdenacao($sql, $arrayQso, $aliases);            } else {                $this->interpretaOrdenacao($sql, $arrayQso, $aliases);            }        } else {            if (is_string($sql)) {                $ordem = " ORDER BY " . $aliasOrdena . $chave . " " . parent::getTipoOrdenacaoSql();            } else {                $sql->orderBy($aliasOrdena . $chave, parent::getTipoOrdenacaoSql());            }        }        //Não é Paginado        if ($qLinhas == 0) {            if (is_string($sql)) {                return $this->con->executar($sql . " " . $ordem);            } else {                return $sql->execute();            }        }        //Definir Limit        $limit = '';        if ($limitAtivo and $qLinhas <> 0 and $modoImpressao != 1) {            if (is_string($sql)) {                $limit = ($qLinhas <> 0) ? " LIMIT " . $inicio . "," . $qLinhas : "";            } else {                $sql->setFirstResult($inicio);                $sql->setMaxResults($qLinhas);            }        }        //Retorno        if (is_string($sql)) {            $rS = $this->con->executar($sql . $ordem . $limit);        } else {            $rS = $sql->execute();        }        return $rS;    }    private function interpretaOrdenacao($strObjeto, $arrayQso, $aliases) {        if (!is_array($aliases)) {            $aliases = [];        }        if (is_array($arrayQso)) {            $arrayStr = [];            foreach ($arrayQso as $campo => $ordena) {                $alias = '';                if (array_key_exists($campo, $aliases)) {                    $alias = $aliases[$campo] . '.';                }                if (is_object($strObjeto)) {                    if ($ordena === 'ASC' or $ordena === 'DESC') {                        $strObjeto->addOrderBy($alias . $campo, $ordena);                    }                } else {                    if ($ordena === 'ASC' or $ordena === 'DESC') {                        $arrayStr[] = ' ' . $alias . $campo . ' ' . $ordena . ' ';                    }                }            }            if (is_string($strObjeto) and!empty($arrayStr)) {                return ' ORDER BY ' . implode(',', $arrayStr);            }        }        return '';    }    /**     * 	Retorna um ResultSet com um numero determinado de QLinhas     * 	@param QLinhas Inteiro - Número de QLinhas a retotnar no RS     * 	@param Sql String - Query SQL que irá selecionar os dados     * 	@param PaginaAtual Inteiro - Página atual dos QLinhas     * 	@param IrParaPagina Booleano - Ir diretamente para a página desejada habilitar ou não esta opação na paginação     * 	@return Booleano     */    public function listaResultados() {        $buffer = [];        $qLinhas = parent::getQLinhas();        $paginaAtual = parent::getPaginaAtual();        $quemOrdena = parent::getQuemOrdena();        $tipoOrdenacao = parent::getTipoOrdenacao();        $metodoFiltra = parent::getMetodoFiltra();        $container = parent::getContainer();        $sql = parent::getSql();        $processarNumeroPaginas = parent::getProcessarNumeroPaginas();        $qso = parent::getQsOrdenacao();        if ($qLinhas === 0) {            return;        }        if (is_string($sql)) {            if (substr_count(strtoupper(parent::getSql()), 'SELECT ') > 1) {                $numLinhas = $this->con->execNLinhas(parent::getSql());            } else {                $numLinhas = $this->con->execRLinha($this->converteSql(parent::getSql()));            }        } else {            if ($processarNumeroPaginas === false) {                $inicio = ($paginaAtual == 1) ? 1 : (($paginaAtual * $qLinhas) - $qLinhas);                $limit = (($qLinhas + 1) + ($paginaAtual * $qLinhas - $qLinhas));                $sql->setFirstResult($inicio);                $sql->setMaxResults($limit);                $numLinhas = $this->con->execNLinhas($sql);            } else {                $sql->add("select", 'COUNT(*) AS total_registros_pag', true)                        ->setFirstResult(0);                $numLinhas = $this->con->execRLinha($sql, "total_registros_pag");            }        }        //Total de Páginas        if ($processarNumeroPaginas === false) {            $totalPaginas = ceil($numLinhas / $qLinhas) + $paginaAtual - 1;        } else {            $totalPaginas = ceil($numLinhas / $qLinhas);        }        $buffer['paginaAtual'] = $paginaAtual;        $buffer['totalPaginas'] = $totalPaginas;        $buffer['totalRegistros'] = $numLinhas;        $buffer['totalRegistrosPagina'] = $qLinhas;        $sisOrigem = filter_input(INPUT_GET, 'sisOrigem');        $sisUFC = filter_input(INPUT_GET, 'sisUFC');        //Imprimindo QLinhas        if ($totalPaginas > 1) {            //Verifica se existe variavel para QuemOrdena de ordenação            if (!empty($quemOrdena)) {                Parametros::setParametros("Full", array("qo" => $quemOrdena, 'to' => $tipoOrdenacao, 'sisUFC' => $sisUFC, 'sisOrigem' => $sisOrigem, 'qso' => $qso));            }            //Anterior            if ($paginaAtual > 1) {                Parametros::setParametros("Full", array("pa" => ($paginaAtual - 1), 'sisUFC' => $sisUFC, 'sisOrigem' => $sisOrigem, 'qso' => $qso));                $onclick = $metodoFiltra . '(\'' . Parametros::getQueryString() . '\',\'' . $container . '\'); sisSpa(\'' . ($paginaAtual - 1) . '\',\'' . $container . '\');';                $buffer['anterior']['onclick'] = $onclick;                $buffer['anterior']['ativo'] = true;            } else {                $buffer['anterior']['ativo'] = false;            }            //Proxima            if ($paginaAtual < $totalPaginas) {                Parametros::setParametros("Full", array("pa" => ($paginaAtual + 1), 'sisUFC' => $sisUFC, 'sisOrigem' => $sisOrigem, 'qso' => $qso));                $onclick = $metodoFiltra . '(\'' . Parametros::getQueryString() . '\',\'' . $container . '\'); sisSpa(\'' . ($paginaAtual + 1) . '\',\'' . $container . '\');';                $buffer['proximo']['onclick'] = $onclick;                $buffer['proximo']['ativo'] = true;            } else {                $buffer['proximo']['ativo'] = false;            }        }        return $buffer;    }    /**     * Paginacao::converteSql()     *      * @return     */    public function converteSql($sql) {        return preg_replace('/SELECT.*FROM/i', 'SELECT COUNT(*) as Total FROM ', preg_replace('/\s/i', ' ', $sql));    }}