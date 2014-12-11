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
            if (!\method_exists($this, $acao)) {
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
        return \json_encode(array('sucesso' => 'true', 'retorno' => $retorno));
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

        return \json_encode(array('sucesso' => 'false', 'retorno' => $tratar->texto()->trata($erro)));
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

    protected function registrosSelecionados()
    {
        $selecionados = \filter_input(\INPUT_GET, 'sisReg', \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);

        if (empty($selecionados[0])) {

            $valor = \filter_input(\INPUT_GET, 'sisReg', \FILTER_DEFAULT);

            if (!empty($valor)) {
                $selecionados = [$valor];
            } else {
                $selecionados = 0;
            }
        }

        if (empty($selecionados) or ! \is_array($selecionados)) {
            throw new \Exception("Nenhum registro selecionado!");
        }

        return $selecionados;
    }

    protected function metodoPOST()
    {
        return \filter_input(\INPUT_SERVER, 'REQUEST_METHOD') === 'POST' ? true : false;
    }

    protected function postCod()
    {
        return \filter_input(\INPUT_POST, 'cod');
    }

    protected function emTabs($cod = '')
    {
        $numArgs = \func_num_args();

        $tabs = new \Pixel\Layout\Tab('Tab'.$cod, 12);
        
        for ($i = 0; $i < $numArgs; $i++) {
            
            if($i == 0){
                continue;
            }
            
            $ativa = $i === 1 ? true : false;
            
            $objForm = \func_get_arg($i);  
            
            $retorno = $objForm->montaForm();
            $retorno .= $objForm->javaScript()->getLoad(true);
            $objForm->javaScript()->resetLoad();

            $nomeAtual = $objForm->getConfig()->getHeader();
            $abaNome = $nomeAtual ? $nomeAtual : 'Aba '.$i;
            
            $tabs->config($i)->setAtiva($ativa)->setTitulo($abaNome)->setConteudo($retorno);
        }
        
        return $tabs->criar();
    }

}
