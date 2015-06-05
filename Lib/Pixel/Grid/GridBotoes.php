<?php

/**
 *
 *    Sappiens Framework
 *    Copyright (C) 2014, BRA Consultoria
 *
 *    Website do autor: www.braconsultoria.com.br/sappiens
 *    Email do autor: sappiens@braconsultoria.com.br
 *
 *    Website do projeto, equipe e documentação: www.sappiens.com.br
 *   
 *    Este programa é software livre; você pode redistribuí-lo e/ou
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *    publicada pela Free Software Foundation, versão 2.
 *
 *    Este programa é distribuído na expectativa de ser útil, mas SEM
 *    QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *    detalhes.
 * 
 *    Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *    junto com este programa; se não, escreva para a Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *    02111-1307, USA.
 *
 *    Cópias da licença disponíveis em /Sappiens/_doc/licenca
 *
 */

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
                } else {

                    $arrayBotoesR[$cont] = $dados;
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
