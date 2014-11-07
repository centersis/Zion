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
        $this->atualizaIds($objForm);

        $html = '';

        $campos = $objForm->getFormHtml();

//        foreach ($campos as $htmlCampo) {
//            $html .= $htmlCampo;
//        }

        $tabArray = array(
            array('tabId' => 1,
                'tabActive' => 'active',
                'tabTitle' => 'Filtros especiais' .
                $template->getBadge("1", ['id' => 'fE', 'tipo' => 'danger']),
                'tabContent' => $this->getFiltroNormal($campos)
            ),
            array('tabId' => 2,
                'tabActive' => '',
                'tabTitle' => 'Filtros de operação ' .
                $template->getLabel("E QUE", ['id' => 'tabEQUE', 'tipo' => 'warning']) .
                $template->getBadge("2", ['id' => 'fE', 'tipo' => 'danger']),
                'tabContent' => $this->getFiltroOuQue("E QUE")
            ),
            array('tabId' => 3,
                'tabActive' => '',
                'tabTitle' => 'Filtros de operação ' . 
                $template->getLabel("OU QUE", ['id' => 'tabOUQUE', 'tipo' => 'warning']) . 
                $template->getBadge("1", ['id' => 'fE', 'tipo' => 'danger']),
                'tabContent' => $this->getFiltroOuQue("OU QUE")
            )
        );

        $html .= $template->getTab('tabFiltro', array('classCss' => 'col-sm-12'), $tabArray);


        //Hidden de Interceptção a paginação
        $html .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'pa', 'id' => 'pa', 'value' => '']);
        $html .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'qo', 'id' => 'qo', 'value' => '']);
        $html .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'to', 'id' => 'to', 'value' => '']);

        $html.= $objForm->fechaForm();

        return $html;
    }

    private function getFiltroNormal($campos)
    {
        $buffer  = $this->html->abreTagAberta('form', array('class' => 'form-horizontal'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'form-group'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'col-sm-6'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'input-group'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'input-group-btn'));
        $buffer .= $this->html->abreTagAberta('button', array('type' => 'button', 'class' => 'btn btn-default', 'tabindex' => '-1'));
        $buffer .= 'Fornecedor';
        $buffer .= $this->html->fechaTag('button');

        $buffer .= $this->html->abreTagAberta('button', array('id' => 'sisBtnFil', 'type' => 'button', 'class' => 'btn dropdown-toggle', 'data-toggle' => 'dropdown'));
        $buffer .= $this->html->abreTagAberta('span', array('id' =>'sisIcFil', 'class' => 'fa fa-caret-down'));
        $buffer .= '';
        $buffer .= $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('button');

        $buffer .= $this->html->abreTagAberta('ul', array('class' => 'dropdown-menu'));

        $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisChFil(\'=\');'));
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'label label-warning')) . ' = ' . $this->html->fechaTag('span');
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'recE20px italico')) . 'Igual a' . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');

        $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisChFil(\'!=\');'));
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'label label-warning')) . ' != ' . $this->html->fechaTag('span');
                $buffer .= $this->html->abreTagAberta('span', array('style' => 'padding-left:16px;', 'class' => 'italico')) . 'Diferente de' . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');        

        $buffer .= $this->html->abreTagAberta('li', ['class' => 'divider']) . $this->html->fechaTag('li');

        $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisChFil(\'<>\');'));
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'label label-warning')) . ' <> ' . $this->html->fechaTag('span');
                $buffer .= $this->html->abreTagAberta('span', array('style' => 'padding-left:12px;', 'class' => 'italico')) . 'Menor ou maior que' . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');   

        $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisChFil(\'<\');'));
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'label label-warning')) . ' < ' . $this->html->fechaTag('span');
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'recE20px italico')) . 'Menor que' . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');        

        $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisChFil(\'>\');'));
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'label label-warning')) . ' > ' . $this->html->fechaTag('span');
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'recE20px italico')) . 'Maior que' . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');   

        $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisChFil(\'<=\');'));
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'label label-warning')) . ' <= ' . $this->html->fechaTag('span');
                $buffer .= $this->html->abreTagAberta('span', array('style' => 'padding-left:12px;', 'class' => 'italico')) . 'Menor ou igual a' . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');   
        
        $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisChFil(\'>=\');'));
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'label label-warning')) . ' >= ' . $this->html->fechaTag('span');
                $buffer .= $this->html->abreTagAberta('span', array('style' => 'padding-left:12px;', 'class' => 'italico')) . 'Maior ou igual a' . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');                              

        $buffer .= $this->html->abreTagAberta('li', ['class' => 'divider']) . $this->html->fechaTag('li');         

        $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisChFil(\'*\');'));
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'label label-warning')) . ' * ' . $this->html->fechaTag('span');
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'recE20px italico')) . 'Semelhante' . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');       

        $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisChFil(\'A*\');'));
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'label label-warning')) . ' A* ' . $this->html->fechaTag('span');
                $buffer .= $this->html->abreTagAberta('span', array('style' => 'padding-left:12px;', 'class' => 'italico')) . 'Semelhante após' . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');     

        $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisChFil(\'*A\');'));
                $buffer .= $this->html->abreTagAberta('span', array('class' => 'label label-warning')) . ' *A ' . $this->html->fechaTag('span');
                $buffer .= $this->html->abreTagAberta('span', array('style' => 'padding-left:12px;', 'class' => 'italico')) . 'Semelhante antes' . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');                   

        $buffer .= $this->html->fechaTag('ul');
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('input', array('id' => 'inputFoda', 'class' => 'form-control', 'onchange' => 'javascript:sisInputFil(\'sisLabel_fe\');', 'placeholder' => 'Digite e torça!'));

        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('form');
        return $buffer;

    }   
    
    private function atualizaIds($objForm)
    {
        $obj = $objForm->getObjetos();

        foreach ($obj as $objCampos) {
            $idAtual = $objCampos->getId();
            $objCampos->setId('f' . $idAtual);
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
