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

    public function montaFiltro(\Pixel\Form\Form $objForm)
    {
        $this->atualizaIds($objForm);

        $html = '';

        $campos = $objForm->getFormHtml();

        foreach ($campos as $htmlCampo) {
            $html .= $htmlCampo;
        }

        //Hidden de Interceptção a paginação
        $html .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'pa', 'id' => 'pa', 'value' => '']);
        $html .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'qo', 'id' => 'qo', 'value' => '']);
        $html .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'to', 'id' => 'to', 'value' => '']);

        $html.= $objForm->fechaForm();

        return $html;
    }

    private function atualizaIds(Form $objForm)
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