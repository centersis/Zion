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
    
    private function iniciar()
    {
        try {

            $template = new \Pixel\Template\Template();

            $acesso = new \Zion\Acesso\Acesso();

            $modulo = new \Sappiens\Grupo\Modulo\ModuloClass();

            $moduloForm = new \Sappiens\Grupo\Modulo\ModuloForm();

            $template->setConteudoScripts($moduloForm->getJSEstatico());

            if ($acesso->permissaoAcao('filtrar')) {

                $botoes = new \Pixel\Grid\GridBotoes();
                
                $dadosFiltrar = $modulo->filtrar();
                
                $htmlBotoes = $botoes->geraBotoes($dadosFiltrar['paginacao']);
                
                $retorno = $htmlBotoes.$dadosFiltrar['grid'];
                
            } else {
                $retorno = 'Sem permissão';
            }
        } catch (\Exception $ex) {
            $retorno = $ex;
        }

        $template->setConteudoMain($retorno);

        return $template->getTemplate();
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