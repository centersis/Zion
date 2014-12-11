<<<<<<< HEAD<?php/** *   @copyright CenterSis - Sistemas Para Internet *   @author Pablo Vanni - pablovanni@gmail.com *   @since 18/11/2005 *   Última Atualização: 13/10/2014 *   Autualizada Por: Pablo Vanni - pablovanni@gmail.com *   @name Cria uma grid de resultados com paginação */namespace Pixel\Grid;class GridPadrao extends Grid{    private $con;    private $paginacao;    private $html;    private $botoes;    public function __construct($con = NULL)    {        parent::__construct();        $this->con = (!is_object($con)) ? \Zion\Banco\Conexao::conectar() : $this->con = $con;        $this->paginacao = new \Zion\Paginacao\Paginacao($con);        $this->html = new \Zion\Layout\Html();        $this->botoes = new GridBotoes();        //Padrões Iniciais        parent::setTipoOrdenacao(filter_input(INPUT_GET, 'to'));        parent::setQuemOrdena(filter_input(INPUT_GET, 'qo'));        parent::setPaginaAtual(filter_input(INPUT_GET, 'pa'));        parent::setQLinhas(SIS_LINHAS_GRID);    }    public function tituloGridPadrao()    {        $titulos = parent::getTitulos();        $listados = parent::getListados();        $selecao = parent::getSelecao();        $html = $this->html->abreTagAberta('thead');        $html .= $this->html->abreTagAberta('tr');        if ($selecao === true) {            $html .= $this->html->abreTagFechada('th');        }        //Titulos        foreach ($titulos as $Key => $value) {            $classAlinha = parent::getAlinhamento($listados[$Key]);            $html .= $this->html->abreTagAberta('th', ['class' => $classAlinha]);            $html .= parent::ordena($value, $listados[$Key]);            $html .= $this->html->fechaTag('th');        }        $html .= $this->html->fechaTag('tr');        $html .= $this->html->fechaTag('thead');        return $html;    }    /**     * 	Contrução da Grid de Dados     * 	@param Opcoes Array - Indica se cada item deve ser habilitado     * 	@param QLinhas Inteiro - Número de Resultados por pagina     * 	@param PaginaAtual Inteiro - Número da Pagina Atual     * 	@param Parametros String - Query String com parametros nescessarios      * 	@return String     */    public function montaGridPadrao()    {        //Recupera Valores        $sql = parent::getSql();        $tabelaMestra = parent::getTabelaMestra();        $sqlContador = parent::getSqlContador();        $filtroAtivo = parent::getFiltroAtivo();        $limitAtivo = parent::getLimitAtivo();        $listados = parent::getListados();        $chave = parent::getChave();        $formatarComo = parent::getFormatarComo();        $selecao = parent::getSelecao();        $qLinhas = parent::getQLinhas();        //Verifica se o SQL não esta Vazio        if (empty($sql)) {            throw new \Exception("Valor selecionado inválido!");        }        //Se Formatações existem, intancie funções de Validação        if (!empty($formatarComo)) {            $fPHP = new \Zion\Validacao\Valida();        }        //Iniciando valores        $i = 0;        $html = "";        //Monta Paginanação        if ($qLinhas > 0) {            //Setando Valores para paginação            $this->paginacao->setSql($sql);            $this->paginacao->setTabelaMestra($tabelaMestra);            $this->paginacao->setSqlContador($sqlContador);            $this->paginacao->setFiltroAtivo($filtroAtivo);            $this->paginacao->setLimitAtivo($limitAtivo);            $this->paginacao->setChave($chave);            $this->paginacao->setQLinhas($qLinhas);            $this->paginacao->setPaginaAtual(parent::getPaginaAtual());            $this->paginacao->setTipoOrdenacao(parent::getTipoOrdenacao());            $this->paginacao->setQuemOrdena(parent::getQuemOrdena());            $this->paginacao->setMetodoFiltra(parent::getMetodoFiltra());            $this->paginacao->setAlterarLinhas(parent::getAlterarLinhas());            $rs = $this->paginacao->rsPaginado();        } else {            $rs = $this->con->executar($sql);        }        $nLinhas = $this->con->nLinhas($rs);        //Contruindo grid        if ($nLinhas > 0) {            //$html .= '<!-- starts: sisContainerGrid (opened by class GridBotoes) -->';            //$html .= $this->html->abreTagAberta('div', ['id' => 'sisContainerGrid']);                     $html .= $this->html->abreTagAberta('div', ['id' => 'sisContainerPaginacao', 'class' => 'clearfix']);            $html .= $this->paginacao->listaResultados();            $html .= $this->html->fechaTag('div');                                    $html .= $this->html->abreTagAberta('div', ['id' => 'sisGridControl']);            $html .= $this->html->abreTagAberta('div', ['class' => 'table-primary']);            $html .= $this->html->abreTagAberta('div', ['id' => 'box-filters', 'class' => 'hidden marI10px recS20px recI20px recE20px recI20px']);            $html .= 'Aqui vem os filtros...';            $html .= '<a nohref onclick="hiddenFilters()" class="close" title="Fechar">&times;</a>';            $html .= $this->html->fechaTag('div');            $html .= $this->html->abreTagAberta('table', ['class' => 'table table-bordered']);            //Objeto de Converssão (Objeto Pastor :D)            $objC = $this->getObjetoConverte();            //Estilos de um resultado (ESTILO DE RESULTADO ÚNICO)            $eRU = $this->getCondicaoResultadoUnico();            $eTR = $this->getCondicaoTodosResultados();            while ($linha = $rs->fetch_array()) {                $i += 1;                $cRT = "";                if (is_array($eTR)) {                    foreach ($eTR as $rT) {                        if ($this->resultadoEval($linha, array($rT[0])) === true) {                            $cRT = $rT[1];                        }                    }                }                if ($i == 1) {                    $html .= $this->tituloGridPadrao();                }                $html .= $this->html->abreTagAberta('tbody');                $html .= $this->html->abreTagAberta('tr');                if ($selecao === true) {                    $html .= $this->thCheck($linha[$chave]);                }                foreach ($listados as $value) {                    //Valor com possivel converssão                    if (in_array($value, $objC)) {                        $valorItem = $this->converteValor($linha, $objC[$value]);                    } else {                        $valorItem = $linha[$value];                    }                    //Estilo de Resultado Único                    $cRU = "";                    if(in_array($value, $eRU)){                        if ($this->resultadoEval($linha, $eRU[$value]) === true) {                            $cRU = $eRU[$value][1];                        }                    }                    //Formatação                    if (!empty($formatarComo)) {                        if(in_array($value, $formatarComo)){                            $como = strtoupper($formatarComo[$value]);                            switch ($como) {                                case "DATA" : $valorItem = $fPHP->data()->converteData($valorItem);                                    break;                                case "DATAHORA": $valorItem = $fPHP->data()->converteDataHora($valorItem);                                    break;                                case "NUMERO" : $valorItem = $fPHP->numero()->floatCliente($valorItem);                                    break;                                case "MOEDA" : $valorItem = $fPHP->numero()->moedaCliente($valorItem);                                    break;                            }                        }                    }                    //Alinhamento                    $classAlinha = parent::getAlinhamento($value);                    $html .= $this->html->abreTagAberta('td', ['class' => $classAlinha]);                    $html .= ($valorItem == "") ? "&nbsp;" : $valorItem;                    $html .= $this->html->fechaTag('td');                }                $html .= $this->html->fechaTag('tr');                $html .= $this->html->fechaTag('tbody');            }            $html .= $this->html->fechaTag('table');            $html .= $this->html->fechaTag('div');            //$html .= '<!-- ends: grid-control -->';            $html .= $this->html->fechaTag('div');            //$html .= $this->html->fechaTag('div');            //$html .= '<!-- ends: sisContainerGrid (opened by class GridBotoes) -->';                    } else {            return '<div class="table-footer alinD"><em>Nenhum resultado encontrado.</em></div>';        }        return $html . $this->paginacao->getResultado();    }    public function thCheck($cod)    {        if (parent::getSelecaoMultipla() === true) {            $type = 'checkbox';            $name = 'sisReg';        } else {            $type = 'radio';            $name = 'sisReg[]';        }        $id = 'sisReg' . $cod;        return $this->html->abreTagAberta('td', ['class' => 'l45px']) .                $this->html->abreTagAberta('label', ['class' => 'px-single recE5px recS5px recD5px']) .                $this->html->abreTagAberta('input', ['type' => $type, 'class' => 'px', 'name' => $name, 'id' => $id, 'value' => $cod]) .                $this->html->abreTagFechada('span', ['class' => 'lbl']) .                $this->html->fechaTag('label') .                $this->html->fechaTag('td');    }}=======<?php/** *   @copyright CenterSis - Sistemas Para Internet *   @author Pablo Vanni - pablovanni@gmail.com *   @since 18/11/2005 *   Última Atualização: 13/10/2014 *   Autualizada Por: Pablo Vanni - pablovanni@gmail.com *   @name Cria uma grid de resultados com paginação */namespace Pixel\Grid;class GridPadrao extends Grid{    private $con;    private $paginacao;    private $html;    private $botoes;    public function __construct($con = NULL)    {        parent::__construct();        $this->con = (!is_object($con)) ? \Zion\Banco\Conexao::conectar() : $this->con = $con;        $this->paginacao = new \Zion\Paginacao\Paginacao($con);        $this->html = new \Zion\Layout\Html();        $this->botoes = new GridBotoes();        //Padrões Iniciais        parent::setTipoOrdenacao(filter_input(INPUT_GET, 'to'));        parent::setQuemOrdena(filter_input(INPUT_GET, 'qo'));        parent::setPaginaAtual(filter_input(INPUT_GET, 'pa'));        parent::setQLinhas(SIS_LINHAS_GRID);    }    public function tituloGridPadrao()    {        $colunas = parent::getColunas();        $selecao = parent::getSelecao();        $html = $this->html->abreTagAberta('thead');        $html .= $this->html->abreTagAberta('tr');        if ($selecao === true) {            $html .= $this->html->abreTagFechada('th');        }        //Titulos        foreach ($colunas as $coluna => $titulo) {            $classAlinha = parent::getAlinhamento($coluna);            $html .= $this->html->abreTagAberta('th', ['class' => $classAlinha]);            $html .= parent::ordena($titulo, $coluna);            $html .= $this->html->fechaTag('th');        }        $html .= $this->html->fechaTag('tr');        $html .= $this->html->fechaTag('thead');        return $html;    }    /**     * 	Contrução da Grid de Dados     * 	@param Opcoes Array - Indica se cada item deve ser habilitado     * 	@param QLinhas Inteiro - Número de Resultados por pagina     * 	@param PaginaAtual Inteiro - Número da Pagina Atual     * 	@param Parametros String - Query String com parametros nescessarios      * 	@return String     */    public function montaGridPadrao()    {        //Recupera Valores        $sql = parent::getSql();        $tabelaMestra = parent::getTabelaMestra();        $sqlContador = parent::getSqlContador();        $filtroAtivo = parent::getFiltroAtivo();        $limitAtivo = parent::getLimitAtivo();        $listados = array_keys(parent::getColunas());        $chave = parent::getChave();        $formatarComo = parent::getFormatarComo();        $selecao = parent::getSelecao();        $qLinhas = parent::getQLinhas();        //Verifica se o SQL não esta Vazio        if (empty($sql)) {            throw new \Exception("Valor selecionado inválido!");        }        //Se Formatações existem, intancie funções de Validação        if (!empty($formatarComo)) {            $fPHP = new \Zion\Validacao\Valida();        }        //Iniciando valores        $i = 0;        $html = "";        //Monta Paginanação        if ($qLinhas > 0) {            //Setando Valores para paginação            $this->paginacao->setSql($sql);            $this->paginacao->setTabelaMestra($tabelaMestra);            $this->paginacao->setSqlContador($sqlContador);            $this->paginacao->setFiltroAtivo($filtroAtivo);            $this->paginacao->setLimitAtivo($limitAtivo);            $this->paginacao->setChave($chave);            $this->paginacao->setQLinhas($qLinhas);            $this->paginacao->setPaginaAtual(parent::getPaginaAtual());            $this->paginacao->setTipoOrdenacao(parent::getTipoOrdenacao());            $this->paginacao->setQuemOrdena(parent::getQuemOrdena());            $this->paginacao->setMetodoFiltra(parent::getMetodoFiltra());            $this->paginacao->setAlterarLinhas(parent::getAlterarLinhas());            $rs = $this->paginacao->rsPaginado();        } else {            $rs = $this->con->executar($sql);        }        $nLinhas = $this->con->nLinhas($rs);        //Contruindo grid        if ($nLinhas > 0) {            //$html .= '<!-- starts: sisContainerGrid (opened by class GridBotoes) -->';            //$html .= $this->html->abreTagAberta('div', ['id' => 'sisContainerGrid']);            $html .= $this->html->abreTagAberta('div', ['id' => 'sisContainerPaginacao']);            $html .= $this->paginacao->listaResultados();            $html .= $this->html->fechaTag('div');            $html .= $this->html->abreTagAberta('div', ['id' => 'sisGridControl']);            $html .= $this->html->abreTagAberta('div', ['class' => 'table-primary']);            $html .= $this->html->abreTagAberta('table', ['class' => 'table table-bordered']);            //Objeto de Converssão (Objeto Pastor :D)            $objC = $this->getObjetoConverte();            //Estilos de um resultado (ESTILO DE RESULTADO ÚNICO)            $eRU = $this->getCondicaoResultadoUnico();            $eTR = $this->getCondicaoTodosResultados();            while ($linha = $rs->fetch_array()) {                $i += 1;                $cRT = "";                if (is_array($eTR)) {                    foreach ($eTR as $rT) {                        if ($this->resultadoEval($linha, array($rT[0])) === true) {                            $cRT = $rT[1];                        }                    }                }                if ($i == 1) {                    $html .= $this->tituloGridPadrao();                }                $html .= $this->html->abreTagAberta('tbody');                $html .= $this->html->abreTagAberta('tr');                if ($selecao === true) {                    $html .= $this->thCheck($linha[$chave]);                }                foreach ($listados as $value) {                    //Valor com possivel converssão                    if (in_array($value, $objC)) {                        $valorItem = $this->converteValor($linha, $objC[$value]);                    } else {                        $valorItem = $linha[$value];                    }                    //Estilo de Resultado Único                    $cRU = "";                    if(in_array($value, $eRU)){                        if ($this->resultadoEval($linha, $eRU[$value]) === true) {                            $cRU = $eRU[$value][1];                        }                    }                    //Formatação                    if (!empty($formatarComo)) {                        if(in_array($value, $formatarComo)){                            $como = strtoupper($formatarComo[$value]);                            switch ($como) {                                case "DATA" : $valorItem = $fPHP->data()->converteData($valorItem);                                    break;                                case "DATAHORA": $valorItem = $fPHP->data()->converteDataHora($valorItem);                                    break;                                case "NUMERO" : $valorItem = $fPHP->numero()->floatCliente($valorItem);                                    break;                                case "MOEDA" : $valorItem = $fPHP->numero()->moedaCliente($valorItem);                                    break;                            }                        }                    }                    //Alinhamento                    $classAlinha = parent::getAlinhamento($value);                    $html .= $this->html->abreTagAberta('td', ['class' => $classAlinha]);                    $html .= ($valorItem == "") ? "&nbsp;" : $valorItem;                    $html .= $this->html->fechaTag('td');                }                $html .= $this->html->fechaTag('tr');                $html .= $this->html->fechaTag('tbody');            }            $html .= $this->html->fechaTag('table');            $html .= $this->html->fechaTag('div');            //$html .= '<!-- ends: grid-control -->';            $html .= $this->html->fechaTag('div');            //$html .= $this->html->fechaTag('div');            //$html .= '<!-- ends: sisContainerGrid (opened by class GridBotoes) -->';                    } else {            return '<div class="table-footer alinD"><em>Nenhum resultado encontrado.</em></div>';        }        return $html . $this->paginacao->getResultado();    }    public function thCheck($cod)    {        if (parent::getSelecaoMultipla() === true) {            $type = 'checkbox';            $name = 'sisReg';        } else {            $type = 'radio';            $name = 'sisReg[]';        }        $id = 'sisReg' . $cod;        return $this->html->abreTagAberta('td', ['class' => 'l45px']) .                $this->html->abreTagAberta('label', ['class' => 'px-single recE5px recS5px recD5px']) .                $this->html->abreTagAberta('input', ['type' => $type, 'class' => 'px', 'name' => $name, 'id' => $id, 'value' => $cod]) .                $this->html->abreTagFechada('span', ['class' => 'lbl']) .                $this->html->fechaTag('label') .                $this->html->fechaTag('td');    }}>>>>>>> 6c882550197d7baff4bce513ef924038aa2d69d4