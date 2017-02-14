<?php

namespace Pixel\Form\MasterDetail;

use Pixel\Form\MasterDetail\FormMasterDetail;
use Pixel\Crud\CrudUtil;
use Zion\Banco\Conexao;
use Zion\Validacao\Geral;
use Pixel\Arquivo\ArquivoUpload;

class MasterDetail
{

    private $dados;
    private $contaRepeticao;

    public function __construct()
    {
        $this->dados = [];
        $this->contaRepeticao = [];
    }

    /**
     * 
     * @param FormMasterDetail $config
     * @throws \Exception
     */
    public function gravar($config)
    {
        $identifica = $config->getIdentifica();

        if ($config->getIUpload()) {
            $upload = $config->getIUpload();
        } else {
            $upload = new ArquivoUpload();
        }

        $nome = $config->getNome();

        $itens = \filter_input(\INPUT_POST, 'sisMasterDetailIten' . $nome, \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);
        $confHidden = \json_decode(\str_replace('\'', '"', \filter_input(\INPUT_POST, 'sisMasterDetailConf' . $nome, \FILTER_DEFAULT)));

        try {
            $this->validaDados($config, $confHidden->coringa);
        } catch (\Exception $ex) {
            throw new \Exception('MasterDetail: ' . $identifica . ' - ' . $ex->getMessage());
        }

        if ($confHidden) {
            $doBanco = \explode(',', $confHidden->ativos);
        } else {
            $doBanco = [];
        }

        $ativos = [];

        $coringas = [];
        $coringasMaster = [];

        $this->dados = [];

        try {
            foreach ($itens as $coringa) {

                if ($coringa == $confHidden->coringa) {
                    continue;
                }

                if (\in_array($coringa, $doBanco)) {

                    $ativos[] = $coringa;
                    if ($config->getPermitirIgnorar() === false or \filter_input(\INPUT_POST, 'sisMA' . $nome . $coringa, \FILTER_DEFAULT) !== 'N') {                        
                        $coringasMaster[] = $coringa;
                        $coringas[] = $coringa;

                        $this->update($config, $coringa);
                    }
                } else {
                    $coringasMaster[] = $this->insert($config, $coringa);
                    $coringas[] = $coringa;
                }
            }
        } catch (\Exception $ex) {
            throw new \Exception('MasterDetail: ' . $identifica . ' - ' . $ex->getMessage());
        }


        $config->setDados($this->dados);

        $objPai = $config->getObjetoPai();

        $objetos = $objPai->getObjetos();

        $cont = 0;
        foreach ($objetos as $objeto) {
            if ($objeto->getTipoBase() === 'upload') {

                if ($cont === 0) {
                    $nomeOriginal = $objeto->getId();
                }

                $objeto->setNome($nomeOriginal . $coringas[$cont]);
                $objeto->setValor($nomeOriginal . $coringas[$cont]);
                $objeto->setId($nomeOriginal . $coringas[$cont]);
                $objeto->setNomeCampo($nomeOriginal . $coringasMaster[$cont]);

                $objeto->setCodigoReferencia($coringasMaster[$cont]);
                $upload->sisUpload($objeto);
                $cont++;
            }
        }

        $aRemover = \array_diff($doBanco, $ativos);

        try {
            $this->removeItens($config, $aRemover);
        } catch (\Exception $ex) {
            throw new \Exception('MasterDetail: ' . $identifica . ' - ' . $ex->getMessage());
        }
    }

    private function update($config, $coringa)
    {
        $crudUtil = new CrudUtil();

        $tabela = $config->getTabela();
        $codigo = $config->getCodigo();
        $campos = $config->getCampos();
        $objPai = $config->getObjetoPai();

        $colunasCrud = [];
        $grupo = [];
        $naoRepetir = $config->getNaoRepetir();

        foreach ($campos as $campo => $objForm) {

            $objForm->setNome($campo);
            $valorCampo = $objPai->retornaValor($campo . $coringa);
            $objForm->setValor($valorCampo);

            if ($naoRepetir and \in_array($campo, $naoRepetir)) {

                if (\array_key_exists($campo, $this->contaRepeticao)) {

                    if (\in_array($valorCampo, $this->contaRepeticao[$campo])) {
                        throw new \Exception($objForm->getIdentifica() . ' - não pode ser repetido!');
                    }

                    $this->contaRepeticao[$campo][] = $valorCampo;
                } else {
                    $this->contaRepeticao[$campo][] = $valorCampo;
                }
            }

            $this->dados[$coringa][$campo] = $valorCampo;
            $this->dados[$coringa]['_cod'] = $coringa;

            if ($objForm->getTipoBase() === 'upload') {
                $objForm->setNome($objForm->getNome() . $coringa);
            } else {
                $colunasCrud[] = $campo;
            }

            $grupo[] = $objForm;
        }

        $objPai->processarForm($grupo);

        $objPai->validar();

        if ($config->getGravar()) {
            $crudUtil->update($tabela, $colunasCrud, $objPai, [$codigo => $coringa], [], ['upload']);
        }
    }

    private function insert($config, $coringa)
    {
        $crudUtil = new CrudUtil();

        $tabela = $config->getTabela();
        $campoReferencia = $config->getCampoReferencia();
        $codigoReferencia = $config->getCodigoReferencia();

        $campos = $config->getCampos();
        $objPai = $config->getObjetoPai();

        $colunasCrud = [];
        $grupo = [];
        $naoRepetir = $config->getNaoRepetir();

        foreach ($campos as $campo => $objForm) {

            $objForm->setNome($campo);

            if (\substr_count($campo, '[]') > 0) {
                $valorCampo = (array) $objPai->retornaValor($campo . $coringa . '[]');
            } else {
                $valorCampo = $objPai->retornaValor($campo . $coringa);
            }

            $objForm->setValor($valorCampo);

            if ($naoRepetir and \in_array($campo, $naoRepetir)) {

                if (\array_key_exists($campo, $this->contaRepeticao)) {

                    if (\in_array($valorCampo, $this->contaRepeticao[$campo])) {
                        throw new \Exception($objForm->getIdentifica() . ' - não pode ser repetido!');
                    }

                    $this->contaRepeticao[$campo][] = $valorCampo;
                } else {
                    $this->contaRepeticao[$campo][] = $valorCampo;
                }
            }

            $this->dados[$coringa][$campo] = $valorCampo;

            if ($objForm->getTipoBase() === 'upload') {
                $objForm->setNome($objForm->getNome() . $coringa);
            } else {
                $colunasCrud[] = $campo;
            }

            $grupo[] = $objForm;
        }

        $objPai->processarForm($grupo);

        $objPai->validar();

        //Crud Extra
        $crudExtra = $config->getCrudExtra();

        if ($crudExtra) {
            foreach ($crudExtra as $confExtra) {
                $colunasCrud[] = $confExtra[0];
                $objPai->set($confExtra[0], $confExtra[1], $confExtra[2]);
            }
        }

        $colunasCrud[] = $campoReferencia;
        $objPai->set($campoReferencia, $codigoReferencia, 'texto');

        if ($config->getGravar()) {
            $codGravado = $crudUtil->insert($tabela, $colunasCrud, $objPai, ['upload']);
            $this->dados[$coringa]['_cod'] = $codGravado;
            return $codGravado;
        }
    }

    /**
     * 
     * @param FormMasterDetail $config
     * @param array $aRemover
     */
    private function removeItens($config, array $aRemover = [])
    {
        $con = Conexao::conectar();

        $crudUtil = new CrudUtil();

        $tabela = $config->getTabela();
        $codigo = $config->getCodigo();
        $campoReferencia = $config->getCampoReferencia();
        $codigoReferencia = $config->getCodigoReferencia();
        $objetoRemover = $config->getObjetoRemover();
        $metodoRemover = $config->getMetodoRemover();

        $qb = $con->qb();
        $qb->select($codigo)
            ->from($tabela, '')
            ->where($qb->expr()->eq($campoReferencia, ':cod'))
            ->setParameter(':cod', $codigoReferencia, 2);
        $rs = $con->executar($qb);

        while ($dados = $rs->fetch()) {

            if (\in_array($dados[$codigo], $aRemover)) {

                if ($objetoRemover) {
                    $objetoRemover->{$metodoRemover}($dados[$codigo]);
                }

                if ($config->getGravar()) {
                    $crudUtil->delete($tabela, [$codigo => $dados[$codigo]]);
                }
            }
        }
    }

    /**
     * 
     * @param FormMasterDetail $config
     * @param type $coringa
     * @throws \Exception
     */
    private function validaDados($config, $coringa)
    {
        $valida = Geral::instancia();

        $nome = $config->getNome();
        $addMax = $config->getAddMax();
        $addMin = $config->getAddMin();
        $tabela = $config->getTabela();
        $codigo = $config->getCodigo();
        $campos = $config->getCampos();
        $campoReferencia = $config->getCampoReferencia();
        $codigoReferencia = $config->getCodigoReferencia();
        $objetoRemover = $config->getObjetoRemover();
        $metodoRemover = $config->getMetodoRemover();

        if (empty($tabela)) {
            throw new \Exception('Tabela não informada!');
        }

        if (empty($codigo)) {
            throw new \Exception('Código da Tabela não informado!');
        }

        if (\count($campos) < 1) {
            throw new \Exception('Nenhum campo foi encontrado!');
        }

        if (empty($campoReferencia)) {
            throw new \Exception('Campo de referência deve ser informado!');
        }

        if (empty($codigoReferencia)) {
            throw new \Exception('Código de referência deve ser informado!');
        }

        if (!empty($objetoRemover)) {
            if (\is_object($objetoRemover)) {
                if (!\method_exists($objetoRemover, $metodoRemover)) {
                    throw new \Exception("MetodoRemover informado não foi encontrado no objeto (ObjetoRemover)!");
                }
            } else {
                throw new \Exception("ObjetoRemover informado não é um objeto válido!");
            }
        }

        $itens = (array) \filter_input(\INPUT_POST, 'sisMasterDetailIten' . $nome, \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);

        $totalItens = 0;

        foreach ($itens as $nomeCoringa) {
            if ($nomeCoringa !== $coringa) {
                $totalItens++;
            }
        }

        if (!$valida->validaJSON(\str_replace('\'', '"', \filter_input(\INPUT_POST, 'sisMasterDetailConf' . $nome, \FILTER_DEFAULT)))) {
            throw new \Exception('O sistema não conseguiu recuperar o array de configuração corretamente!');
        }

        if ($addMax > 0 and $totalItens > $addMax) {
            throw new \Exception('O número máximo de itens foi ultrapassado, adicione no máximo ' . $addMax . ' itens!');
        }

        if ($addMin > 0 and $totalItens < $addMin) {
            throw new \Exception('O número mínimo de itens não foi alcançado, adicione no mínimo ' . $addMin . ' itens!');
        }
    }

}
