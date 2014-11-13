<?php

namespace Pixel\Filtro;

class FiltroForm
{

    private $html;

    public function __construct()
    {
        $this->html = new \Zion\Layout\Html();
    }

    public function montaFiltro($objForm)
    {
        $template = new \Pixel\Template\Template();

        $html = $objForm->abreFormFiltro();
        
        $objFiltroDuploE = clone $objForm;
        $objFiltroDuploO = clone $objForm;

        $tabArray = [
            ['tabId' => 1,
                'onClick'=>'sisChangeFil(\'n\')',
                'tabActive' => 'active',
                'tabTitle' => 'Filtros especiais' .
                $template->getBadge(['id' => 'N', 'tipo' => 'danger'], 0),
                'tabContent' => $this->getFiltroNormal($objForm)
            ],
            ['tabId' => 2,
                'onClick'=>'sisChangeFil(\'e\')',
                'tabActive' => '',
                'tabTitle' => 'Filtros de operação ' .
                $template->getLabel(['id' => 'tabEQUE', 'tipo' => 'warning'], "E QUE") .
                $template->getBadge(['id' => 'E', 'tipo' => 'danger'], 0),
                'tabContent' => $this->getFiltroDuplo($objFiltroDuploE, 'e')
            ],
            ['tabId' => 3,
                'onClick'=>'sisChangeFil(\'o\')',
                'tabActive' => '',
                'tabTitle' => 'Filtros de operação ' .
                $template->getLabel(['id' => 'tabOUQUE', 'tipo' => 'warning'], "OU QUE") .
                $template->getBadge(['id' => 'O', 'tipo' => 'danger'], 0),
                'tabContent' => $this->getFiltroDuplo($objFiltroDuploO, 'o')
            ]
        ];

        //$this->getFiltroDuplo($objForm, 'E QUE');
        //$this->getFiltroDuplo($objForm, 'OU QUE');

        $html .= $template->getTab('tabFiltro', ['classCss' => 'col-sm-12'], $tabArray);

        $html .= $this->html->fechaTag('form');

        //Hidden de Interceptção a paginação
        //$html .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'pa', 'id' => 'pa', 'value' => '']);
        //$html .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'qo', 'id' => 'qo', 'value' => '']);
        //$html .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'to', 'id' => 'to', 'value' => '']);
        //$html.= $objForm->fechaForm();

        return $html;
    }

    private function getFiltroNormal($objForm)
    {
        $objetos = $objForm->getObjetos();

        $prefixo = 'n';
        $this->atualizaCampos($objForm, $prefixo);

        $buffer = $this->html->abreTagAberta('div', ['class' => 'form-group']);

        foreach ($objetos as $nomeObjeto => $objCampo) {

            $objCampo->setLayoutPixel(false);
            $nomeCampo = $objCampo->getNome();

            $tipoFiltro = key($this->getTipoFiltro($objCampo->getTipoFiltro()));
            $acao = $objCampo->getAcao();

            $buffer .= $this->html->abreTagAberta('div', ['class' => 'col-sm-6']);
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'input-group']);

            $buffer .= $this->html->abreTagAberta('div', ['class' => 'input-group-btn']);
            $buffer .= $this->html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn btn-default', 'tabindex' => '-1']);
            $buffer .= $objCampo->getIdentifica();
            $buffer .= $this->html->fechaTag('button');

            $buffer .= $this->html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn dropdown-toggle btn-warning', 'data-toggle' => 'dropdown']);
            $buffer .= $this->html->abreTagAberta('span', ['id' => 'sisIcFil' . $nomeCampo, 'class' => 'fa fa-caret-down']);
            $buffer .= $tipoFiltro;
            $buffer .= $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('button');

            $buffer .= $this->html->abreTagAberta('ul', ['class' => 'dropdown-menu']);

            $buffer.= $this->opcoesDeFiltro($objCampo->getTipoFiltro(), $nomeCampo,'n');

            $buffer .= $this->html->fechaTag('ul');
            $buffer .= $this->html->fechaTag('div');

            //Campo
            $buffer .= $objForm->getFormHtml($nomeObjeto);

            //Hiddens - sisHiddenOperador = sho e sisHiddenAcao = sha
            $buffer .= $this->html->abreTagFechada('input', ['name' => 'sho' . $nomeCampo, 'id' => 'sho' . $nomeCampo, 'type' => 'hidden', 'value' => $tipoFiltro]);
            $buffer .= $this->html->abreTagFechada('input', ['name' => 'sha' . $nomeCampo, 'id' => 'sha' . $nomeCampo, 'type' => 'hidden', 'value' => $acao]);

            $buffer .= $this->html->fechaTag('div');
            $buffer .= $this->html->fechaTag('div');
        }
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $objForm->javaScript()->getLoad(true);

        return $buffer;
    }

    private function getFiltroDuplo($objForm, $prefixo)
    {

        $this->html = new \Zion\Layout\Html();
                
        $objetos = $objForm->getObjetos();

        $buffer = $this->html->abreTagAberta('form', ['class' => 'form-horizontal']);
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'form-group']);


        foreach ($objetos as $nomeObjeto => $objCampo) {

            $objCampo->setLayoutPixel(false);

            // por questoes de alinhamento, o primeiro campo é col-sm-5 e o segundo é col-sm-6        
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'col-sm-5']);
            $buffer .= $this->getCampoDuplo($objForm, $nomeObjeto, $objCampo, $prefixo, 'A');
            $buffer .= $this->html->fechaTag('div');
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'col-sm-1']);
            $buffer .= $this->html->abreTagAberta('span', ['class' => 'label label-warning marE10px']) . $prefixo . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('div');
            // por questoes de alinhamento, o primeiro campo é col-sm-5 e o segundo é col-sm-6
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'col-sm-6']);
            $buffer .= $this->getCampoDuplo($objForm, $nomeObjeto, $objCampo, $prefixo, 'B');
            $buffer .= $this->html->fechaTag('div');
        }

        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('form');

        return $buffer;
    }

    private function getCampoDuplo($objForm, $nomeCampo, $objCampo, $prefixo, $sufixo)
    {
        $this->atualizaCampos($objForm, $prefixo, $sufixo);
        
        $objCampo->setLayoutPixel(false);

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'input-group']);
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'input-group-btn']);
        $buffer .= $this->html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn btn-default', 'tabindex' => '-1']);
        $buffer .= $objCampo->getIdentifica();
        $buffer .= $this->html->fechaTag('button');

        $buffer .= $this->html->abreTagAberta('button', ['id' => 'sisBtnFil', 'type' => 'button', 'class' => 'btn dropdown-toggle', 'data-toggle' => 'dropdown']);
        $buffer .= $this->html->abreTagAberta('span', ['id' => 'sisIcFil', 'class' => 'fa fa-caret-down']);
        $buffer .= '';
        $buffer .= $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('button');

        $buffer .= $this->html->abreTagAberta('ul', ['class' => 'dropdown-menu']);

        $buffer.= $this->opcoesDeFiltro($objCampo->getTipoFiltro(), $nomeCampo, $prefixo);

        $buffer .= $this->html->fechaTag('ul');
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $objForm->getFormHtml($nomeCampo);

        $buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

    private function atualizaCampos($objForm, $prefixo = '', $sufixo = '')
    {
        $obj = $objForm->getObjetos();    
        
        foreach ($obj as $objCampos) {

            $tipoBase = $objCampos->getTipoBase();

            $nomeAtual = $objCampos->getNome();
            $idAtual = $objCampos->getId();
            $complementoAtual = $objCampos->getComplemento();

            $objCampos->setNome($prefixo . $nomeAtual . $sufixo);
            $objCampos->setId($prefixo . $idAtual . $sufixo);
            $objCampos->setComplemento($complementoAtual . ' onChange="sisChangeFil(\''.$prefixo.'\')"');

            if ($tipoBase == 'suggest') {
                $onSelect = $objCampos->getOnSelect();
                $objCampos->setOnSelect($onSelect . ' sisChangeFil(\''.$prefixo.'\');');
            }
        }
    }

    private function opcoesDeFiltro($tipoFiltro, $nomeCampo, $prefixo)
    {
        $buffer = '';
        $tipos = $this->getTipoFiltro($tipoFiltro);

        foreach ($tipos as $tipo => $descricao) {

            $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisOpFiltro(\'' . $nomeCampo . '\',\'' . $tipo . '\',\'' . $prefixo . '\');'));
            $buffer .= $this->html->abreTagAberta('span', array('class' => 'label label-warning')) . $tipo . $this->html->fechaTag('span');
            $buffer .= $this->html->abreTagAberta('span', array('class' => 'recE20px italico')) . $descricao . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
            $buffer .= $this->html->fechaTag('li');
        }

        return $buffer;
    }

    private function getTipoFiltro($tipoFiltro)
    {
        $igual = ['=' => 'Igual a'];
        $diferente = ['≠' => 'Diferente de'];
        $menor = ['<' => 'Menor que'];
        $menorIgual = ['<=' => 'Menor ou igual que'];
        $maior = ['>' => 'Maior que'];
        $maiorIgual = ['>=' => 'Maior ou igual que'];
        $coringa = ['*' => 'Coringa'];
        $coringaAntes = ['*A' => 'Coringa antes'];
        $coringaDepois = ['A*' => 'Coringa após'];

        switch (\strtolower($tipoFiltro)) {
            case "valorvariavel":
                return \array_merge($igual, $diferente, $menor, $menorIgual, $maior, $maiorIgual);

            case "texto":
                return \array_merge($igual, $diferente, $coringa, $coringaAntes, $coringaDepois);

            case "valorfixo":
                return \array_merge($igual, $diferente);

            case "igual":
                return $igual;

            case "diferente":
                return $diferente;

            default: return [];
        }
    }

}
