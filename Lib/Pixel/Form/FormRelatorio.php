<?php

namespace Pixel\Form;

use Zion\Acesso\Acesso;

class FormRelatorio
{

    public function relatorioInterno($cod)
    {
        $retorno = [];

        $acesso = new Acesso();

        $acoes = $acesso->acoesModulo("acaoModuloApresentacao = 'Rel'");

        foreach ($acoes as $dados) {
            $retorno[] = ['nome' => $dados['acaomodulopermissao'], 'funcao' => $dados['acaomodulofuncaojs'], 'cod' => $cod];
        }

        return $retorno;
    }

}
