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
    
    protected function imprimir()
    {
        new \Zion\Acesso\Acesso('imprimir');
        
        $impressao = new \Pixel\Grid\Impressao();
        
        $dados = json_decode($this->filtrar(), true);
        
        $impressao->setLogo('http:'. SIS_URL_BASE . 'Arquivos/logo_exemplo.jpg');
        
        $retorno = $impressao->imprimeHTML($dados['retorno']);

        if($retorno === false){
            return $this->jsonErro('Falaha ao gerar PDF para impressão!');
        } else {
            return $retorno;
        }
    }
    
    protected function salvarPDF()
    {
        new \Zion\Acesso\Acesso('salvarPDF');

        $impressao = new \Pixel\Grid\Impressao();

        $dados = json_decode($this->filtrar(), true);

        $impressao->setLogo('http:'. SIS_URL_BASE . 'Arquivos/logo_exemplo.jpg');
        if($impressao->imprimePDF($dados['retorno']) === false){
            return $this->jsonErro('Falaha ao gerar PDF para impressão!');
        } else {
            return $this->jsonSucesso('PDF gerado com sucesso!');
        }
    }

}