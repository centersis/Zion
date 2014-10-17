<?php

namespace Zion\Grid;

class GridBotoes
{

    private $botoesExcluir;
    private $botoesIncluir;

    public function __construct()
    {
        $this->botoesIncluir = [];
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
        $buffer .= $html->abreTagFechada('i', ['class' => 'fa-check-square-o']);
        $buffer.= '&nbsp;';
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

                $botoes = $html->abreTagAberta('button', [
                    'type' => 'button',
                    'class' => 'btn btn-lg hidden-xs',
                    'title' => $dados['AcaoModuloPermissao'],
                    'onclick' => $dados['AcaoModuloFuncaoJS']]);

                $botoes.= $html->abreTagFechada('i', ['class' => $dados['AcaoModuloIcon']]);
                $botoes.= $html->fechaTag('button');

                if ($dados['AcaoModuloApresentacao'] == 'E') {
                    $arrayBotoesE[$cont] = $botoes;
                } else {
                    $arrayBotoesR[$cont] = $botoes;
                }

                $posicoes[$cont] = (int) $dados['AcaoModuloPosicao'];
            }
        }

        //Incluir Botões
        if (is_array($this->botoesIncluir)) {
            foreach ($this->botoesIncluir as $dadosBotao) {
                $cont++;

                $botoes = $html->abreTagAberta('button', [
                    'type' => 'button',
                    'class' => 'btn btn-lg hidden-xs',
                    'title' => $dados['AcaoModuloPermissao'],
                    'onclick' => $dados['AcaoModuloFuncaoJS']]);

                $botoes.= $html->abreTagFechada('i', ['class' => $dados['AcaoModuloIcon']]);
                $botoes.= $html->fechaTag('button');

                $arrayBotoes[$cont] = $botoes;
                $posicoes[$cont] = (int) $dadosBotao['AcaoModuloPosicao'];
            }
        }

        //Gerando HTML com as Posiçõees Corretas
        asort($posicoes, SORT_NUMERIC);

        foreach ($posicoes as $chave => $posicao) {
            $buffer .= $arrayBotoes[$chave];
        }        

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
