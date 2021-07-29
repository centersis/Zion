<?php

namespace Centersis\Zion\Pixel\Grid;

use Zion\Acesso\Acesso;
use Zion\Exception\ErrorException;

class GridBotoes
{

    private $botoesExcluir;

    public function __construct()
    {
        $this->botoesExcluir = [];
    }

    public function setFiltros($filtros)
    {
        $this->conteudoFiltros = $filtros;
    }

    public function geraBotoes($selecao = true)
    {
        $acesso = new Acesso();

        $arrayBotoesE = [];
        $arrayBotoesR = [];
        $posicoes = [];

        if (!defined('MODULO')) {

            throw new ErrorException("O módulo não foi definido!");
        }

        $arrayAcesso = $acesso->permissoesModulo();

        $cont = 0;
        foreach ($arrayAcesso as $dados) {

            $cont++;

            if (!in_array($dados['acao_modulo_id_permissao'], $this->botoesExcluir)) {

                $cont++;

                if ($dados['acao_modulo_apresentacao'] == 'E') {

                    $arrayBotoesE[$cont] = $dados;
                } elseif($dados['acao_modulo_apresentacao'] == 'R') {

                    $arrayBotoesR[$cont] = $dados;
                } else {
                    continue;
                }

                $posicoes[$cont] = (int) $dados['acao_modulo_posicao'];
            }
        }

        //Gerando Posições Corretas
        asort($posicoes, SORT_NUMERIC);

        $expandidos = [];
        $recolhidos = [];

        foreach (array_keys($posicoes) as $chave) {

            if (array_key_exists($chave, $arrayBotoesE)) {

                $expandidos[] = $arrayBotoesE[$chave];
            } else {

                $recolhidos[] = $arrayBotoesR[$chave];
            }
        }

        return ['expandidos' => $expandidos, 'recolhidos' => $recolhidos, 'selecao' => ($selecao ? 'true' : 'false')];
    }

    public function setBotoesExcluir($botoesExcluir)
    {
        $this->botoesExcluir = array_map('strtolower', $botoesExcluir);
    }

}
