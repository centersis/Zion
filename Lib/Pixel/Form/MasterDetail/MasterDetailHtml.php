<?php

/**
 *
 *    Sappiens Framework
 *    Copyright (C) 2014, BRA Consultoria
 *
 *    Website do autor: www.braconsultoria.com.br/sappiens
 *    Email do autor: sappiens@braconsultoria.com.br
 *
 *    Website do projeto, equipe e documentação: www.sappiens.com.br
 *   
 *    Este programa é software livre; você pode redistribuí-lo e/ou
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *    publicada pela Free Software Foundation, versão 2.
 *
 *    Este programa é distribuído na expectativa de ser útil, mas SEM
 *    QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *    detalhes.
 * 
 *    Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *    junto com este programa; se não, escreva para a Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *    02111-1307, USA.
 *
 *    Cópias da licença disponíveis em /Sappiens/_doc/licenca
 *
 */

namespace Pixel\Form\MasterDetail;

use Pixel\Form\MasterDetail\FormMasterDetail;
use Zion\Banco\Conexao;
use Pixel\Form\Form;
use Pixel\Form\FormPixelJavaScript;
use Pixel\Twig\Carregador;

class MasterDetailHtml
{

    private $buffer;

    public function __construct()
    {
        $this->buffer = [];

        $this->buffer['ativos'] = '';
    }

    public function montaMasterDetail(FormMasterDetail $config, $nomeForm)
    {
        $totalInicio = $config->getTotalItensInicio();
        $objPai = $config->getObjetoPai();
        $valorItensDeInicio = $config->getValorItensDeInicio();

        $this->buffer['id'] = $config->getNome();

        $acao = $objPai->getAcao();

        if ($acao == 'alterar') {

            $this->camposDoBanco($config, $nomeForm);
        } else {
            for ($i = 0; $i < $totalInicio; $i++) {

                $coringa = $this->coringa();

                $valoresInicio = \is_array($valorItensDeInicio[$i]) ? \array_change_key_case($valorItensDeInicio[$i]) : [];

                $this->montaGrupoDeCampos($config, $coringa, $nomeForm, $valoresInicio);
            }
        }

        $this->botaoAdd($config, $nomeForm, $this->buffer['ativos']);

        if ($config->getBotaoRemover()) {
            $this->buffer['botaoRemover'] = 'true';
        }

        $carregador = new Carregador();

        return $carregador->render('master_detail.html.twig', $this->buffer);
    }

    private function camposDoBanco(FormMasterDetail $config, $nomeForm)
    {
        $con = Conexao::conectar();

        $ativos = [];

        $tabela = $config->getTabela();
        $codigo = $config->getCodigo();
        $campoReferencia = $config->getCampoReferencia();
        $codigoReferencia = $config->getCodigoReferencia();
        $campos = $config->getCampos();
        $nomeCampos = \array_keys($campos);

        foreach ($nomeCampos as $chave => $nome) {
            if ($campos[$nome]->getTipoBase() === 'upload') {
                unset($nomeCampos[$chave]);
            }
        }

        if (!\in_array($codigo, $nomeCampos)) {

            $nomeCampos[] = $codigo;
        }

        $qb = $con->qb();
        $qb->select(\implode(',', $nomeCampos))
                ->from($tabela, '')
                ->where($qb->expr()->eq($campoReferencia, ':cod'))
                ->setParameter(':cod', $codigoReferencia);
        $rs = $con->executar($qb);

        while ($dados = $rs->fetch()) {

            $coringa = $dados[$codigo];
            $ativos[] = $coringa;
            $this->montaGrupoDeCampos($config, $coringa, $nomeForm, $dados);
        }

        $this->buffer['ativos'] = \implode(',', $ativos);
    }

    private function montaGrupoDeCampos($config, $coringa, $nomeForm, array $valores = [], $limpar = false)
    {
        $form = new Form();
        $pixelJs = new FormPixelJavaScript();

        $campos = $config->getCampos();

        $nomeOriginal = '';

        foreach ($campos as $nomeOriginal => $configuracao) {

            $arCampos = [];

            $novoNomeId = $nomeOriginal . $coringa;
            $nomeOriginalMinusculo = \strtolower($nomeOriginal);

            if (!empty($valores) and \array_key_exists($nomeOriginalMinusculo, $valores)) {

                $configuracao->setValor($valores[$nomeOriginalMinusculo]);
            }

            if ($limpar) {
                $valorPadrao = $configuracao->getValorPadrao();
                if ($valorPadrao) {
                    $configuracao->setValor($valorPadrao);
                } else {
                    $configuracao->setValor(\NULL);
                }
            }

            $arCampos[] = $configuracao->setNome($novoNomeId)->setId($novoNomeId);
            $form->processarForm($arCampos);
            $this->buffer['campos'][$coringa][$nomeOriginal] = $form->getFormHtml($arCampos[0]);

            $this->buffer['tipos'][$nomeOriginal] = $configuracao->getTipoBase();

            if (\method_exists($configuracao, 'getEmColunaDeTamanho')) {
                $this->buffer['emColunas'][$nomeOriginal] = $configuracao->getEmColunaDeTamanho();
            }

            if (\method_exists($configuracao, 'getIdentifica')) {
                $this->buffer['identifica'][$nomeOriginal] = $configuracao->getIdentifica();
            }

            if (\method_exists($configuracao, 'getLabelAntes') and $configuracao->getLabelAntes()) {
                $this->buffer['labelAntes'][$nomeOriginal] = $configuracao->getLabelAntes();
            }

            if (\method_exists($configuracao, 'getLabelAntes') and $configuracao->getLabelDepois()) {
                $this->buffer['labelAntes'][$nomeOriginal] = $configuracao->getLabelDepois();
            }

            if (\method_exists($configuracao, 'getIconFA') and $configuracao->getIconFA()) {
                $this->buffer['iconFA'][$nomeOriginal] = 'fa ' . $configuracao->getIconFA() . ' form-control-feedback';
            }

            $js = $pixelJs->getJsExtraObjeto($arCampos, $nomeForm);

            if ($js) {
                $this->buffer['javascript'][$coringa] = $js;
            }
        }
    }

    private function botaoAdd(FormMasterDetail $config, $nomeForm, $ativos)
    {
        $coringa = $this->coringa();

        $this->buffer['botaoAdd'] = $config->getAddTexto();

        $this->montaGrupoDeCampos($config, $coringa, $nomeForm, [], true);

        $this->buffer['config'] = ['addMax' => $config->getAddMax(), 'addMin' => $config->getAddMin(), 'botaoRemover' => $config->getBotaoRemover() ? 'true' : 'false', 'coringa' => $coringa, 'ativos' => $ativos];
    }

    private function coringa()
    {
        $letras = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return \substr(\str_shuffle($letras), 0, 5);
    }

}
