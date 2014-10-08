<?php

namespace Zion\Core;

class Controller
{

    public function controle($acao)
    {
        if (empty($acao)) {
            $acao = 'iniciar';
        }

        try {
            if (!method_exists($this, $acao)) {
                throw new \Exception("Opção inválida!");
            }

            return $this->{$acao}();
        } catch (\Exception $e) {

            $tratar = new \Zion\Validacao\Valida();

            return json_encode(array('sucesso' => 'false', 'retorno' => $tratar->texto()->trata($e->getMessage())));
        }
    }

}
