<?php

namespace Zion\Core;

class Controller
{

    private $acao;
    
    public function controle($acao)
    {
        if (empty($acao)) {
            $acao = 'iniciar';
        }
        
        $this->acao = $acao;

        try {
            if (!method_exists($this, $acao)) {
                throw new \Exception("Opção inválida!");
            }

            return $this->{$acao}();
        } catch (\Exception $e) {
            
            return $this->jsonErro($e->getMessage());
        }
    }    
    
    public function jsonSucesso($retorno)
    {
        return json_encode(array('sucesso' => 'true', 'retorno' => $retorno));
    }
    
    public function jsonErro($erro)
    {
        $tratar = new \Zion\Validacao\Valida();
        
        json_encode(array('sucesso' => 'false', 'retorno' => $tratar->texto()->trata($erro)));
    }
    
    public function getAcao()
    {
        return $this->acao;
    }

}