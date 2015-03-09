<?php/** * *    Sappiens Framework *    Copyright (C) 2014, BRA Consultoria * *    Website do autor: www.braconsultoria.com.br/sappiens *    Email do autor: sappiens@braconsultoria.com.br * *    Website do projeto, equipe e documentação: www.sappiens.com.br *    *    Este programa é software livre; você pode redistribuí-lo e/ou *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme *    publicada pela Free Software Foundation, versão 2. * *    Este programa é distribuído na expectativa de ser útil, mas SEM *    QUALQUER GARANTIA; sem mesmo a garantia implícita de *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais *    detalhes. *  *    Você deve ter recebido uma cópia da Licença Pública Geral GNU *    junto com este programa; se não, escreva para a Free Software *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA *    02111-1307, USA. * *    Cópias da licença disponíveis em /Sappiens/_doc/licenca * *//** * *   22/01/2015 - Vinícius Pozzebon *   Implementação do método getModuloCod() para capturar corretamente o uploadCod *   Ajustes nos demais métodos para considerem o getModuloCod() nas pesquisas de arquivos/imagens, etc * */namespace Pixel\Arquivo;class ArquivoUpload extends \Zion\Arquivo\ManipulaImagem{    /**     * Realiza o upload de um ou mais arquivos para uma pasta padrão do sistema     * chamada Storage, cada arquivo é guardado dentro de uma pasta com o      * caminho que representa a data de Upload. Ex: um arquivo enviado no dia      * 13/01/2015 ficaria no seguinte diretorio:      * SIS_DIR_BASE . 'Storage/2015/01/13/nome_arquivo.sua_extensão     *      * @param \Pixel\Form\FormUpload $config     * @return void     * @throws \Exception     */    public function sisUpload(\Pixel\Form\FormUpload $config)    {        $con = \Zion\Banco\Conexao::conectar();        if ($config->getDisabled() === true) {            return;        }        $uploadCodReferencia = $config->getCodigoReferencia();        $nomeCampo = \str_replace('[]', '', $config->getNome());        $totalDoForm = $this->contaArquivos($nomeCampo);        $upsPermitido = \ini_get("max_file_uploads");        //Maximo de uploads ultrapassado - Existem servidorem que não retornan esse parametro        if ($totalDoForm > $upsPermitido and ! empty($upsPermitido)) {            throw new \Exception("Seu servidor permite envio de no maximo " . $upsPermitido . " arquivos simultaneos, remova alguns arquivos e tente novamente.");        }        $ano = \date('Y');        $mes = \date('m');        $dia = \date('d');        $this->criaDiretorioStorage($ano, $mes, $dia);        /*          $qbModulo = $con->link()->createQueryBuilder();          $qbModulo->select('moduloCod')          ->from('_modulo', '')          ->where($qbModulo->expr()->eq('moduloNome', $qbModulo->expr()->literal(MODULO)));          $moduloCod = $con->execRLinha($qbModulo);         */        $moduloCod = $this->getModuloCod();        /*          if (!$moduloCod) {          throw new \Exception('Módulo inválido! a definição atual é: "' . MODULO . '"');          }         */        if ($uploadCodReferencia) {            $uploadNomeCampo = $nomeCampo;            $qbTotalDoBanco = $con->link()->createQueryBuilder();            $qbTotalDoBanco->select('COUNT(uploadCod)')                    ->from('_upload', '')                    ->where($qbTotalDoBanco->expr()->eq('moduloCod', $moduloCod))                    ->andWhere($qbTotalDoBanco->expr()->eq('uploadCodReferencia', $uploadCodReferencia))                    ->andWhere($qbTotalDoBanco->expr()->eq('uploadNomeCampo', $qbTotalDoBanco->expr()->literal($uploadNomeCampo)));            $totalDoBanco = $con->execRLinha($qbTotalDoBanco);            $removidos = \filter_input(\INPUT_POST, 'sisUR' . $nomeCampo, \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);            $totalRemovidos = \count($removidos);            $totalArquivos = $totalDoForm + ($totalDoBanco - $totalRemovidos);            if (\is_array($removidos)) {                foreach ($removidos as $codigoARemover) {                    $this->removerArquivos($config, $uploadCodReferencia, $codigoARemover);                }            }        } else {            $totalArquivos = $totalDoForm;        }        $minimoArquivos = $config->getMinimoArquivos();        $maximoArquivos = $config->getMaximoArquivos();        if (!empty($minimoArquivos) and $totalArquivos < $minimoArquivos) {            throw new \Exception("Você deve selecionar no minimo " . $minimoArquivos . " arquivo" . (($minimoArquivos > 1) ? "s." : "."));        }        if (!empty($maximoArquivos) and $totalArquivos > $maximoArquivos) {            throw new \Exception("$totalArquivos Você deve selecionar no maximo " . $maximoArquivos . " arquivo" . (($maximoArquivos > 1) ? "s." : "."));        }        $tamanhoMaximo = $config->getTamanhoMaximoEmBytes();        $extensaoPermitida = $config->getExtensoesPermitidas();        $extensaoNaoPermitida = $config->getExtensoesNaoPermitidas();        $alturaMaxima = $config->getAlturaMaxima();        $larguraMaxima = $config->getLarguraMaxima();        $tratarComo = $config->getTratarComo();        if ($totalDoForm > 0) {            if (\is_array($_FILES[$nomeCampo]['name'])) {                $dadosFiles = $_FILES[$nomeCampo]['name'];            } else {                $dadosFiles = ['fake' => true]; //Upload Simples            }            foreach (\array_keys($dadosFiles) as $posicao) {                if ($posicao === 'fake') {                    $nomeOriginal = $_FILES[$nomeCampo]['name'];                    $uploadMime = $_FILES[$nomeCampo]['type'];                    $tamanhoEmBytes = $_FILES[$nomeCampo]['size'];                    $origem = $_FILES[$nomeCampo]['tmp_name'];                } else {                    $nomeOriginal = $_FILES[$nomeCampo]['name'][$posicao];                    $uploadMime = $_FILES[$nomeCampo]['type'][$posicao];                    $tamanhoEmBytes = $_FILES[$nomeCampo]['size'][$posicao];                    $origem = $_FILES[$nomeCampo]['tmp_name'][$posicao];                }                $extensao = \strtolower($this->extenssaoArquivo($nomeOriginal));                if (\is_array($extensaoPermitida)) {                    $extensaoPermitida = \array_map(\strtolower, $extensaoPermitida);                    if (!\in_array($extensao, $extensaoPermitida)) {                        throw new \Exception("Extensão para o arquivo " . $nomeOriginal . " não é permitida. Use somente:" . \implode(", ", $extensaoPermitida));                    }                }                if (\is_array($extensaoNaoPermitida)) {                    $extensaoNaoPermitida = \array_map(\strtolower, $extensaoNaoPermitida);                    if (\in_array($extensao, $extensaoNaoPermitida)) {                        throw new \Exception("Extensão para o arquivo " . $nomeOriginal . " não é permitida.");                    }                }                if ($tamanhoMaximo and $tamanhoEmBytes > $tamanhoMaximo) {                    throw new \Exception("O tamanho maximo de arquivo permitido é " . (($tamanhoMaximo / 1048576 * 100000) / 100000) . "MB");                }                //Grava na Tabela                           $qbInsert = $con->link()->createQueryBuilder();                $qbInsert->insert('_upload')                        ->values([                            'organogramaCod'        => '?',                            'moduloCod'             => '?',                            'uploadCodReferencia'   => '?',                            'uploadNomeCampo'       => '?',                            'uploadNomeOriginal'    => '?',                            'uploadDataCadastro'    => '?',                            'uploadMime'            => '?'                            ])                        ->setParameter(0, $_SESSION['organogramaCod'], \PDO::PARAM_INT)                        ->setParameter(1, $moduloCod, \PDO::PARAM_INT)                        ->setParameter(2, $uploadCodReferencia, \PDO::PARAM_INT)                        ->setParameter(3, $nomeCampo, \PDO::PARAM_STR)                        ->setParameter(4, $nomeOriginal, \PDO::PARAM_STR)                        ->setParameter(5, ($ano . '-' . $mes . '-' . $dia), \PDO::PARAM_STR)                        ->setParameter(6, $uploadMime, \PDO::PARAM_STR);                $con->executar($qbInsert);                $uID = $con->ultimoId();                //Definindo Hash                $hashA = \crypt($nomeOriginal, \mt_rand()) . \crypt($uID, \mt_rand()) . $uID;                //Remove barras do hash se existir                $hashC = \str_replace(['/', '\\', '.'], "9", $hashA) . '.' . $extensao;                //Atualiza Código Hash                $qbUpdate = $con->link()->createQueryBuilder();                $qbUpdate->update('_upload')                        ->set('uploadNomeFisico', '?')                        ->setParameter(1, $hashC, \PDO::PARAM_STR)                        ->where($qbUpdate->expr()->eq('uploadCod', '?'))                        ->setParameter(2, $uID, \PDO::PARAM_INT);                $con->executar($qbUpdate);                //Setando Destino                $destino = SIS_DIR_BASE . 'Storage/' . $ano . '/' . $mes . '/' . $dia . '/' . $hashC;                if ($tratarComo === "IMAGEM") {                    $this->uploadImagem($nomeOriginal, $origem, $destino, $alturaMaxima, $larguraMaxima);                    //Grava TB                    if ($config->getThumbnail() === true) {                        $alturaMaximaTB = $config->getAlturaMaximaTB();                        $larguraMaximaTB = $config->getLarguraMaximaTB();                        $destinoTB = SIS_DIR_BASE . 'Storage/' . $ano . '/' . $mes . '/' . $dia . '/tb/' . $hashC;                        $this->uploadImagem($nomeOriginal, $destino, $destinoTB, $alturaMaximaTB, $larguraMaximaTB);                    }                } else {                    $this->uploadArquivo($origem, $destino);                }            }        }    }    private function getModuloCod($modulo = '')    {        if(!$modulo){            $modulo = MODULO;        }                $con = \Zion\Banco\Conexao::conectar();        $qbModulo = $con->link()->createQueryBuilder();        $qbModulo->select('moduloCod')                ->from('_modulo', '')                ->where($qbModulo->expr()->eq('moduloNome', $qbModulo->expr()->literal($modulo)));        $moduloCod = $con->execRLinha($qbModulo);        if (!$moduloCod) {            throw new \Exception('Módulo inválido! a definição atual é: "' . $modulo . '"');        }        return $moduloCod;    }    /**     * Este metodo remove apenas as ocorrenciar dos arquivos em banco de dados     * ignorando os arquivos físicos     *      * @param \Pixel\Form\FormUpload $config     * @param int $uploadCodReferencia     * @param int $uploadCod     * @return void     */    public function removerArquivos(\Pixel\Form\FormUpload $config, $uploadCodReferencia, $uploadCod = 0)    {        $con = \Zion\Banco\Conexao::conectar();        $qbSelect = $con->link()->createQueryBuilder();        /*          $qbSelect->select('uploadCod', 'uploadNomeFisico', 'uploadDataCadastro')          ->from('_upload', '')          ->where($qbSelect->expr()->eq('uploadCodReferencia', '?'))          ->setParameter(1, $uploadCodReferencia, \PDO::PARAM_INT);         */        $moduloCod = $this->getModuloCod();        $qbSelect->select('uploadCod', 'uploadNomeFisico', 'uploadDataCadastro')                 ->from('_upload')                 ->where($qbSelect->expr()->eq('organogramaCod', ':organogramaCod'))                 ->andWhere($qbSelect->expr()->eq('uploadCodReferencia', ':uploadCodReferencia'))                 ->andWhere($qbSelect->expr()->eq('moduloCod', ':moduloCod'))                 ->setParameters([                     'organogramaCod'       => $_SESSION['organogramaCod'],                     'uploadCodReferencia'  => $uploadCodReferencia,                      'moduloCod'            => $moduloCod                         ]);        if ($uploadCod) {            $qbSelect->andWhere($qbSelect->expr()->eq('uploadCod', ':uploadCod'))                    ->setParameter('uploadCod', $uploadCod, \PDO::PARAM_INT);        }        $rS = $con->executar($qbSelect);        $nL = $con->nLinhas($rS);        if ($nL < 1) {            return;        }        while ($dados = $rS->fetch()) {            //Remove do banco            $qbDelete = $con->link()->createQueryBuilder();            $qbDelete->delete('_upload', '')                    ->where($qbDelete->expr()->eq('organogramaCod', '?'))                    ->setParameter(1, $_SESSION['organogramaCod'], \PDO::PARAM_INT)                                        ->andWhere($qbDelete->expr()->eq('uploadcodreferencia', '?'))                    ->setParameter(2, $uploadCodReferencia, \PDO::PARAM_INT)                    ->andWhere($qbDelete->expr()->eq('uploadcod', '?'))                    ->setParameter(3, $dados['uploadcod'], \PDO::PARAM_INT);            $con->executar($qbDelete);            //Diretorios            $diretorioBase = SIS_DIR_BASE . 'Storage/' . \str_replace('-', '/', $dados['uploaddatacadastro']) . '/';            $diretorioDestino = $diretorioBase . $dados['uploadnomefisico'];            try {                //Tenta Remover do servidor                $this->removeArquivo($diretorioDestino);                if ($config->getThumbnail() === true) {                    $diretorioDestinoTB = $diretorioBase . 'tb/' . $dados['uploadnomefisico'];                    $this->removeArquivo($diretorioDestinoTB);                }            } catch (\Exception $e) {                //não faz nada.            }        }    }    /**     * Mostra os arquivos existentes no banco de dados     * @param int $nomeCampo     * @param int $uploadCodReferencia     * @return string     */    public function visualizarArquivos($nomeCampo, $uploadCodReferencia)    {        $htmlArquivos = '';        if (\is_numeric($uploadCodReferencia)) {            $con = \Zion\Banco\Conexao::conectar();            $moduloCod = $this->getModuloCod();            $qbSelect = $con->link()->createQueryBuilder();            $qbSelect->select('uploadCod', 'uploadCodReferencia', 'uploadNomeOriginal', 'uploadDataCadastro')                    ->from("_upload")                    ->where($qbSelect->expr()->eq('organogramaCod', ':organogramaCod'))                    ->andWhere($qbSelect->expr()->eq('uploadCodReferencia', ':uploadCodReferencia'))                    ->andWhere($qbSelect->expr()->eq('moduloCod', ':moduloCod'))                    ->setParameters([                        'organogramaCod'        => $_SESSION['organogramaCod'],                        'uploadCodReferencia'   => $uploadCodReferencia,                         'moduloCod'             => $moduloCod                            ]);            $rS = $con->executar($qbSelect);            $nR = $con->nLinhas($rS);            if ($nR > 0) {                $htmlArquivos.= '<div class="">Arquivos:</div>';                while ($dados = $rS->fetch()) {                    $htmlArquivos.= '<div class="">';                    //sisUploadRemovido                    $htmlArquivos.= '<label><input name="sisUR' . $nomeCampo . '[]" type="checkbox" value="' . $dados['uploadcod'] . '" /> Remover </label>';                    $htmlArquivos.= ' - <a href="' . SIS_URL_BASE . 'Storage/ArquivoDownload.php?uploadCod=' . $dados['uploadcod'] . '&modo=download" alt="' . $dados['uploadnomeoriginal'] . '" border="0" >' . $dados['uploadnomeoriginal'] . '</a>';                    $htmlArquivos.= '</div>';                }            }        }        return $htmlArquivos;    }    /**     * Cria um diretório com base no diretório base do sistema e nos parametros     * @param string $ano     * @param string $mes     * @param string $dia     */    public function criaDiretorioStorage($ano, $mes, $dia)    {        $this->criaDiretorio(SIS_DIR_BASE . 'Storage/' . $ano, 0777);        $this->criaDiretorio(SIS_DIR_BASE . 'Storage/' . $ano . '/' . $mes, 0777);        $this->criaDiretorio(SIS_DIR_BASE . 'Storage/' . $ano . '/' . $mes . '/' . $dia, 0777);        $this->criaDiretorio(SIS_DIR_BASE . 'Storage/' . $ano . '/' . $mes . '/' . $dia . '/tb', 0777);    }    /**     * Conta o numero de arquivos contidos na variavel superglobal $_FILES     * @param string $nomeCampo     * @return int     */    private function contaArquivos($nomeCampo)    {        if (empty($_FILES[$nomeCampo]['name'][0])) {            return 0;        } else {            return \count($_FILES[$nomeCampo]['name']);        }    }}