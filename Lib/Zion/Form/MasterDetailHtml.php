<?php
namespace Zion\Form;

use Pixel\Form\Form;
use Zion\Layout\Html;
use Pixel\Form\FormMasterDetail;

class MasterDetailHtml
{

    private $html;

    public function __construct()
    {
        $this->html = new Html();
    }

    /**
     * 
     * @param FormMasterDetail $config
     * @return string
     */
    public function montaMasterDetail($config)
    {
        $totalInicio = $config->getTotalItensInicio();

        $buffer = $this->abreGrupo($config);
        $buffer.= $this->botaoAdd($config);

        for ($i = 0; $i < $totalInicio; $i++) {

            $cont = $this->coringa();

            $buffer.= $this->abreItem($config, $cont);

            $buffer.= $this->montaGrupoDeCampos($config, $cont);

            $buffer .= $this->fechaItem($config, $cont);
        }

        $buffer .= $this->fechaGrupo($config);

        return $buffer;
    }

    private function montaGrupoDeCampos($config, $cont)
    {
        $form = new Form();

        $campos = $config->getCampos();

        $htmlForm = '';
        foreach ($campos as $configuracao) {
            $arCampos = [];

            $novoNomeId = $config->getNome() . $cont;

            $arCampos[] = $configuracao->setNome($novoNomeId)->setId($novoNomeId);
            $form->processarForm($arCampos);
            $htmlForm .= $form->getFormHtml($arCampos[0]);
            $form->javaScript(false, true);
        }

        return $htmlForm;
    }

    private function abreGrupo($config)
    {
        $buffer = $this->html->abreTagAberta('div', array('id' => 'sisMasterDetail' . $config->getNome(), 'class' => 'col-sm-12'));
        $buffer .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'sisMasterDetailGrupo[]', 'value' => $config->getNome()]);

        return $buffer;
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

    private function botaoAdd(FormMasterDetail $config)
    {
        $coringa = $this->coringa();
        $htmlModelo = $this->abreItem($config, $coringa) . $this->montaGrupoDeCampos($config, $coringa) . $this->fechaItem($config, $coringa);
        $dadosConfig = ['addMax' => $config->getAddMax(), 'addMin' => $config->getAddMin(), 'botaoRemover' => $config->getBotaoRemover(), 'coringa' => $coringa];
        $nameId = 'sisMasterDetailConf' . $config->getNome();


        $buffer = $this->html->abreTagAberta('div', array('class' => 'col-sm-12'));
        $buffer .= $this->html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn btn-lg', 'onclick' => 'sisAddMasterDetail(\'' . $config->getNome() . '\')']);
        $buffer .= $this->html->abreTagFechada('i', ['class' => 'fa fa-plus']);
        $buffer .= $config->getAddTexto();
        $buffer .= $this->html->fechaTag('button');
        $buffer .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => $nameId, 'id' => $nameId, 'value' => \str_replace('"', "'", \json_encode($dadosConfig))]);
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('div', array('id' => 'sisMasterDetailModelo' . $config->getNome(), 'style' => 'display:none'));
        $buffer .= $htmlModelo;
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
        return \substr(\str_shuffle($letras), 0, 10);
    }

}
