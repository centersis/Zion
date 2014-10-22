<?php

namespace Pixel\Filtro;

class Filtro
{

    private $zIndex;
    private $html;

    public function __construct()
    {
        $this->html = new \Zion\Layout\Html();
    }

    public function montaFiltro($arrayCampos)
    {
        $html = "";
        $hiddens = "";
        $StrFormI = '<div id="sis_form_filtros"><img id="imgSisFiltro" src="' . $_SESSION['UrlBase'] . 'figuras/sis_filtro_mostrar.gif" onClick="sisShowFiltro()"><span id="sis_filtrar_filtro"><img src="' . $_SESSION['UrlBase'] . 'figuras/mostrar_filtros.gif" border="0" onClick="sisFiltrarFiltro(\'' . MODULO . '\')" /></span><span id="sis_salvar_filtro"><img src="' . $_SESSION['UrlBase'] . 'figuras/bt_salvar_filtro.gif" border="0" onClick="sisCadastrarFiltro(\'' . MODULO . '\')" /></span></div>';
        $StrFormI .= '<form action="" method="get" name="FormFiltro" id="FormFiltro" class="forms" onSubmit="return false" style="display:none">';
        $StrFormF = '</form>';

        if (is_array($arrayCampos)) {
            $tab = new Tabelas();

            $html = $tab->tabIni();

            //Cria Linhas
            foreach ($arrayCampos as $valor) {

                if ($valor[0] == true) {
                    if (!empty($valor[1])) {
                        $linha[$valor[3]] .= $tab->abreTd(null, "itensFiltro", "right") . $valor[1] . $tab->fechaTd();
                    }

                    $linha[$valor[3]] .= $tab->abreTd() . $valor[2] . $tab->fechaTd();
                } else {
                    $escondido[] = $valor[2];
                }
            }

            //Descarrega Campos Escondidos
            if (is_array($escondido)) {
                foreach ($escondido as $campos) {
                    $hiddens .= $campos;
                }
            }

            //Cria estrutura de Filtro
            foreach ($linha as $conteudo) {
                $html .= $tab->abreTr();
                $html .= $conteudo;
                $html .= $hiddens . $tab->fechaTr();
            }

            $html .= $tab->tabFim();

            //Hidden de Intercep��o a pagina��o
            $html .= '<input name="PaginaAtual"   id="SisPaginaAtual"   type="text" value="" size="1" style="display:none" />';
            $html .= '<input name="QuemOrdena"    id="SisQuemOrdena"    type="text" value="" size="1" style="display:none" />';
            $html .= '<input name="TipoOrdenacao" id="SisTipoOrdenacao" type="text" value="" size="1" style="display:none" />';

            return $StrFormI . $html . $StrFormF;
        }

        return $html;
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
