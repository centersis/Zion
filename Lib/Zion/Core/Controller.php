<?php

/**
 * Controller()
 * @author Pablo Vanni - pablovanni@gmail.com
 * @since 11/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Controller
 */
namespace Zion\Core;

class Controller
{

    /**
     * @var string $acao
     */
    private $acao;
    
    /**
     * Controller::controle()
     * 
     * @param mixed $acao
     * @return string
     */
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
    
    /**
     * Controller::jsonSucesso()
     * 
     * @param mixed $retorno
     * @return string
     */
    public function jsonSucesso($retorno)
    {
        return json_encode(array('sucesso' => 'true', 'retorno' => $retorno));
    }
    
    /**
     * Controller::jsonErro()
     * 
     * @param mixed $erro
     * @return void
     */
    public function jsonErro($erro)
    {
        $tratar = \Zion\Validacao\Valida::instancia();
        
        return json_encode(array('sucesso' => 'false', 'retorno' => $tratar->texto()->trata($erro)));
    }
    
    /**
     * Controller::getAcao()
     * 
     * @return string
     */
    public function getAcao()
    {
        return $this->acao;
    }

}