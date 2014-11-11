<?php

namespace Pixel\Filtro;

class FiltroForm
{

    private $zIndex;
    private $html;

    public function __construct()
    {
        $this->html = new \Zion\Layout\Html();
    }

    public function montaFiltro($objForm)
    {
        $template = new \Pixel\Template\Template();

        $html = $objForm->abreFormFiltro();

        $tabArray = [
            ['tabId' => 1,
                'tabActive' => 'active',
                'tabTitle' => 'Filtros especiais' .
                $template->getBadge(['id' => 'N', 'tipo' => 'danger'], 0),
                'tabContent' => $this->getFiltroNormal($objForm)
            ],
            ['tabId' => 2,
                'tabActive' => '',
                'tabTitle' => 'Filtros de operação ' .
                $template->getLabel(['id' => 'tabEQUE', 'tipo' => 'warning'], "E QUE") .
                $template->getBadge(['id' => 'E', 'tipo' => 'danger'], 0),
                'tabContent' => ''
            ],
            ['tabId' => 3,
                'tabActive' => '',
                'tabTitle' => 'Filtros de operação ' .
                $template->getLabel(['id' => 'tabOUQUE', 'tipo' => 'warning'], "OU QUE") .
                $template->getBadge(['id' => 'O', 'tipo' => 'danger'], 0),
                'tabContent' => ''
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
            $buffer .= $this->html->abreTagAberta('span', ['id' => 'sisIcFil'.$nomeCampo, 'class' => 'fa fa-caret-down']);
            $buffer .= $tipoFiltro;
            $buffer .= $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('button');

            $buffer .= $this->html->abreTagAberta('ul', ['class' => 'dropdown-menu']);

            $buffer.= $this->opcoesDeFiltro($objCampo->getTipoFiltro(), $nomeCampo);

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

    private function getFiltroDuplo($objForm, $operacao)
    {

        $this->html = new \Zion\Layout\Html();

        $objetos = $objForm->getObjetos();

        $buffer = $this->html->abreTagAberta('form', ['class' => 'form-horizontal']);
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'form-group']);


        foreach ($objetos as $nomeCampo => $objCampo) {

            $objCampo->setLayoutPixel(false);

            // por questoes de alinhamento, o primeiro campo é col-sm-5 e o segundo é col-sm-6        
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'col-sm-5']);
            $buffer .= $this->getCampoDuplo($objForm, $nomeCampo, $objCampo, 'A');
            $buffer .= $this->html->fechaTag('div');
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'col-sm-1']);
            $buffer .= $this->html->abreTagAberta('span', ['class' => 'label label-warning marE10px']) . $operacao . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('div');
            // por questoes de alinhamento, o primeiro campo é col-sm-5 e o segundo é col-sm-6
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'col-sm-6']);
            $buffer .= $this->getCampoDuplo($objForm, $nomeCampo, $objCampo, 'B');
            $buffer .= $this->html->fechaTag('div');
        }

        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('form');

        return $buffer;
    }

    private function getCampoDuplo($objForm, $nomeCampo, $objCampo, $modo)
    {

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

        $buffer.= $this->opcoesDeFiltro($objCampo->getTipoFiltro());

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
            $objCampos->setComplemento($complementoAtual . ' onChange="sisChangeFil()"');

            if ($tipoBase == 'suggest') {
                $onSelect = $objCampos->getOnSelect();
                $objCampos->setOnSelect($onSelect . ' sisChangeFil();');
            }
        }
    }

    private function opcoesDeFiltro($tipoFiltro, $nomeCampo)
    {
        $buffer = '';
        $tipos = $this->getTipoFiltro($tipoFiltro);

        foreach ($tipos as $tipo => $descricao) {

            $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisOpFiltro(\''.$nomeCampo.'\',\''.$tipo.'\');'));
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

    public function opFiltro($cFG, $campo)
    {
        //Variaveis de Configurações
        $nomeCampo = $cFG['Nome'];
        $identifica = $cFG['Identifica'];
        $tipoFiltro = $cFG['TipoFiltro'];
        $addFiltro = $cFG['AddFiltro'];
        $padraoFiltro = $cFG['PadraoFiltro'];

        //Index CSS
        $this->zIndex -= 1;

        //Array de Tipos
        $arrayTipos = $this->getTipoFiltro($tipoFiltro);

        //Adiciona Tipos Extras
        if (is_array($addFiltro)) {
            $arrayTipos += $addFiltro;
        }


        //Monta Extrutura Html
        if (count($arrayTipos) > 0) {
            //Valor default
            if ($padraoFiltro != '') {
                $padraoFiltro = (in_array($padraoFiltro, $arrayTipos) and $padraoFiltro != '') ? $padraoFiltro : '=';
            } else {
                $padraoFiltro = '=';
            }

            $htmlFil = '';
            //Monta Html de opções
            foreach ($arrayTipos as $valor) {
                $htmlFil .= $this->html->entreTags('li', $valor);
            }

            //Html do Filtro
            $opFiltro = $this->html->abreTagAberta('id', ['id' => 'sis_filtro_' . $nomeCampo, 'style' => 'z-index:' . $this->zIndex . ';', 'width' => '25px', 'float' => 'left']);
            $opFiltro .= $this->html->abreTagAberta('ul', ['id' => 'fil_ini', 'style' => 'z-index:' . $this->zIndex . ';']);
            $opFiltro .= $this->html->entreTags('li', $padraoFiltro);
            $opFiltro .= $this->html->fechaTag('ul');
            $opFiltro .= $this->html->entreTags('ul', ['id' => 'tudo', 'style' => 'display:none; z-index:' . $this->zIndex . ';'], $htmlFil);
            $opFiltro .= $this->html->fechaTag('div');

            //Hidden que guarda o valor selecionado
            $hidden = $this->html->abreTagFechada('input', ['name' => 'shf' . $nomeCampo, 'id' => 'shf' . $nomeCampo, 'type' => 'hidden', 'value' => $padraoFiltro]);

            //Hidden que guarda o Tipo de Campo
            $hidden .= $this->html->abreTagFechada('input', ['name' => 'shtf' . $nomeCampo, 'id' => 'shtf' . $nomeCampo, 'type' => 'hidden', 'value' => '']);

            //Hidden que guarda a Identificação do Campo
            $hidden .= $this->html->abreTagFechada('input', ['name' => 'shif' . $nomeCampo, 'id' => 'shif' . $nomeCampo, 'type' => 'hidden', 'value' => $identifica]);

            //Retorno
            return $opFiltro . $hidden . $campo;
        } else {
            //Se não existem elementos retorna somente o campo
            return $campo;
        }
    }

}
