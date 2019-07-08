<?phpnamespace Pixel\Arquivo;use Zion\Banco\Conexao;use Zion\Arquivo\ManipulaImagem;use Zion\Exception\ValidationException;use Zion\Exception\ErrorException;class ArquivoUpload extends ManipulaImagem{    public function __construct()    {        parent::__construct();    }    /**     * Realiza o upload de um ou mais arquivos para uma pasta padrão do sistema     * chamada Storage, cada arquivo é guardado dentro de uma pasta com o      * caminho que representa a data de Upload. Ex: um arquivo enviado no dia      * 13/01/2015 ficaria no seguinte diretorio:      * SIS_DIR_BASE . 'Storage/2015/01/13/nome_arquivo.sua_extensão     *      * @param \Pixel\Form\FormUpload $config     * @return void     */    public function sisUpload($config)    {        $con = Conexao::conectar();        if ($config->getDisabled() === true) {            return;        }        $uploadCodReferencia = $config->getCodigoReferencia();        $nomeImput = \str_replace('[]', '', $config->getNome());        $totalDoForm = $this->contaArquivos($nomeImput);        $upsPermitido = \ini_get("max_file_uploads");        $dimensoes = $config->getDimensoes();        $organogramaCod = $config->getOrganogramaCod() ? $config->getOrganogramaCod() : $_SESSION['organogramaCod'];                //Maximo de uploads ultrapassado - Existem servidorem que não retornan esse parametro        if ($totalDoForm > $upsPermitido and ! empty($upsPermitido)) {            throw new ErrorException("Seu servidor permite envio de no maximo " . $upsPermitido . " arquivos simultaneos, remova alguns arquivos e tente novamente.");        }        $ano = \date('Y');        $mes = \date('m');        $dia = \date('d');        $this->criaDiretorioStorage($ano, $mes, $dia, $dimensoes, $organogramaCod);        $moduloCod = $this->getModuloCod($config->getModulo());        $uploadNomeCampo = $config->getNomeCampo() ? $config->getNomeCampo() : $nomeImput;        if ($uploadCodReferencia) {            $qbTotalDoBanco = $con->qb();            $qbTotalDoBanco->select('COUNT(uploadCod)')                ->from('_upload', '')                ->where($qbTotalDoBanco->expr()->eq('moduloCod', $moduloCod))                ->andWhere($qbTotalDoBanco->expr()->eq('uploadCodReferencia', $uploadCodReferencia))                ->andWhere($qbTotalDoBanco->expr()->eq('uploadNomeCampo', $qbTotalDoBanco->expr()->literal($uploadNomeCampo)));            $totalDoBanco = $con->execRLinha($qbTotalDoBanco);            $removidos = \filter_input(\INPUT_POST, 'sisUR' . $nomeImput, \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);            $totalRemovidos = \count($removidos);            $totalArquivos = $totalDoForm + ($totalDoBanco - $totalRemovidos);            if (\is_array($removidos)) {                foreach ($removidos as $codigoARemover) {                    $this->removerArquivos($config, $uploadCodReferencia, $codigoARemover);                }            }        } else {            $totalArquivos = $totalDoForm;        }        $minimoArquivos = $config->getMinimoArquivos();        $maximoArquivos = $config->getMaximoArquivos();        if (!empty($minimoArquivos) and $totalArquivos < $minimoArquivos) {            throw new ValidationException("Você deve selecionar no minimo " . $minimoArquivos . " arquivo" . (($minimoArquivos > 1) ? "s." : "."));        }        if (!empty($maximoArquivos) and $totalArquivos > $maximoArquivos) {            throw new ValidationException("$totalArquivos Você deve selecionar no maximo " . $maximoArquivos . " arquivo" . (($maximoArquivos > 1) ? "s." : "."));        }        $tamanhoMaximo = $config->getTamanhoMaximoEmBytes();        $extensaoPermitida = $config->getExtensoesPermitidas();        $extensaoNaoPermitida = $config->getExtensoesNaoPermitidas();        $tratarComo = $config->getTratarComo();        if ($totalDoForm > 0) {            if (\is_array($_FILES[$nomeImput]['name'])) {                $dadosFiles = $_FILES[$nomeImput]['name'];            } else {                $dadosFiles = ['fake' => true]; //Upload Simples            }            foreach (\array_keys($dadosFiles) as $posicao) {                if ($posicao === 'fake') {                    $nomeOriginal = $_FILES[$nomeImput]['name'];                    $uploadMime = $_FILES[$nomeImput]['type'];                    $tamanhoEmBytes = $_FILES[$nomeImput]['size'];                    $origem = $_FILES[$nomeImput]['tmp_name'];                } else {                    $nomeOriginal = $_FILES[$nomeImput]['name'][$posicao];                    $uploadMime = $_FILES[$nomeImput]['type'][$posicao];                    $tamanhoEmBytes = $_FILES[$nomeImput]['size'][$posicao];                    $origem = $_FILES[$nomeImput]['tmp_name'][$posicao];                }                $extensao = \strtolower($this->extenssaoArquivo($nomeOriginal));                                //Crop sempre salva arquivo em formato png                if (is_array($config->getCrop())) {                                        $pedacos = explode('.', $nomeOriginal);                    if (count($pedacos) > 1) {                                                end($pedacos);                        $chave = key($pedacos);                        $pedacos[$chave] = 'png';                        reset($pedacos);                        $nomeOriginal = implode('.', $pedacos);                        $extensao = 'png';                    }                                                        }                if (\is_array($extensaoPermitida)) {                    $extensaoPermitida = \array_map(\strtolower, $extensaoPermitida);                    if (!\in_array($extensao, $extensaoPermitida)) {                        throw new ValidationException("Extensão para o arquivo " . $nomeOriginal . " não é permitida. Use somente:" . \implode(", ", $extensaoPermitida));                    }                }                if (\is_array($extensaoNaoPermitida)) {                    $extensaoNaoPermitida = \array_map(\strtolower, $extensaoNaoPermitida);                    if (\in_array($extensao, $extensaoNaoPermitida)) {                        throw new ValidationException("Extensão para o arquivo " . $nomeOriginal . " não é permitida.");                    }                }                if ($tamanhoMaximo and $tamanhoEmBytes > $tamanhoMaximo) {                    throw new ValidationException("O tamanho maximo de arquivo permitido é " . (($tamanhoMaximo / 1048576 * 100000) / 100000) . "MB");                }                //Grava na Tabela                           $qbInsert = $con->qb();                $qbInsert->insert('_upload')                        ->values([                            'organogramaCod' => '?',                            'moduloCod' => '?',                            'uploadCodReferencia' => '?',                            'uploadNomeCampo' => '?',                            'uploadNomeOriginal' => '?',                            'uploadDataCadastro' => '?',                            'uploadMime' => '?'                        ])                        ->setParameter(0, $organogramaCod, \PDO::PARAM_INT)                        ->setParameter(1, $moduloCod, \PDO::PARAM_INT)                        ->setParameter(2, $uploadCodReferencia, \PDO::PARAM_INT)                        ->setParameter(3, $uploadNomeCampo, \PDO::PARAM_STR)                        ->setParameter(4, $nomeOriginal, \PDO::PARAM_STR)                        ->setParameter(5, ($ano . '-' . $mes . '-' . $dia), \PDO::PARAM_STR)                        ->setParameter(6, $uploadMime, \PDO::PARAM_STR);                $con->executar($qbInsert);                $uID = $con->ultimoId();                //Definindo Hash                $hashA = \crypt($nomeOriginal, \mt_rand()) . \crypt($uID, \mt_rand()) . $uID;                //Remove barras do hash se existir                $hashC = \str_replace(['/', '\\', '.'], "9", $hashA) . '.' . $extensao;                //Atualiza Código Hash                $qbUpdate = $con->qb();                $qbUpdate->update('_upload')                    ->set('uploadNomeFisico', '?')                    ->setParameter(1, $hashC, \PDO::PARAM_STR)                    ->where($qbUpdate->expr()->eq('uploadCod', '?'))                    ->setParameter(2, $uID, \PDO::PARAM_INT);                $con->executar($qbUpdate);                //Setando Destino Arquivo                $destino = \SIS_DIR_BASE . 'Storage/' . $organogramaCod . '/' . $ano . '/' . $mes . '/' . $dia . '/' . $hashC;                if ($tratarComo === "IMAGEM") {                    $destino = \SIS_DIR_BASE . 'Storage/' . $organogramaCod . '/' . $ano . '/' . $mes . '/' . $dia . '/' . $hashC;                    if ($config->getCrop()) {                        $base64String = filter_input(INPUT_POST, 'sis_base64_crop_' . $config->getId());                        $this->uploadArquivoBase64($base64String, $destino);                    } else {                        $this->uploadArquivo($origem, $destino);                    }                    $origemOriginal = $destino;                    if (\is_array($dimensoes)) {                        foreach ($dimensoes as $pasta => $configDaPasta) {                            $destino = \SIS_DIR_BASE . 'Storage/' . $organogramaCod . '/' . $ano . '/' . $mes . '/' . $dia . '/' . $pasta . '/' . $hashC;                            $altura = \array_key_exists('altura', $configDaPasta) ? $configDaPasta['altura'] : 0;                            $largura = \array_key_exists('largura', $configDaPasta) ? $configDaPasta['largura'] : 0;                            $qualidade = \array_key_exists('qualidade', $configDaPasta) ? $configDaPasta['qualidade'] : 100;                            $this->uploadImagem($nomeOriginal, $origemOriginal, $destino, $altura, $largura, $qualidade);                        }                    }                } else {                    $this->uploadArquivo($origem, $destino);                }            }        }    }    protected function getModuloCod($modulo = '')    {        if (!$modulo) {            $modulo = \MODULO;        }        $con = Conexao::conectar();        $qbModulo = $con->qb();        $qbModulo->select('moduloCod')            ->from('_modulo', '')            ->where($qbModulo->expr()->eq('moduloNome', $qbModulo->expr()->literal($modulo)));        $moduloCod = $con->execRLinha($qbModulo);        if (!$moduloCod) {            throw new ValidationException('Módulo inválido! a definição atual é: "' . $modulo . '"');        }        return $moduloCod;    }    /**     * Este metodo remove apenas as ocorrenciar dos arquivos em banco de dados     * ignorando os arquivos físicos     *      * @param \Pixel\Form\FormUpload $config     * @param int $uploadCodReferencia     * @param int $uploadCod     * @return void     */    public function removerArquivos($config, $uploadCodReferencia, $uploadCod = 0)    {        $con = Conexao::conectar();        $qbSelect = $con->qb();        $moduloCod = $this->getModuloCod($config->getModulo());        $organogramaCod = $config->getOrganogramaCod() ? $config->getOrganogramaCod() : $_SESSION['organogramaCod'];        $qbSelect->select('uploadCod', 'uploadNomeFisico', 'uploadDataCadastro')                ->from('_upload', '')                ->where($qbSelect->expr()->eq('organogramaCod', ':organogramaCod'))                ->andWhere($qbSelect->expr()->eq('uploadCodReferencia', ':uploadCodReferencia'))                ->andWhere($qbSelect->expr()->eq('moduloCod', ':moduloCod'))                ->setParameters([                    'organogramaCod' => $organogramaCod,                    'uploadCodReferencia' => $uploadCodReferencia,                    'moduloCod' => $moduloCod        ]);        if ($uploadCod) {            $qbSelect->andWhere($qbSelect->expr()->eq('uploadCod', ':uploadCod'))                ->setParameter('uploadCod', $uploadCod, \PDO::PARAM_INT);        }        $rS = $con->executar($qbSelect);        $nL = $con->nLinhas($rS);        if ($nL < 1) {            return;        }        while ($dados = $rS->fetch()) {            //Remove do banco            $qbDelete = $con->qb();            $qbDelete->delete('_upload', '')                    ->where($qbDelete->expr()->eq('organogramaCod', '?'))                    ->setParameter(1, $organogramaCod, \PDO::PARAM_INT)                    ->andWhere($qbDelete->expr()->eq('uploadcodreferencia', '?'))                    ->setParameter(2, $uploadCodReferencia, \PDO::PARAM_INT)                    ->andWhere($qbDelete->expr()->eq('uploadcod', '?'))                    ->setParameter(3, $dados['uploadcod'], \PDO::PARAM_INT);            $con->executar($qbDelete);            //Diretorios            $diretorioBase = \SIS_DIR_BASE . 'Storage/' . $organogramaCod . '/' . \str_replace('-', '/', $dados['uploaddatacadastro']) . '/';            $diretorioDestino = $diretorioBase . $dados['uploadnomefisico'];            try {                $dimensoes = $config->getDimensoes();                $tratarComo = $config->getTratarComo();                if ($tratarComo === 'IMAGEM') {                    if (\is_array($dimensoes)) {                        foreach (\array_keys($dimensoes) as $pasta) {                            $diretorioPasta = $diretorioBase . $pasta . '/' . $dados['uploadnomefisico'];                            $this->removeArquivo($diretorioPasta);                        }                    }                }                $this->removeArquivo($diretorioDestino);            } catch (\Exception $e) {                //não faz nada.            }        }    }    /**     * Mostra os arquivos existentes no banco de dados     * @param int $nomeImput     * @param int $uploadCodReferencia     * @return string     */    public function visualizarArquivos($nomeImput, $uploadCodReferencia, $modulo = '')    {        $htmlArquivos = '';        if (\is_numeric($uploadCodReferencia)) {            $con = Conexao::conectar();            $moduloCod = $this->getModuloCod($modulo);            $qbSelect = $con->qb();            $qbSelect->select('uploadCod', 'uploadCodReferencia', 'uploadNomeOriginal', 'uploadDataCadastro')                ->from("_upload", '')                ->andWhere($qbSelect->expr()->eq('uploadCodReferencia', ':uploadCodReferencia'))                ->andWhere($qbSelect->expr()->eq('moduloCod', ':moduloCod'))                ->andWhere($qbSelect->expr()->eq('uploadNomeCampo', ':uploadNomeCampo'))                ->setParameters([                    'uploadCodReferencia' => $uploadCodReferencia,                    'moduloCod' => $moduloCod,                    'uploadNomeCampo' => $nomeImput            ]);            $rS = $con->executar($qbSelect);            $nR = $con->nLinhas($rS);            if ($nR > 0) {                $htmlArquivos.= '<div class="sisLabelMostraArquivos">Arquivos:</div>';                while ($dados = $rS->fetch()) {                    $htmlArquivos.= '<div class="labelMostraArquivos">';                    //sisUploadRemovido                    $htmlArquivos.= '<label><input name="sisUR' . $nomeImput . '[]" type="checkbox" value="' . $dados['uploadcod'] . '" /> Remover </label>';                    $htmlArquivos.= ' - <a target="_blank" href="' . SIS_URL_BASE_STORAGE . '?uid=' . $dados['uploadcod'] . '&modo=download" alt="' . $dados['uploadnomeoriginal'] . '" border="0" >' . $dados['uploadnomeoriginal'] . '</a>';                    $htmlArquivos.= '</div>';                }            }        }        return $htmlArquivos;    }    /**     * Cria um diretório com base no diretório base do sistema e nos parametros     * @param string $ano     * @param string $mes     * @param string $dia     */    public function criaDiretorioStorage($ano, $mes, $dia, $dimensoes, $organogramaCod)    {        if (!$organogramaCod) {            $organogramaCod = $_SESSION['organogramaCod'];        }        $this->criaDiretorio(\SIS_DIR_BASE . 'Storage/' . $organogramaCod, 0777);        $this->criaDiretorio(\SIS_DIR_BASE . 'Storage/' . $organogramaCod . '/' . $ano, 0777);        $this->criaDiretorio(\SIS_DIR_BASE . 'Storage/' . $organogramaCod . '/' . $ano . '/' . $mes, 0777);        $this->criaDiretorio(\SIS_DIR_BASE . 'Storage/' . $organogramaCod . '/' . $ano . '/' . $mes . '/' . $dia, 0777);        if (\is_array($dimensoes)) {            foreach (\array_keys($dimensoes) as $nomePasta) {                $this->criaDiretorio(\SIS_DIR_BASE . 'Storage/' . $organogramaCod . '/' . $ano . '/' . $mes . '/' . $dia . '/' . $nomePasta, 0777);            }        }    }    /**     * Conta o número de arquivos contidos na variavel superglobal $_FILES     * @param string $nomeImput     * @return int     */    protected function contaArquivos($nomeImput)    {        $total = 0;        if (isset($_FILES[$nomeImput]['size'])) {            if (\is_array($_FILES[$nomeImput]['size'])) {                foreach ($_FILES[$nomeImput]['size'] as $size) {                    if ($size > 0) {                        $total ++;                    }                }            } else if ($_FILES[$nomeImput]['size'] > 0) {                $total = 1;            }        }        return $total;    }}