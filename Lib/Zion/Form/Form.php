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

namespace Zion\Form;

use Zion\Form\Exception\FormException;
use Zion\Form\FormLayout;
use Zion\Form\FormHtml;
use Zion\Form\FormTag;
use Zion\Form\FormInputHidden;
use Zion\Form\FormInputTexto;
use Zion\Form\FormInputData;
use Zion\Form\FormInputHora;
use Zion\Form\FormInputSenha;
use Zion\Form\FormInputNumber;
use Zion\Form\FormInputFloat;
use Zion\Form\FormInputCpf;
use Zion\Form\FormInputCnpj;
use Zion\Form\FormInputCep;
use Zion\Form\FormInputTelefone;
use Zion\Form\FormInputEmail;
use Zion\Form\FormEscolha;
use Zion\Form\FormInputTextArea;
use Zion\Form\FormUpload;
use Zion\Form\FormInputButton;
use Zion\Tratamento\Tratamento;
use Zion\Form\FormValida;

class Form
{

    protected $formConfig;
    protected $objetos;
    protected $formHtml;
    protected $acao;

    public function __construct()
    {
        $this->formHtml = new FormHtml();

        $this->formConfig = new FormTag();

        $this->formConfig->setNome('formManu')
                ->setMethod('POST');

        $this->objetos = [];
    }

    public function layout($nome, $conteudo)
    {
        return new FormLayout($nome, $conteudo);
    }

    public function hidden($nome)
    {
        return new FormInputHidden('hidden', $nome);
    }

    public function texto($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputTexto('texto', $nome, $identifica, $obrigatorio);
    }

    public function data($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputData('data', $nome, $identifica, $obrigatorio);
    }

    public function hora($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputHora('hora', $nome, $identifica, $obrigatorio);
    }

    public function senha($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputSenha('password', $nome, $identifica, $obrigatorio);
    }

    public function numero($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputNumber('number', $nome, $identifica, $obrigatorio);
    }

    public function float($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputFloat('float', $nome, $identifica, $obrigatorio);
    }

    public function cpf($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputCpf('cpf', $nome, $identifica, $obrigatorio);
    }

    public function cnpj($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputCnpj('cnpj', $nome, $identifica, $obrigatorio);
    }

    public function cep($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputCep('cep', $nome, $identifica, $obrigatorio);
    }

    public function telefone($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputTelefone('telefone', $nome, $identifica, $obrigatorio);
    }

    public function email($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputEmail('email', $nome, $identifica, $obrigatorio);
    }

    public function escolha()
    {
        return new FormEscolha('escolha');
    }

    public function textArea($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputTextArea('textarea', $nome, $identifica, $obrigatorio);
    }

    public function upload($nome, $identifica, $obrigatorio = false)
    {
        return new FormUpload('upload', $nome, $identifica, $obrigatorio);
    }

    public function botaoSubmit($nome, $identifica)
    {
        return new FormInputButton('submit', $nome, $identifica);
    }

    public function botaoSimples($nome, $identifica)
    {
        return new FormInputButton('button', $nome, $identifica);
    }

    public function botaoReset($nome, $identifica)
    {
        return new FormInputButton('reset', $nome, $identifica);
    }

    public function config($nome, $metodo = 'GET')
    {
        $this->formConfig->setNome($nome)->setMethod($metodo);

        return $this->formConfig;
    }

    public function getConfig()
    {
        return $this->formConfig;
    }

    public function processarForm(array $campos)
    {
        foreach ($campos as $objCampos) {

            if (\method_exists($objCampos, 'setNomeForm')) {
                $objCampos->setNomeForm($this->formConfig->getNome());
            }

            $this->objetos[$objCampos->getNome()] = $objCampos;
        }

        return $this;
    }

    public function retornaValor($nome, $metodo = '')
    {

        if ($metodo) {

            $metodo = \strtoupper($metodo);

            if ($metodo == 'REQUEST') {

                if (\array_key_exists($nome, $_GET)) {

                    $metodo = 'GET';
                } elseif (\array_key_exists($nome, $_POST)) {

                    $metodo = 'POST';
                }
            }

            $metodoOriginal = $this->formConfig->getMethod();
            $this->formConfig->setMethod($metodo);
        }

        switch ($this->formConfig->getMethod()) {
            case "POST" :

                if (\substr_count($nome, '[]') > 0) {
                    $valor = \filter_input(\INPUT_POST, \str_replace('[]', '', $nome), \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);
                } else {
                    $valor = \filter_input(\INPUT_POST, $nome);
                }

                break;
            case "GET" :

                if (\substr_count($nome, '[]') > 0) {
                    $valor = \filter_input(\INPUT_GET, \str_replace('[]', '', $nome), \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);
                } else {
                    $valor = \filter_input(\INPUT_GET, $nome);
                }

                break;

            default: $valor = null;
        }

        if ($metodo) {

            $this->formConfig->setMethod($metodoOriginal);
        }

        return $valor;
    }

    public function set($nome, $valor, $tipo = 'texto')
    {
        if (!\is_null($nome) or ! \is_null($nome)) {

            if (\array_key_exists($nome, $this->objetos)) {
                $this->objetos[$nome]->setValor($valor);
            } else {
                switch (\strtolower($tipo)) {

                    case 'float': case 'double': case 'decimal':
                        $this->objetos[$nome] = new FormInputFloat('float', $nome, '-', false);
                        break;
                    case 'numero': case 'inteiro': case 'int':
                        $this->objetos[$nome] = new FormInputNumber('number', $nome, '-', false);
                        break;
                    case 'data': case 'date':
                        $this->objetos[$nome] = new FormInputData('date', $nome, '-', false);
                        break;
                    case 'datahora': case 'datetime':
                        $this->objetos[$nome] = new FormInputDataHora('dateTime', $nome, '-', false);
                        break;
                    default :
                        $this->objetos[$nome] = new FormInputTexto('texto', $nome, '-', false);
                }
                $this->objetos[$nome]->setValor($valor);
            }
        } else {
            throw new FormException("set: Falta um argumento.");
        }
    }

    public function get($nome)
    {
        if (\array_key_exists($nome, $this->objetos)) {
            return $this->objetos[$nome]->getValor();
        } else {
            return NULL;
        }
    }

    public function getTipoPDO($idObjeto)
    {
        $valor = $this->objetos[$idObjeto]->getValor();
        $tipoBase = $this->objetos[$idObjeto]->getTipoBase();

        switch (\strtolower($tipoBase)) {
            case 'data' : case 'date' :

                return $valor == '' ? \PDO::PARAM_NULL : \PDO::PARAM_STR;

            case 'datahora' : case 'datetime' :

                return $valor == '' ? \PDO::PARAM_NULL : \PDO::PARAM_STR;

            case 'float' : case 'double' :

                return $valor == '' ? \PDO::PARAM_NULL : \PDO::PARAM_STR;

            case 'number' : case 'numero' :

                return $valor == '' ? \PDO::PARAM_NULL : \PDO::PARAM_INT;

            default:

                if (\is_array($valor)) {

                    $vArray = \implode(',', $valor);
                    return empty($vArray) ? \PDO::PARAM_NULL : \PDO::PARAM_STR;
                } else {

                    return $valor == '' ? \PDO::PARAM_NULL : \PDO::PARAM_STR;
                }
        }
    }

    public function unsetObjeto($idObjeto)
    {

        if (\array_key_exists($idObjeto, $this->objetos)) {
            unset($this->objetos[$idObjeto]);
        }
    }

    public function getSql($idObjeto)
    {
        $tratar = Tratamento::instancia();

        $valor = $this->objetos[$idObjeto]->getValor();
        $tipoBase = $this->objetos[$idObjeto]->getTipoBase();

        switch (\strtolower($tipoBase)) {
            case 'data' : case 'date' :

                return empty($valor) ? NULL : $valor;

            case 'datahora' : case 'datetime' :

                if (\strlen($valor) === 16) {
                    $valor = $valor . ':00';
                }

                $dataHoraConvertida = $valor;

                return empty($dataHoraConvertida) ? NULL : $dataHoraConvertida;

            case 'float' : case 'double' :
                $float = $tratar->numero()->floatBanco($valor);

                return \is_numeric($float) ? $float : NULL;

            case 'number' : case 'numero' : case 'inteiro' :
                return \is_numeric($valor) ? $valor : NULL;

            default:

                if (\is_array($valor)) {
                    $arr = \implode(',', $valor);

                    return empty($arr) ? NULL : $arr;
                } else {
                    return $valor == '' ? NULL : $valor;
                }
        }
    }

    public function getFiltroSql($idObjeto)
    {
        $tratar = Tratamento::instancia();

        $valor = $this->objetos[$idObjeto]->getValor();
        $tipoBase = $this->objetos[$idObjeto]->getTipoBase();

        switch ($tipoBase) {
            case 'data' : case 'date' :
                $dataConvertida = $tratar->data()->converteData($valor);
                return $dataConvertida;

            case 'float' : case 'double' :
                $valorConvertido = $tratar->numero()->floatBanco($valor);
                return $valorConvertido;

            case 'number' : case 'numero' : case 'inteiro' :
                return $valor;

            default:
                return $valor;
        }
    }

    public function getObjetos($nome = null)
    {
        if ($nome and ! \array_key_exists($nome, $this->objetos)) {
            throw new \Exception('Objeto ' . $nome . ' não existe!');
        }

        return $nome ? $this->objetos[$nome] : $this->objetos;
    }

    public function getFormHtml($nome = null)
    {
        $htmlCampos = [];

        $obj = $nome ? [$nome => $this->objetos[$nome]] : $this->objetos;

        foreach ($obj as $idCampo => $objCampos) {
            switch ($objCampos->getTipoBase()) {
                case 'hidden' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaHidden($objCampos);
                    break;
                case 'texto' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaTexto($objCampos);
                    break;
                case 'senha' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaSenha($objCampos);
                    break;
                case 'textarea' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaTextArea($objCampos);
                    break;
                case 'data' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaData($objCampos);
                    break;
                case 'hora' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaHora($objCampos);
                    break;
                case 'number' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaNumber($objCampos);
                    break;
                case 'float' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaFloat($objCampos);
                    break;
                case 'cpf' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaTexto($objCampos);
                    break;
                case 'escolha':
                    $htmlCampos[$idCampo] = $this->formHtml->montaEscolha($objCampos);
                    break;
                case 'button':
                    $htmlCampos[$idCampo] = $this->formHtml->montaButton($objCampos);
                    break;
                case 'upload':
                    $htmlCampos[$idCampo] = $this->formHtml->montaUpload($objCampos);
                    break;
                case 'layout':
                    $htmlCampos[$idCampo] = $this->formHtml->montaLayout($objCampos);
                    break;
                default : throw new FormException('Tipo Base não encontrado!');
            }
        }

        return $nome ? $htmlCampos[$nome] : $htmlCampos;
    }

    public function validar($nome = null)
    {
        $valida = new FormValida();

        $obj = $nome ? array($this->objetos[$nome]) : $this->objetos;

        foreach ($obj as $objCampos) {
            $valida->validar($objCampos);
        }
    }

    public function setAcao($acao)
    {
        $this->acao = $acao;
    }

    public function getAcao()
    {
        return $this->acao;
    }

}
