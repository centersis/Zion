<?php

namespace Pixel\Grid;

use Zion\Acesso\Acesso;

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

        if (!\defined('MODULO')) {

            throw new \Exception("O módulo não foi definido!");
        }

        $arrayAcesso = $acesso->permissoesModulo();

        $cont = 0;
        foreach ($arrayAcesso as $dados) {

            $cont++;

            if (!\in_array($dados['acaomoduloidpermissao'], $this->botoesExcluir)) {

                $cont++;

                if ($dados['acaomoduloapresentacao'] == 'E') {

                    $arrayBotoesE[$cont] = $dados;
                } elseif($dados['acaomoduloapresentacao'] == 'R') {

                    $arrayBotoesR[$cont] = $dados;
                } else {
                    continue;
                }

                $posicoes[$cont] = (int) $dados['acaomoduloposicao'];
            }
        }

        //Gerando Posições Corretas
        \asort($posicoes, \SORT_NUMERIC);

        $expandidos = [];
        $recolhidos = [];

        foreach (\array_keys($posicoes) as $chave) {

            if (\array_key_exists($chave, $arrayBotoesE)) {

                $expandidos[] = $arrayBotoesE[$chave];
            } else {

                $recolhidos[] = $arrayBotoesR[$chave];
            }
        }

        return ['expandidos' => $expandidos, 'recolhidos' => $recolhidos, 'selecao' => ($selecao ? 'true' : 'false')];
    }

    public function setBotoesExcluir($botoesExcluir)
    {
        $this->botoesExcluir = \array_map('strtolower', $botoesExcluir);
    }

}
