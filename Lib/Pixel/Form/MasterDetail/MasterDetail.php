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

namespace Pixel\Form\MasterDetail;

use Pixel\Form\MasterDetail\FormMasterDetail;
use Pixel\Crud\CrudUtil;
use Zion\Banco\Conexao;
use Zion\Validacao\Geral;

class MasterDetail
{

    public function gravar(FormMasterDetail $config)
    {
        $identifica = $config->getIdentifica();

        try {
            $this->validaDados($config);
        } catch (\Exception $ex) {
            throw new \Exception('MasterDetail: ' . $identifica . ' - ' . $ex->getMessage());
        }

        $nome = $config->getNome();

        $itens = \filter_input(\INPUT_POST, 'sisMasterDetailIten' . $nome, \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);
        $confHidden = \json_decode(\str_replace('\'', '"', \filter_input(\INPUT_POST, 'sisMasterDetailConf' . $nome, \FILTER_DEFAULT)));

        $doBanco = \explode(',', $confHidden->ativos);
        $ativos = [];

        foreach ($itens as $coringa) {

            if ($coringa == $confHidden->coringa) {
                continue;
            }

            if (\in_array($coringa, $doBanco)) {
                $ativos[] = $coringa;

                $this->update($config, $coringa);
            } else {
                $this->insert($config, $coringa);
            }
        }

        $aRemover = \array_diff($doBanco, $ativos);

        $this->removeItens($config, $aRemover);
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
        foreach ($campos as $campo => $objForm) {

            $objForm->setNome($campo);
            $objForm->setValor($objPai->retornaValor($campo . $coringa));
            $colunasCrud[] = $campo;
            $grupo[] = $objForm;
        }

        $objPai->processarForm($grupo);

        $objPai->validar();

        $crudUtil->update($tabela, $colunasCrud, $objPai, [$codigo => $coringa]);
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
        foreach ($campos as $campo => $objForm) {

            $objForm->setNome($campo);
            $objForm->setValor($objPai->retornaValor($campo . $coringa));
            $colunasCrud[] = $campo;
            $grupo[] = $objForm;
        }

        $objPai->processarForm($grupo);

        $objPai->validar();

        $colunasCrud[] = $campoReferencia;
        $objPai->set($campoReferencia, $codigoReferencia, 'numero');
        $crudUtil->insert($tabela, $colunasCrud, $objPai);
    }

    private function removeItens(FormMasterDetail $config, array $aRemover = [])
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
                ->setParameter(':cod', $codigoReferencia);
        $rs = $con->executar($qb);

        while ($dados = $rs->fetch()) {

            if (\in_array($dados[$codigo], $aRemover)) {

                if ($objetoRemover) {
                    $objetoRemover->{$metodoRemover}($dados[$codigo]);
                }

                $crudUtil->delete($tabela, [$codigo => $dados[$codigo]]);
            }
        }
    }

    private function validaDados(FormMasterDetail $config)
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

        $itens = \filter_input(\INPUT_POST, 'sisMasterDetailIten' . $nome, \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);
        $totalItens = \count($itens);

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
