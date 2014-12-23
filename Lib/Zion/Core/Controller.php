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

    private $acao;

    /**
     * Instancia de controler que intancia o metodo que lhe foi informado no
     * paremetro $acao
     * @param string $acao
     * @return string json
     * @throws \Exception
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
     * Retorna uma string no formato json como mensagem de sucesso
     * @param string $retorno
     * @return string json
     */
    public function jsonSucesso($retorno)
    {
        return \json_encode(array('sucesso' => 'true', 'retorno' => $retorno));
    }

    /**
     * Retorna uma string no formato json como mensagem de erro
     * @param string $erro
     * @return string json
     */
    public function jsonErro($erro)
    {
        $tratar = \Zion\Validacao\Valida::instancia();

        return \json_encode(array('sucesso' => 'false', 'retorno' => $tratar->texto()->trata($erro)));
    }

    /**
     * Retona a ação de controle usada para instanciar o controller
     * @return $this->acao
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

    /**
     * Retona verdadeiro cado o metodo da requisição seja POST
     * @return boolean
     */
    protected function metodoPOST()
    {
        return \filter_input(\INPUT_SERVER, 'REQUEST_METHOD') === 'POST' ? true : false;
    }

    /**
     * Recupera o valor da variavel cod enviada via POST
     * @return int
     */
    protected function postCod()
    {
        return \filter_input(\INPUT_POST, 'cod');
    }

    /**
     * Monta o layout do formulário em abas, após o primeiro parametro são 
     * aceitos instancias de Form, sendo que cada instancia formará uma aba
     * @param int $cod
     * @return string
     */
    protected function emTabs($cod = '')
    {
        $numArgs = \func_num_args();

        $tabs = new \Pixel\Layout\Tab('Tab' . $cod, 12);

        for ($i = 0; $i < $numArgs; $i++) {

            if ($i == 0) {
                continue;
            }

            $ativa = $i === 1 ? true : false;

            $objForm = \func_get_arg($i);

            if ($this->acao === 'visualizar') {
                $retorno = $objForm->montaFormVisualizar();
            } else {
                $retorno = $objForm->montaForm();
                $retorno .= $objForm->javaScript()->getLoad(true);
                $objForm->javaScript()->resetLoad();
            }

            $nomeAtual = $objForm->getConfig()->getHeader();
            $abaNome = $nomeAtual ? $nomeAtual : 'Aba ' . $i;

            $tabs->config($i)->setAtiva($ativa)->setTitulo($abaNome)->setConteudo($retorno);
        }

        return $tabs->criar();
    }

}
