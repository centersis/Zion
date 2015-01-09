<?php

namespace Pixel\Grid;

class GridBotoes
{

    private $botoesExcluir;
    private $html;

    public function __construct()
    {
        $this->botoesExcluir = [];
        $this->html = new \Zion\Layout\Html();
    }

    public function setFiltros($filtros)
    {
        $this->conteudoFiltros = $filtros;
    }

    private function getFiltros()
    {

        $template = new \Pixel\Template\Template();

        $buffer = '';
        $buffer .= $template->getPanel('box-filters', 'Filtros especiais', $this->conteudoFiltros, ['startVisible' => false, 'titleVisible' => false, 'iconTitle' => 'fa fa-filter']);

        return $buffer;
    }

    public function geraBotoes()
    {
        $acesso = new \Zion\Acesso\Acesso();

        $arrayBotoesE = [];
        $arrayBotoesR = [];
        $posicoes = [];

        if (!\defined('MODULO')) {
            throw new \Exception("O módulo não foi definido!");
        }

        $arrayAcesso = $acesso->permissoesModulo();
        $buffer = $this->html->abreTagAberta('div', ['id' => 'sisContainer', 'class' => 'clearfix recI10px', 'style' => 'position:relative']);
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'btn-toolbar wide-btns']);

        //Check
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'btn-group hidden-xs hidden-sm recD20px']);
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'btn-group']);

        $buffer .= $this->html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn btn-lg dropdown-toggle', 'data-toggle' => 'dropdown']);
        $buffer .= $this->html->abreTagFechada('i', ['class' => 'fa fa-check-square-o']);
        $buffer .= '&nbsp;';
        $buffer .= $this->html->abreTagFechada('i', ['class' => 'fa fa-caret-down']);
        $buffer .= $this->html->fechaTag('button');

        $buffer .= $this->html->abreTagAberta('ul', ['class' => 'dropdown-menu', 'role' => 'menu']);

        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('a', ['href' => '#', 'onclick' => 'sisMarcarTodos()']);
        $buffer .= 'Marcar todos';
        $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');

        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('a', ['href' => '#', 'onclick' => 'sisDesmarcarTodos()']);
        $buffer .= 'Desmarcar todos';
        $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');

        $buffer .= $this->html->fechaTag('ul');

        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

        $cont = 0;
        foreach ($arrayAcesso as $dados) {
            $cont++;
            if (!\in_array($dados['acaomoduloidpermissao'], $this->botoesExcluir)) {
                $cont++;

                if ($dados['acaomoduloapresentacao'] == 'E') {

                    $botoes = $this->html->abreTagAberta('button', [
                        'type' => 'button',
                        'class' => 'btn btn-lg hidden-xs',
                        'title' => $dados['acaomodulopermissao'],
                        'onclick' => $dados['acaomodulofuncaojs']]);

                    $botoes.= $this->html->abreTagFechada('i', ['class' => $dados['acaomoduloicon']]);
                    $botoes.= $this->html->fechaTag('button');

                    $arrayBotoesE[$cont] = $botoes;
                } else {

                    //$form = new \Pixel\Form\Form();
                    //$formHtml = new \Pixel\Form\FormHtml();
                    //$gridHtml = $form->hidden('gridHtml', 'gridHtml', false)->setValor('asd');

                    $botoes = $this->html->abreTagAberta('li', ['class' => 'hidden-xs']);
                    $botoes .= $this->html->abreTagAberta('a', ['href' => 'javascript:' . $dados['acaomodulofuncaojs']]); //GET THE FUCKING OVER HERE!
                    $botoes .= $this->html->abreTagAberta('i', ['class' => 'dropdown-icon ' . $dados['acaomoduloicon']]);
                    $botoes .= $this->html->fechaTag('i');
                    $botoes .= '&nbsp;&nbsp;&nbsp;' . $dados['acaomodulopermissao'];
                    $botoes .= $this->html->fechaTag('a');
                    $botoes .= $this->html->fechaTag('li');

                    $arrayBotoesR[$cont] = $botoes;
                }

                $posicoes[$cont] = (int) $dados['acaomoduloposicao'];
            }
        }

        //Gerando HTML com as Posições Corretas
        \asort($posicoes, \SORT_NUMERIC);
        $expandidos = '';
        $recolhidos = '';

        foreach (\array_keys($posicoes) as $chave) {

            if (\array_key_exists($chave, $arrayBotoesE)) {
                $expandidos .= $arrayBotoesE[$chave];
            } else {
                $recolhidos .= $arrayBotoesR[$chave];
            }
        }

        $buffer.= $this->html->abreTagAberta('div', ['class' => 'btn-group']);

        $buffer .= $expandidos;

        if ($recolhidos) {

            $buffer .= $this->html->abreTagAberta('div', ['class' => 'btn-group']);
            $buffer .= $this->html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn btn-lg dropdown-toggle', 'data-toggle' => 'dropdown', 'title' => 'Permiss&otilde;es especiais']);
            $buffer .= $this->html->abreTagFechada('i', ['class' => 'fa fa-bars']);
            $buffer .= '&nbsp;';
            $buffer .= $this->html->abreTagFechada('i', ['class' => 'fa fa-caret-down']);
            $buffer .= $this->html->fechaTag('button');

            $buffer .= $this->html->abreTagAberta('ul', ['class' => 'dropdown-menu', 'role' => 'menu']);
            $buffer .= $recolhidos;
            $buffer .= $this->html->fechaTag('ul');
            $buffer .= $this->html->fechaTag('div');

            $buffer .= $this->html->abreTagAberta('div', ['class' => 'btn-group recE20px']);
            $buffer .= $this->html->abreTagAberta('button', ['id' => 'ch-filters', 'class' => 'btn btn-lg', 'title' => 'Filtros especiais', 'onclick' => ' showHiddenFilters();']);
            $buffer .= $this->html->abreTagFechada('i', ['class' => 'fa fa-filter']);
            $buffer .= '&nbsp;';
            $buffer .= $this->html->abreTagFechada('i', ['class' => 'fa fa-caret-down']);
            $buffer .= $this->html->fechaTag('button');
        }

        $buffer .= $this->html->fechaTag('div');

        $buffer.= $this->html->abreTagAberta('div', ['class' => 'btn-toolbar pull-right recE5px hidden-xs hidden-sm hidden-md']);
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'btn-group']);
        $buffer .= $this->html->abreTagAberta('input', ['id' => 'sisBuscaGridA', 'name' => 'sisBuscaGridA', 'type' => 'text', 'class' => 'input form-control tagsinput', 'data-role' => 'tagsinput', 'placeholder' => 'Pesquisar']);
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('div', ['class' => 'btn-toolbar pull-right recE20px visible-md hidden-lg']);
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'btn-group']);
        $buffer .= $this->html->abreTagAberta('input', ['id' => 'sisBuscaGridB', 'name' => 'sisBuscaGridB', 'type' => 'text', 'class' => 'input form-control tagsinput', 'data-role' => 'tagsinput', 'placeholder' => 'Pesquisar']);
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->getFiltros();

        //$buffer .= $this->html->abreTagAberta('div', ['class' => 'btn-toolbar pull-right recE20px visible-md hidden-lg']);
        //$buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

    public function setBotoesExcluir($botoesExcluir)
    {
        $this->botoesExcluir = \array_map('strtolower', $botoesExcluir);
    }

}
