<?phpnamespace Pixel\Grid;use Zion\Banco\Conexao;use Zion\Paginacao\Paginacao;use Zion\Validacao\Valida;use Zion\Paginacao\Parametros;use Pixel\Form\Form;use Zion\Log\Log;use Pixel\Crud\CrudUtil;use Pixel\Twig\Carregador;class GridPadrao{    private $con;    private $paginacao;    private $botoes;    private $modoImpressao;    private $grid;    private $log;    /**     * @param \Zion\Banco\Conexao $con     */    public function __construct(Conexao $con = NULL)    {        $this->grid = new Grid();        $this->con = (!\is_object($con)) ? Conexao::conectar() : $this->con = $con;        $this->paginacao = new Paginacao($con);        $this->botoes = new GridBotoes();        $this->grid->setSelecaoMultipla(true);        $qso = \filter_input(\INPUT_GET, 'qso');        $qo = \filter_input(\INPUT_GET, 'qo');        $to = \filter_input(\INPUT_GET, 'to');        $pa = \filter_input(\INPUT_GET, 'pa');        //Padrões Iniciais        $this->grid->setTipoOrdenacao($to);        $this->grid->setQuemOrdena($qo);        $this->grid->setQsOrdenacao(Parametros::atualizaQueryString($qso, $qo, $to));        $this->grid->setPaginaAtual($pa);        $this->grid->setQLinhas(10);    }    private function tituloGridPadrao($colunasDinamicas)    {        $buffer = [];        $selecao = $this->grid->getSelecao();        $buffer['selecao'] = $selecao;        //Titulos        foreach ($colunasDinamicas as $id => $dados) {            $coluna = $id;            $titulo = $dados;            if (\is_array($dados)) {                $titulo = $dados[0];            }            $alinhamento = $this->grid->getAlinhamento($coluna);            if ($alinhamento) {                $buffer['alinhamento'][$coluna] = $this->grid->getAlinhamento($coluna);            }            $pr = Parametros::$parametros;            $buffer['titulo'][$coluna] = $this->grid->ordena($titulo, $coluna);            Parametros::limpaParametros();            Parametros::$parametros = $pr;        }        $buffer['qso'] = Parametros::queryStringParaArray($this->grid->getQsOrdenacao());        return $buffer;    }    public function montaGridPadrao()    {        //Modo de impresssão        $this->modoImpressao = false;        $colunasSemHTML = [];        //Desabilita a paginação para o modo de impressão, mantendo apenas filtros e ordenação.        if (\filter_input(\INPUT_GET, 'sisModoImpressao')) {            $this->modoImpressao = true;            $this->paginacao->setModoImpressao($this->modoImpressao);            $this->grid->setQLinhas(0);            $this->setSelecao(false);            $colunasSemHTML = $this->grid->getColunasSemHTML();        }        //Recupera Valores        $sql = $this->grid->getSql();        $tabelaMestra = $this->grid->getTabelaMestra();        $sqlContador = $this->grid->getSqlContador();        $filtroAtivo = $this->grid->getFiltroAtivo();        $limitAtivo = $this->grid->getLimitAtivo();        $chave = $this->grid->getChave();        $aliasOrdena = $this->grid->getAliasOrdena();        $formatarComo = $this->grid->getFormatarComo();        $selecao = $this->grid->getSelecao();        $selecaoMultipla = $this->grid->getSelecaoMultipla();        $totalizador = $this->grid->getTotalizador();        $processarUpload = $this->grid->getProcessarUpload();        $configuracaoPersonalizada = $this->grid->getConfiguracaoPersonalizada();        $moduloCod = 0;        $qLinhas = $this->grid->getQLinhas();        if (\defined('MODULO') and $this->modoImpressao != 1 and $limitAtivo) {            $qbModulo = $this->con->qb();            $qbModulo->select('moduloCod')                ->from('_modulo', '')                ->where($qbModulo->expr()->eq('moduloNome', $qbModulo->expr()->literal(\MODULO)));            $moduloCod = $this->con->execRLinha($qbModulo);            $qbQlinhas = $this->con->qb();            $qbQlinhas->select('usuarioPaginacaoTotal')                ->from('_usuario_paginacao')                ->where('organogramaCod = ' . $_SESSION['organogramaCod'])                ->andWhere('usuarioCod = ' . $_SESSION['usuarioCod'])                ->andWhere('moduloCod = ' . $moduloCod);            $qlEncontrado = $this->con->execRLinha($qbQlinhas);            if (\is_numeric($qlEncontrado)) {                $qLinhas = $qlEncontrado;            }        }        $colunasDinamicas = $this->interpretaColunas($this->grid->getColunas(), $configuracaoPersonalizada);        $colunasAtivas = $this->getColunasAtivas($colunasDinamicas);        $buffer = [];        $bufferTitulo = $this->tituloGridPadrao($colunasAtivas);        $listados = \array_keys($colunasAtivas);        $this->paginacao->setSql($sql);        //Verifica se o SQL não esta Vazio        if (empty($sql)) {            throw new \Exception("Valor selecionado inválido!");        }        if ($this->getLog() === true) {            (new Log())->registraLogUsuario($_SESSION['usuarioCod'], \MODULO, 'filtrar', $this->grid->getSql());        }        //Se Formatações existem, intancie funções de validação.        if (!empty($formatarComo)) {            $fPHP = Valida::instancia();        }        $buffer['chave'] = $chave;        $buffer['selecao']['selecao'] = $selecao;        $buffer['selecao']['selecaoMultipla'] = $selecaoMultipla;        //Setando Valores para paginação        $this->paginacao->setTabelaMestra($tabelaMestra);        $this->paginacao->setSqlContador($sqlContador);        $this->paginacao->setFiltroAtivo($filtroAtivo);        $this->paginacao->setLimitAtivo($limitAtivo);        $this->paginacao->setChave($chave);        $this->paginacao->setAliasOrdena($aliasOrdena);        $this->paginacao->setQLinhas($qLinhas);        $this->paginacao->setProcessarNumeroPaginas($this->grid->getProcessarNumeroPaginas());        $this->paginacao->setPaginaAtual($this->grid->getPaginaAtual());        $this->paginacao->setTipoOrdenacao($this->grid->getTipoOrdenacao());        $this->paginacao->setQuemOrdena($this->grid->getQuemOrdena());        $this->paginacao->setQsOrdenacao($this->grid->getQsOrdenacao());        $this->paginacao->setMetodoFiltra($this->grid->getMetodoFiltra());        $this->paginacao->setAlterarLinhas($this->grid->getAlterarLinhas());        $rs = $this->paginacao->rsPaginado();        $nLinhas = $this->con->nLinhas($rs);        $buffer['nLinhas'] = $nLinhas;        //Contruindo grid        if ($nLinhas > 0) {            $buffer['paginacao'] = $this->paginacao->listaResultados();            $subs = $this->grid->getSubstituirPor();            $objC = $this->grid->getObjetoConverte();            $cTd = $this->grid->getComplementoTD();            $cssTd = $this->grid->getCssTD();            $i = 0;            $valorTotalizadores = [];            while ($linha = $rs->fetch()) {                $i += 1;                if (\is_array($cTd) and ! empty($cTd)) {                    $buffer['cTd'][$linha[$chave]] = $this->grid->verificaComplementoTD($linha, $cTd);                }                if (\is_array($cssTd) and ! empty($cssTd)) {                    $buffer['cssTd'][$linha[$chave]] = $this->grid->verificaComplementoTD($linha, $cssTd);                }                foreach ($listados as $value) {                    //Valor com possivel converssão                    if (\is_array($objC) and \key_exists($value, $objC)) {                        if (isset($colunasSemHTML[$value])) {                            $valorItem = $linha[$value];                        } else {                            $valorItem = $this->grid->converteValor($linha, $objC[$value]);                        }                    } else {                        $valorItem = '';                        if (\array_key_exists($value, $linha)) {                            $valorItem = $linha[$value];                            //throw new \Exception('Grid: Valor ' . $value . ' não encontrado!');                        }                    }                    if (!empty($totalizador)) {                        if (\array_key_exists($value, $totalizador)) {                            $valorTotalizadores[$value][] = $valorItem;                        }                    }                    //Formatação                    if (!empty($formatarComo)) {                        if (\array_key_exists($value, $formatarComo)) {                            $como = \strtoupper($formatarComo[$value]);                            switch ($como) {                                case "DATA" : $valorItem = $fPHP->data()->converteData($valorItem);                                    break;                                case "DATAHORA": $valorItem = $fPHP->data()->converteDataHora($valorItem);                                    break;                                case "NUMERO" : $valorItem = $fPHP->numero()->floatCliente($valorItem);                                    break;                                case "MOEDA" : $valorItem = $fPHP->numero()->floatCliente($valorItem);                                    break;                                case "REAIS" : $valorItem = $fPHP->numero()->moedaCliente($valorItem);                                    break;                            }                        }                    }                    //Valor com possivel stituição                    if (\is_array($subs) and \array_key_exists($value, $subs)) {                        if (\array_key_exists($valorItem, $subs[$value])) {                            $valorItem = $subs[$value][$valorItem];                        } else {                            if ($valorItem == '') {                                $valorItem = \current($subs[$value]);                            }                        }                    }                    //Upload                    if (\is_array($processarUpload) and \array_key_exists($value, $processarUpload)) {                        $buffer['valores'][$linha[$chave]][$value] = $this->mostraUpload($processarUpload[$value], $linha[$processarUpload[$value]['referenciaCod']]);                    } else {                        $buffer['valores'][$linha[$chave]][$value] = $valorItem;                    }                }            }            if (!empty($totalizador)) {                foreach ($listados as $itenListado) {                    if (\array_key_exists($itenListado, $totalizador)) {                        $buffer['totalizador'][$itenListado] = $this->processaTotalizador($totalizador[$itenListado], $valorTotalizadores[$itenListado]);                    }                }            }        }        $buffer['modoImpressao'] = $this->modoImpressao;        $buffer['legenda'] = $this->grid->getLegenda();        $buffer['queryString'] = Parametros::getQueryString();        $buffer['ordenacao'] = ['qo' => $this->grid->getQuemOrdena(), 'to' => $this->grid->getTipoOrdenacao()];        /* ALTERAÇÂO DE LINHAS */        $aGeradoT = \range(1, 200);        $form = new Form();        $campo[] = $form->escolha('sisAlteraLinhas', 'NLinhasGrid')            ->setValorPadrao($qLinhas)            ->setComplemento('onchange="sisAlterarLinhas(\'' . \SIS_URL_BASE . '\',' . $moduloCod . ');"')            ->setArray(\array_combine($aGeradoT, $aGeradoT));        $form->processarForm($campo);        $buffer['alterarLinhas'] = $form->getFormHtml('sisAlteraLinhas');        /* ALTERAÇÂO DE LINHAS */        $buffer['configuracaoPersonalizada'] = $configuracaoPersonalizada;        $buffer['colunasDinamicas'] = $colunasDinamicas;        $buffer['moduloCod'] = $moduloCod;        $buffer['listaColunas'] = \implode(',', $listados);        $buffer['view'] = $this->grid->getView();        $ret = \array_merge($bufferTitulo, $buffer);        return $ret;    }    private function mostraUpload($config, $uploadCodReferencia)    {        $carregadorClass = new Carregador();        $moduloCod = $this->getModuloCod($config['modulo']);        $organogramaCod = $_SESSION['organogramaCod'];        if(isset($config['organogramaCod']) and !empty($config['organogramaCod'])){            $organogramaCod = $config['organogramaCod'];        }                        $qbSelect = $this->con->qb();        $qbSelect->select('*')            ->from("_upload", '')            ->where($qbSelect->expr()->eq('organogramaCod', ':organogramaCod'))            ->andWhere($qbSelect->expr()->eq('uploadCodReferencia', ':uploadCodReferencia'))            ->andWhere($qbSelect->expr()->eq('moduloCod', ':moduloCod'))            ->andWhere($qbSelect->expr()->eq('uploadNomeCampo', ':uploadNomeCampo'))            ->setParameters([                'organogramaCod' => $organogramaCod,                'uploadCodReferencia' => $uploadCodReferencia,                'moduloCod' => $moduloCod,                'uploadNomeCampo' => $config['nome']        ]);        $dados = $this->con->paraArray($qbSelect);        return $carregadorClass->render('upload_grid.html.twig', [                'arquivos' => $dados,                'config' => $config,                'urlBaseStorage' => \SIS_URL_BASE_STORAGE,                'organogramaCod' => $organogramaCod        ]);    }    private function getModuloCod($modulo = '')    {        if (!$modulo) {            $modulo = \MODULO;        }        $qbModulo = $this->con->qb();        $qbModulo->select('moduloCod')            ->from('_modulo', '')            ->where($qbModulo->expr()->eq('moduloNome', $qbModulo->expr()->literal($modulo)));        $moduloCod = $this->con->execRLinha($qbModulo);        if (!$moduloCod) {            return 0;        }        return $moduloCod;    }    private function interpretaColunas($colunas, $configuracaoPersonalizada)    {        $crudUtil = new CrudUtil();        return $crudUtil->getColunasDinamicas($colunas, $configuracaoPersonalizada);    }    private function getColunasAtivas($colunasDinamicas)    {        $retorno = [];        foreach ($colunasDinamicas as $id => $dados) {            if ($dados[2] === 'S' or $dados[2] === true) {                $retorno[$id] = $dados;            }        }        return $retorno;    }    private function processaTotalizador($config, $itens)    {        $fPHP = Valida::instancia();        if (!empty($config) and ! empty($itens) and \is_array($itens)) {            $resultado = 0;            switch ($config['tipo']) {                case 'conta':                    $resultado = \count($itens);                    break;                case 'soma':                    $itens = $this->traduzItens($itens);                    $resultado = \array_sum($itens);                    break;                case 'media':                    $itens = $this->traduzItens($itens);                    $resultado = \round(\array_sum($itens) / \count($itens), 2);                    break;                default : $resultado = ' - ';            }            $mascara = '';            $prefixo = '';            $sufixo = '';            if (\array_key_exists('mascara', $config)) {                $mascara = $config['mascara'];            }            if (\array_key_exists('prefixo', $config)) {                $prefixo = $config['prefixo'];            }            if (\array_key_exists('sufixo', $config)) {                $sufixo = $config['sufixo'];            }            switch ($mascara) {                case "moeda":                    return $prefixo . ' ' . $fPHP->numero()->moedaCliente($resultado) . ' ' . $sufixo;                default:                    return $prefixo . ' ' . $resultado . ' ' . $sufixo;            }        }        return '-';    }    private function traduzItens($itens)    {        $fPHP = Valida::instancia();        //Traduzindo Valores        foreach ($itens as $chave => $valor) {            $traduzido = $fPHP->numero()->floatBanco(\implode(\preg_split('/[^[:digit:] (,.)]/', $valor)));            $itens[$chave] = $traduzido;        }        return $itens;    }#####################################################################    public function setTipoOrdenacao($valor)    {        $this->grid->setTipoOrdenacao($valor);    }    public function setQuemOrdena($valor)    {        $this->grid->setQuemOrdena($valor);    }    public function setSql($valor)    {        $this->grid->setSql($valor);    }    public function setSqlContador($valor)    {        $this->grid->setSqlContador($valor);    }    public function setFiltroAtivo($valor)    {        $this->grid->setFiltroAtivo($valor);    }    public function setLimitAtivo($valor)    {        $this->grid->setLimitAtivo($valor);    }    public function setChave($valor)    {        $this->grid->setChave($valor);    }    public function setAliasOrdena($valor)    {        $this->grid->setAliasOrdena($valor);    }    public function setMetodoFiltra($valor)    {        $this->grid->setMetodoFiltra($valor);    }    public function setQLinhas($valor)    {        $this->grid->setQLinhas($valor);    }    public function setPaginaAtual($valor)    {        $this->grid->setPaginaAtual($valor);    }    public function setIrParaPagina($valor)    {        $this->grid->setIrParaPagina($valor);    }    public function setAlterarLinhas($valor)    {        $this->grid->setAlterarLinhas($valor);    }    /**     * Monta um array representativo das colunas da tabela de um banco de dados.     * Por questões de compatibilidade as colunas serão convertidas      * automaticamente para minisculo     * @param array $arrayColunas     * @throws \Exception     */    public function setColunas($arrayColunas)    {        $this->grid->setColunas($arrayColunas);    }    public function getColunas()    {        return $this->grid->getColunas();    }    public function setColunasSemHTML($arrayColunasSemHTML)    {        $this->grid->setColunasSemHTML($arrayColunasSemHTML);    }    /**     * Monta um array com informações de alinhamento de campos, pode alinhar um     * ou mais campos     * setAlinhamento(['campo1'=>'Esquerda', 'campo2'=>'Centro'],'campo3'=>'Direita');     * @param array $arrayAlinhamento     * @throws \Exception     */    public function setAlinhamento($arrayAlinhamento)    {        $this->grid->setAlinhamento($arrayAlinhamento);    }    /**     * Usa um objeto, um metodos e a indicaçãoo de como usa-los, com a função     * de converter um resultado da grid.     *      * $grid->converterResultado($this, 'mostraIcone', 'moduloClass', ['moduloClass']);     *      * @param object $objeto     * @param string $metodo     * @param string $campo     * @param array $parametrosInternos     * @param array $paremetrosExternos     * @param string $ordem     * @throws \Exception     */    public function converterResultado($objeto, $metodo, $campo, $parametrosInternos = [], $paremetrosExternos = [], $ordem = 'IE')    {        $this->grid->converterResultado($objeto, $metodo, $campo, $parametrosInternos, $paremetrosExternos, $ordem);    }    /**     * Usa um objeto, um metodo e a indicação de como usa-los, com a função     * de inserir um complemento em cada TD de resultado de uma grid     *      * $grid->complementoTD($this, 'mostraIcone', ['moduloClass']);     *      * @param object $objeto     * @param string $metodo     * @param array $parametrosInternos     * @param array $paremetrosExternos     * @param string $ordem     * @throws \Exception     */    public function complementoTD($objeto, $metodo, $parametrosInternos = [], $paremetrosExternos = [], $ordem = 'IE')    {        $this->grid->complementoTD($objeto, $metodo, $parametrosInternos, $paremetrosExternos, $ordem);    }    public function cssTD($objeto, $metodo, $parametrosInternos = [], $paremetrosExternos = [], $ordem = 'IE')    {        $this->grid->cssTD($objeto, $metodo, $parametrosInternos, $paremetrosExternos, $ordem);    }    /**     * Monta um array com informações de ordenação de campos, pode ordenar um     * ou mais campos     *      * $grid->naoOrdenePor(['moduloClass']);     *      * @param array $arrayNaoOrdenePor     * @throws \Exception     */    public function naoOrdenePor($arrayNaoOrdenePor)    {        $this->grid->naoOrdenePor($arrayNaoOrdenePor);    }    /**     * Deve receber um array com as configurações de upload     *      * $grid->processarUpload([     *       'anexo' => [     *           'referenciaCod' => 'fnc_lancamento_cod',     *           'nome' => 'anexo',     *           'altura' => 50,     *           'urlBaseStorage' => \SIS_URL_BASE_STORAGE,     *           'modulo' => \MODULO]     *   ]);     *      * @param array $processarUpload     */    public function processarUpload($processarUpload)    {        $this->grid->setProcessarUpload($processarUpload);    }        public function setConfiguracaoPersonalizada($configuracaoPersonalizada)    {        $this->grid->setConfiguracaoPersonalizada($configuracaoPersonalizada);    }    /**     * Formata um resultado da grid, pode ser (DATA, DATAHORA, NUMERO, MOEDA)     *      * $grid->setFormatarComo('moduloClass','DATA');     *      * @param string $identificacao     * @param string $como     * @throws \Exception     */    public function setFormatarComo($identificacao, $como)    {        $this->grid->setFormatarComo($identificacao, $como);    }    public function formatarComo($identificacao, $como)    {        return $this->setFormatarComo($identificacao, $como);    }    /**     * Indica se a grid deve apresentar checkbox ou radiobox de seleção      * de resultados     * @param bool $selecao     */    public function setSelecao($selecao)    {        $this->grid->setSelecao($selecao);    }    /**     * Por padrão a seleção multipla é verdadeira, no caso de setar false para      * este metodo a grid irá trazer radios para a seleção de resultados.     * @param bool $selecaoMultipla     */    public function setSelecaoMultipla($selecaoMultipla)    {        $this->grid->setSelecaoMultipla($selecaoMultipla);    }    /**     * Substitui um valor da grid por um valor equivalente em um array     *      * $grid->substituaPor('moduloVisivelMenu', ['S' => 'Sim', 'N' => 'Não']);     *      * @param string $identificacao     * @param string $por     * @throws \Exception     */    public function substituaPor($identificacao, $por)    {        $this->grid->substituaPor($identificacao, $por);    }    public function setProcessarNumeroPaginas($valor)    {        $this->grid->setProcessarNumeroPaginas($valor);    }    public function setLegenda($legenda)    {        $this->grid->setLegenda($legenda);    }    public function setLog($log)    {        if (!\is_bool($log)) {            throw new \Exception("log: o valor informado deve ser do tipo booleano.");        }        $this->log = $log;    }    public function getLog()    {        return $this->log;    }    public function setTotalizador($identificador, $configuracoes)    {        $this->grid->setTotalizador($identificador, $configuracoes);    }    public function setView($view)    {        $this->grid->setView($view);    }}