<?php

/**
 * \Zion\Form\Form()
 * 
 * @author The Sappiens Team
 * @copyright Sappiens 2014
 * @version 2014
 * @access public
 */

namespace Zion\Form;

class Form
{

    protected $formConfig;
    protected $objetos;
    protected $formHtml;
    protected $acao;

    /**
     * Form::__construct()
     * 
     * @return
     */
    public function __construct()
    {
        $this->formHtml = new \Zion\Form\FormHtml();

        $this->formConfig = new \Zion\Form\FormTag();

        $this->formConfig->setNome('formManu')
                ->setMethod('POST');
    }

    /**
     * Form::layout()
     * 
     * @param mixed $nome
     * @param mixed $conteudo
     * @return
     */
    public function layout($nome, $conteudo)
    {
        return new \Zion\Form\FormLayout($nome, $conteudo);
    }

    /**
     * Form::hidden()
     * 
     * @param mixed $nome
     * @return
     */
    public function hidden($nome)
    {
        return new \Zion\Form\FormInputHidden('hidden', $nome);
    }

    /**
     * Form::texto()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function texto($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('texto', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::data()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function data($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputData('data', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::hora()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function hora($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputHora('hora', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::senha()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function senha($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputSenha('password', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::numero()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function numero($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputNumber('number', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::float()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function float($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputFloat('float', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::cpf()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function cpf($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputCpf('cpf', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::cnpj()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function cnpj($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputCnpj('cnpj', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::cep()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function cep($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputCep('cep', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::telefone()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function telefone($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTelefone('telefone', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::email()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function email($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputEmail('email', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::escolha()
     * 
     * @return
     */
    public function escolha()
    {
        return new \Zion\Form\FormEscolha('escolha');
    }

    /**
     * Form::textArea()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function textArea($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTextArea('textarea', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::upload()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @param bool $obrigatorio
     * @return
     */
    public function upload($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormUpload('upload', $nome, $identifica, $obrigatorio);
    }

    /**
     * Form::botaoSubmit()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @return
     */
    public function botaoSubmit($nome, $identifica)
    {
        return new \Zion\Form\FormInputButton('submit', $nome, $identifica);
    }

    /**
     * Form::botaoSimples()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @return
     */
    public function botaoSimples($nome, $identifica)
    {
        return new \Zion\Form\FormInputButton('button', $nome, $identifica);
    }

    /**
     * Form::botaoReset()
     * 
     * @param mixed $nome
     * @param mixed $identifica
     * @return
     */
    public function botaoReset($nome, $identifica)
    {
        return new \Zion\Form\FormInputButton('reset', $nome, $identifica);
    }

    /**
     * @return FormTag 
     */

    /**
     * Form::config()
     * 
     * @param mixed $nome
     * @param mixed $metodo
     * @return
     */
    public function config($nome, $metodo = 'GET')
    {
        $this->formConfig->setNome($nome)->setMethod($metodo);

        return $this->formConfig;
    }

    /**
     * Form::abreForm()
     * 
     * @return
     */
    public function abreForm()
    {
        return $this->formHtml->abreForm($this->formConfig);
    }

    /**
     * Form::fechaForm()
     * 
     * @return
     */
    public function fechaForm()
    {
        return $this->formHtml->fechaForm();
    }

    /**
     * Form::processarForm()
     * 
     * @param mixed $campos
     * @return Form
     */
    public function processarForm(array $campos)
    {
        foreach ($campos as $objCampos) {
            $this->objetos[$objCampos->getNome()] = $objCampos;
        }

        return $this;
    }

    /**
     * Form::retornaValor()
     * 
     * @param mixed $nome
     * @return
     */
    public function retornaValor($nome)
    {
        switch ($this->formConfig->getMethod()) {
            case "POST" :

                if (substr_count($nome, '[]') > 0) {
                    $valor = \filter_input(\INPUT_POST, \str_replace('[]', '', $nome), \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);
                } else {
                    $valor = \filter_input(\INPUT_POST, $nome);
                }

                break;
            case "GET" :

                if (substr_count($nome, '[]') > 0) {
                    $valor = \filter_input(\INPUT_GET, \str_replace('[]', '', $nome), \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);
                } else {
                    $valor = filter_input(\INPUT_GET, $nome);
                }

                break;
            default: $valor = null;
        }

        return $valor;
    }

    /**
     * Form::set()
     * 
     * @param mixed $nome
     * @param mixed $valor
     * @return
     */
    public function set($nome, $valor, $tipo = 'texto')
    {
        if (!\is_null($nome) or ! \is_null($nome)) {

            if (\key_exists($nome, $this->objetos)) {
                $this->objetos[$nome]->setValor($valor);
            } else {
                switch ($tipo) {

                    case 'float':
                        $this->objetos[$nome] = new \Zion\Form\FormInputFloat('number', $nome, '-', false);
                        break;
                    case 'numero':
                        $this->objetos[$nome] = new \Zion\Form\FormInputNumber('number', $nome, '-', false);
                        break;
                    case 'data':
                        $this->objetos[$nome] = new \Zion\Form\FormInputData('date', $nome, '-', false);
                        break;
                    default :
                        $this->objetos[$nome] = new \Zion\Form\FormInputTexto('texto', $nome, '-', false);
                }
                $this->objetos[$nome]->setValor($valor);
            }
        } else {
            throw new FormException("set: Falta um argumento.");
        }
    }

    /**
     * Form::get()
     * 
     * @param mixed $nome
     * @return
     */
    public function get($nome)
    {
        return $this->objetos[$nome]->getValor();
    }

    public function getSql($idObjeto)
    {
        $tratar = \Zion\Tratamento\Tratamento::instancia();

        $valor = $this->objetos[$idObjeto]->getValor();
        $obrigatorio = $this->objetos[$idObjeto]->getObrigatorio();
        $tipoBase = $this->objetos[$idObjeto]->getTipoBase();

        switch ($tipoBase) {
            case 'date' :
                $dataConvertida = $tratar->data()->converteData($valor);
                return $this->valorOuNull($dataConvertida, true, $obrigatorio);

            case 'float' :
                $valorConvertido = $tratar->numero()->floatBanco($valor);
                return $this->valorOuNull($valorConvertido, false, $obrigatorio);

            case 'number' :
                return $this->valorOuNull($valor, false, $obrigatorio);

            default:
                if (is_array($valor)) {
                    return $this->valorOuNull(implode(',', $valor), true, $obrigatorio);
                } else {
                    return $this->valorOuNull($valor, true, $obrigatorio);
                }
        }
    }

    public function getFiltroSql($idObjeto)
    {
        $tratar = \Zion\Tratamento\Tratamento::instancia();

        $valor = $this->objetos[$idObjeto]->getValor();
        $tipoBase = $this->objetos[$idObjeto]->getTipoBase();

        switch ($tipoBase) {
            case 'date' :
                $dataConvertida = $tratar->data()->converteData($valor);
                return "'$dataConvertida'";

            case 'float' :
                $valorConvertido = $tratar->numero()->floatBanco($valor);
                return $valorConvertido;

            case 'number' :
                return $valor;

            default:
                return "'$valor'";
        }
    }

    /**
     * Form::getObjetos()
     * 
     * @return
     */
    public function getObjetos()
    {
        return $this->objetos;
    }

    /**
     * Form::getFormHtml()
     * 
     * @param mixed $nome
     * @return
     */
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
                case 'textarea' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaTextArea($objCampos);
                    break;
                case 'dateTime' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaDateTime($objCampos);
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
                default : throw new Exception('Tipo Base não encontrado!');
            }
        }

        return $nome ? $htmlCampos[$nome] : $htmlCampos;
    }

    /**
     * Form::validar()
     * 
     * @param mixed $nome
     * @return
     */
    public function validar($nome = null)
    {
        $valida = new \Zion\Form\FormValida();

        $obj = $nome ? array($this->objetos[$nome]) : $this->objetos;

        foreach ($obj as $objCampos) {
            $valida->validar($objCampos);
        }
    }

    private function valorOuNull($valor, $aspas, $obrigatorio)
    {
        if ($obrigatorio and $valor == '') {
            return $valor;
        } else {
            if ($aspas === false) {
                return ($valor == '') ? 'NULL' : $valor;
            } else {
                return ($valor == '') ? 'NULL' : "'" . $valor . "'";
            }
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
