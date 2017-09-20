<?php

namespace Zion\Core;

use Zion\Validacao\Valida;
use Pixel\Layout\Tab;
use Pixel\Twig\Carregador;
use Zion\Exception\ErrorException;
use Zion\Exception\ValidationException;
use Zion\Exception\AcessoException;

class Controller
{

    public function __construct()
    {
        
    }

    protected $acao;

    /**
     * Instancia de controler que intancia o metodo que lhe foi informado no
     * paremetro $acao
     * @param string $acao
     * @return string json
     * @throws ErrorException
     */
    public function controle($acao = '')
    {
        if (empty($acao)) {
            $acao = 'iniciar';
        }

        $this->acao = $acao;

        try {
            if (!\method_exists($this, $acao)) {
                throw new ErrorException("Opção inválida!");
            }

            return $this->{$acao}();
        } catch (AcessoException $e) {
            return $this->jsonErro($e->getMessage());
        } catch (ValidationException $e) {
            return $this->jsonErro($e->getMessage());
        } catch (ErrorException $e) {
            return $this->jsonErro($e->getMessage());
        } catch (\Exception $e) {
            return $this->jsonErro($e->getMessage());
        }
    }

    public function layout()
    {
        return new Carregador();
    }

    /**
     * Retorna uma string no formato json como mensagem de sucesso
     * @param string $retorno
     * @return string json
     */
    public function jsonSucesso($retorno = '')
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
        $tratar = Valida::instancia();

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
            throw new ErrorException("Oops! Nenhum registro selecionado!");
        }

        return $selecionados;
    }

    /**
     * Retona verdadeiro caso o metodo da requisição seja POST
     * @return boolean
     */
    protected function metodoPOST()
    {
        //return \filter_input(\INPUT_SERVER, 'REQUEST_METHOD') === 'POST' ? true : false;
        return ($_SERVER['REQUEST_METHOD']) === 'POST' ? true : false;
    }

    /**
     * Retona verdadeiro caso o metodo da requisição seja GET
     * @return boolean
     */
    protected function metodoGET()
    {
        //return \filter_input(\INPUT_SERVER, 'REQUEST_METHOD') === 'GET' ? true : false;
        return ($_SERVER['REQUEST_METHOD']) === 'GET' ? true : false;
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
     * Recupera o valor da variavel cod enviada via GET
     * @return int
     */
    protected function getCod()
    {
        return \filter_input(\INPUT_GET, 'cod');
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

        $tabs = new Tab('Tab' . $cod, 12);

        for ($i = 0; $i < $numArgs; $i++) {

            if ($i == 0) {
                continue;
            }

            $ativa = $i === 1 ? true : false;

            $objForm = \func_get_arg($i);

            if (\get_class($objForm) == 'stdClass') {
                $retorno = $objForm->conteudo;
                $abaNome = $objForm->tabNome;
            } else {
                if ($this->acao === 'visualizar') {
                    $retorno = $objForm->montaFormVisualizar();
                } else {
                    $retorno = $objForm->montaForm();
                    $retorno .= $objForm->javaScript()->getLoad(true);
                    $objForm->javaScript()->resetLoad();
                }

                $nomeAtual = $objForm->getConfig()->getHeader();
                $abaNome = $nomeAtual ? $nomeAtual : 'Aba ' . $i;
            }

            $tabs->config($i)->setAtiva($ativa)->setTitulo($abaNome)->setConteudo($retorno);
        }

        return $tabs->criar();
    }

}
