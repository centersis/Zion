<?php

/**
 * @author Pablo Vanni
 */

namespace Pixel\Form\MasterDetail;

class MasterDetailHtml
{

    private $html;

    public function __construct()
    {
        $this->html = new \Zion\Layout\Html();
    }

    public function montaMasterDetail(\Pixel\Form\MasterDetail\FormMasterDetail $config, $nomeForm)
    {
        $totalInicio = $config->getTotalItensInicio();
        $js = new \Zion\Layout\JavaScript();
        $objPai = $config->getObjetoPai();

        $textoJs = '';
        $html = '';
        $ativos = '';

        $acao = $objPai->getAcao();

        if ($acao == 'alterar') {

            $dadosGrupo = $this->camposDoBanco($config, $nomeForm);

            $html .= $dadosGrupo['html'];
            $textoJs = $dadosGrupo['js'];
            $ativos = $dadosGrupo['ativos'];
        } else {
            for ($i = 0; $i < $totalInicio; $i++) {

                $coringa = $this->coringa();

                $html.= $this->abreItem($config, $coringa);

                $dadosGrupo = $this->montaGrupoDeCampos($config, $coringa, $nomeForm);

                $html .= $dadosGrupo['html'];
                $textoJs .=$dadosGrupo['js'];

                $html .= $this->fechaItem($config, $coringa);
            }
        }

        $buffer = $this->abreGrupo($config);
        $buffer .= $this->botaoAdd($config, $nomeForm, $ativos);
        $buffer .= $html;
        $buffer .= $this->fechaGrupo($config);
        $buffer .= $js->entreJS($js->abreLoadJQuery() . $textoJs . $js->fechaLoadJQuery());

        return $buffer;
    }

    private function camposDoBanco(\Pixel\Form\MasterDetail\FormMasterDetail $config, $nomeForm)
    {
        $con = \Zion\Banco\Conexao::conectar();

        $tabela = $config->getTabela();
        $codigo = $config->getCodigo();
        $campoReferencia = $config->getCampoReferencia();
        $codigoReferencia = $config->getCodigoReferencia();
        $campos = $config->getCampos();
        $nomeCampos = \array_keys($campos);

        if (!\in_array($codigo, $nomeCampos)) {
            $nomeCampos[] = $codigo;
        }

        $qb = $con->link()->createQueryBuilder();
        $qb->select(\implode(',', $nomeCampos))
                ->from($tabela, '')
                ->where($qb->expr()->eq($campoReferencia, ':cod'))
                ->setParameter(':cod', $codigoReferencia);
        $rs = $con->executar($qb);

        $bufferJS = '';
        $buffer = '';
        $ativos = [];
        while ($dados = $rs->fetch()) {

            $coringa = $dados[$codigo];
            $ativos[] = $coringa;
            $buffer.= $this->abreItem($config, $coringa);
            $dadosGrupo = $this->montaGrupoDeCampos($config, $coringa, $nomeForm, $dados);
            $buffer .= $dadosGrupo['html'];
            $bufferJS .=$dadosGrupo['js'];

            $buffer .= $this->fechaItem($config, $coringa);
        }

        return ['html' => $buffer, 'js' => $bufferJS, 'ativos' => \implode(',', $ativos)];
    }

    private function montaGrupoDeCampos($config, $coringa, $nomeForm, array $valores = [])
    {
        $form = new \Pixel\Form\Form();
        $pixelJs = new \Pixel\Form\FormPixelJavaScript();

        $campos = $config->getCampos();

        $htmlForm = '';
        $javaScript = '';
        $nomeOriginal = '';

        foreach ($campos as $nomeOriginal => $configuracao) {

            $arCampos = [];

            $novoNomeId = $nomeOriginal . $coringa;
            $nomeOriginalMinusculo = \strtolower($nomeOriginal);

            if (!empty($valores) and \array_key_exists($nomeOriginalMinusculo, $valores)) {

                $configuracao->setValor($valores[$nomeOriginalMinusculo]);
            }

            $arCampos[] = $configuracao->setNome($novoNomeId)->setId($novoNomeId);
            $form->processarForm($arCampos);
            $htmlForm .= $form->getFormHtml($arCampos[0]);
            $javaScript .= $pixelJs->getJsExtraObjeto($arCampos, $nomeForm);
        }

        return ['html' => $htmlForm, 'js' => $javaScript];
    }

    private function abreGrupo($config)
    {
        return $this->html->abreTagAberta('div', array('id' => 'sisMasterDetail' . $config->getNome(), 'class' => 'col-sm-12'));
    }

    private function fechaGrupo($config)
    {
        $buffer = $this->html->abreTagFechada('div', array('id' => 'sisMasterDetailAppend' . $config->getNome(), 'class' => 'col-sm-12'));
        $buffer .= $this->html->fechaTag('div');
        return $buffer;
    }

    private function abreItem($config, $cont)
    {
        $buffer = $this->html->abreTagAberta('div', array('id' => 'sisMasterDetailIten' . $config->getNome() . $cont, 'class' => 'col-sm-12'));

        $colunas = $config->getBotaoRemover() ? '11' : '12';

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'col-sm-' . $colunas));

        $buffer .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'sisMasterDetailIten' . $config->getNome() . '[]', 'value' => $cont]);

        return $buffer;
    }

    private function fechaItem($config, $cont)
    {
        $buffer = $this->html->fechaTag('div');

        $buffer.= $this->botaoRemover($config, $cont);
        $buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

    private function botaoAdd(\Pixel\Form\MasterDetail\FormMasterDetail $config, $nomeForm, $ativos)
    {
        $js = new \Zion\Layout\JavaScript();

        $coringa = $this->coringa();

        $dadosGrupo = $this->montaGrupoDeCampos($config, $coringa, $nomeForm);

        $htmlModelo = $this->abreItem($config, $coringa) . $dadosGrupo['html'] . $this->fechaItem($config, $coringa);
        $jsModelo = $js->abreLoadJQuery() . $dadosGrupo['js'] . $js->fechaLoadJQuery();
        $dadosConfig = ['addMax' => $config->getAddMax(), 'addMin' => $config->getAddMin(), 'botaoRemover' => $config->getBotaoRemover(), 'coringa' => $coringa, 'ativos' => $ativos];
        $nameId = 'sisMasterDetailConf' . $config->getNome();


        $buffer = $this->html->abreTagAberta('div', array('class' => 'col-sm-12'));
        $buffer .= $this->html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn btn-lg', 'onclick' => 'sisAddMasterDetail(\'' . $config->getNome() . '\')']);
        $buffer .= $this->html->abreTagFechada('i', ['class' => 'fa fa-plus']);
        $buffer .= $config->getAddTexto();
        $buffer .= $this->html->fechaTag('button');
        $buffer .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => $nameId, 'id' => $nameId, 'value' => \str_replace('"', "'", \json_encode($dadosConfig))]);
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('div', array('id' => 'sisMasterDetailModeloHtml' . $config->getNome(), 'style' => 'display:none'));
        $buffer .= $htmlModelo;
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('div', array('id' => 'sisMasterDetailModeloJS' . $config->getNome(), 'style' => 'display:none'));
        $buffer .= $jsModelo;
        $buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

    private function botaoRemover($config, $id)
    {
        if (!$config->getBotaoRemover()) {
            return '';
        }

        $buffer = $this->html->abreTagAberta('div', array('id' => 'sisMasterDetail' . $config->getNome() . $id, 'class' => 'col-sm-1'));
        $buffer .= $this->html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn btn-lg', 'onclick' => 'sisRemoverMasterDetail(\'' . $config->getNome() . '\',\'' . $id . '\')']);
        $buffer .= $this->html->abreTagFechada('i', ['class' => 'fa fa-minus']);
        $buffer .= 'Remover';
        $buffer .= $this->html->fechaTag('button');
        $buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

    private function coringa()
    {
        $letras = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return \substr(\str_shuffle($letras), 0, 5);
    }

}
