<?php

namespace Pixel\Grid;

class GridBotoes
{

    private $botoesExcluir;

    public function __construct()
    {
        $this->botoesExcluir = [];
    }

    public function geraBotoes()
    {
        $acesso = new \Zion\Acesso\Acesso();
        $html = new \Zion\Layout\Html();

        $arrayBotoesE = [];
        $arrayBotoesR = [];
        $posicoes = [];

        if (!defined('MODULO')) {
            throw new \Exception("O módulo não foi definido!");
        }

        $arrayAcesso = $acesso->permissoesModulo();

        $buffer = $html->abreTagAberta('div', ['class' => 'btn-toolbar wide-btns pull-left']);

        //Check
        $buffer .= $html->abreTagAberta('div', ['class' => 'btn-group hidden-xs hidden-sm recD20px']);
        $buffer .= $html->abreTagAberta('div', ['class' => 'btn-group']);

        $buffer .= $html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn btn-lg dropdown-toggle', 'data-toggle' => 'dropdown']);
        $buffer .= $html->abreTagFechada('i', ['class' => 'fa fa-check-square-o']);
        $buffer .= '&nbsp;';
        $buffer .= $html->abreTagFechada('i', ['class' => 'fa fa-caret-down']);
        $buffer .= $html->fechaTag('button');

        $buffer .= $html->abreTagAberta('ul', ['class' => 'dropdown-menu', 'role' => 'menu']);

        $buffer .= $html->abreTagAberta('li');
        $buffer .= $html->abreTagAberta('a', ['href' => '#']);
        $buffer .= 'Marcar todos';
        $buffer .= $html->fechaTag('a');
        $buffer .= $html->fechaTag('li');

        $buffer .= $html->abreTagAberta('li');
        $buffer .= $html->abreTagAberta('a', ['href' => '#']);
        $buffer .= 'Desmarcar todos';
        $buffer .= $html->fechaTag('a');
        $buffer .= $html->fechaTag('li');

        $buffer .= $html->fechaTag('ul');

        $buffer .= $html->fechaTag('div');
        $buffer .= $html->fechaTag('div');

        $cont = 0;
        foreach ($arrayAcesso as $dados) {
            $cont++;

            if (!in_array($dados['AcaoModuloIdPermissao'], $this->botoesExcluir)) {
                $cont++;

                if ($dados['AcaoModuloApresentacao'] == 'E') {

                    $botoes = $html->abreTagAberta('button', [
                        'type' => 'button',
                        'class' => 'btn btn-lg hidden-xs',
                        'title' => $dados['AcaoModuloPermissao'],
                        'onclick' => $dados['AcaoModuloFuncaoJS']]);

                    $botoes.= $html->abreTagFechada('i', ['class' => $dados['AcaoModuloIcon']]);
                    $botoes.= $html->fechaTag('button');

                    $arrayBotoesE[$cont] = $botoes;
                } else {

                    $botoes = $html->abreTagAberta('li', ['class' => 'hidden-xs']);
                    $botoes .= $html->abreTagAberta('a', ['href' => 'javascript:' . $dados['AcaoModuloFuncaoJS']]);
                    $botoes .= $html->abreTagAberta('i', ['class' => 'dropdown-icon ' . $dados['AcaoModuloIcon']]);
                    $botoes .= $html->fechaTag('i');
                    $botoes .= '&nbsp;&nbsp;&nbsp;'.$dados['AcaoModuloPermissao'];
                    $botoes .= $html->fechaTag('a');
                    $botoes .= $html->fechaTag('li');

                    $arrayBotoesR[$cont] = $botoes;
                }

                $posicoes[$cont] = (int) $dados['AcaoModuloPosicao'];
            }
        }

        //Gerando HTML com as Posições Corretas
        asort($posicoes, SORT_NUMERIC);
        $expandidos = '';
        $recolhidos = '';
        
        foreach ($posicoes as $chave => $posicao) {

            if (key_exists($chave, $arrayBotoesE)) {
                $expandidos .= $arrayBotoesE[$chave];
            } else {
                $recolhidos .= $arrayBotoesR[$chave];
            }
        }
        
        $buffer.= $html->abreTagAberta('div',['class'=>'btn-group']);
        
        $buffer .= $expandidos;

        if ($recolhidos) {

            $buffer .= $html->abreTagAberta('div', ['class' => 'btn-group']);
            $buffer .= $html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn btn-lg dropdown-toggle', 'data-toggle' => 'dropdown']);
            $buffer .= $html->abreTagFechada('i', ['class' => 'fa fa-bars']);
            $buffer .= '&nbsp;';
            $buffer .= $html->abreTagFechada('i', ['class' => 'fa fa-caret-down']);
            $buffer .= $html->fechaTag('button');

            $buffer .= $html->abreTagAberta('ul', ['class' => 'dropdown-menu', 'role' => 'menu']);
            $buffer .= $recolhidos;
            $buffer .= $html->fechaTag('ul');

            $buffer .= $html->fechaTag('div');
        }
        
        $buffer .= $html->fechaTag('div');
        
        
        $buffer.= $html->abreTagAberta('div', ['class' => 'btn-toolbar pull-right recE20px hidden-xs hidden-sm hidden-md']);
            $buffer .= $html->abreTagAberta('div', ['class' => 'btn-group']);
            $buffer .= $html->abreTagAberta('input', ['id' => 'sisBuscaGridA','name'=>'sisBuscaGridA','type'=>'text','class'=>'input form-control tagsinput','data-role'=>'tagsinput','placeholder'=>'Pesquisar']);
            $buffer .= $html->fechaTag('div');        
        $buffer .= $html->fechaTag('div');
        
        $buffer.= $html->abreTagAberta('div', ['class' => 'btn-toolbar pull-right recE20px visible-md hidden-lg']);
            $buffer .= $html->abreTagAberta('div', ['class' => 'btn-group']);
            $buffer .= $html->abreTagAberta('input', ['id' => 'sisBuscaGridB','name'=>'sisBuscaGridB','type'=>'text','class'=>'input form-control tagsinput','data-role'=>'tagsinput','placeholder'=>'Pesquisar']);
            $buffer .= $html->fechaTag('div');        
        $buffer .= $html->fechaTag('div');
        
        $buffer .= $html->fechaTag('div');

        return $buffer;
    }

    public function getBotoesExcluir()
    {
        return $this->botoesExcluir;
    }

    public function setBotoesExcluir($botoesExcluir)
    {
        $this->botoesExcluir = $botoesExcluir;
    }

    public function getBotoesIncluir()
    {
        return $this->botoesIncluir;
    }

    public function setBotoesIncluir($botoesIncluir)
    {
        $this->botoesIncluir = $botoesIncluir;
    }

}
