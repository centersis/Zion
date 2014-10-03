<?php

namespace Zion\Form;

class Form
{

    private $formConfig;
    private $objetos;
    private $formHtml;

    public function __construct()
    {
        $this->formHtml = new \Zion\Form\FormHtml();

        $this->formConfig = new \Zion\Form\FormTag();

        $this->formConfig->setNome('Form1')
                ->setMethod('POST');
    }

    public function hidden($nome)
    {
        return new \Zion\Form\FormInputHidden('hidden', $nome);
    }

    public function texto($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('texto', $nome, $identifica, $obrigatorio);
    }

    public function suggest($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputSuggest('suggest', $nome, $identifica, $obrigatorio);
    }

    public function data($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputDateTime('date', $nome, $identifica, $obrigatorio);
    }

    public function hora($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputDateTime('time', $nome, $identifica, $obrigatorio);
    }

    public function senha($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('password', $nome, $identifica, $obrigatorio);
    }

    public function numero($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputNumber('number', $nome, $identifica, $obrigatorio);
    }

    public function float($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('moeda', $nome, $identifica, $obrigatorio);
    }

    public function cpf($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('cpf', $nome, $identifica, $obrigatorio);
    }

    public function cnpj($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('cnpj', $nome, $identifica, $obrigatorio);
    }

    public function cep($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('cep', $nome, $identifica, $obrigatorio);
    }

    public function telefone($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('telefone', $nome, $identifica, $obrigatorio);
    }

    public function email($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('email', $nome, $identifica, $obrigatorio);
    }

    public function escolha()
    {
        return new \Zion\Form\FormEscolha('escolha');
    }

    public function textArea($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('email', $nome, $identifica, $obrigatorio);
    }

    public function editor($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('email', $nome, $identifica, $obrigatorio);
    }

    public function upload($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('email', $nome, $identifica, $obrigatorio);
    }

    public function botaoSubmit($nome, $identifica)
    {
        return new \Zion\Form\FormInputButton('bubmit', $nome, $identifica);
    }

    public function botaoSimples($nome, $identifica)
    {
        return new \Zion\Form\FormInputButton('button', $nome, $identifica);
    }

    public function botaoReset($nome, $identifica)
    {
        return new \Zion\Form\FormInputButton('reset', $nome, $identifica);
    }

    /**
     * @return FormTag 
     */
    public function config($nome, $metodo)
    {
        $this->formConfig->setNome($nome)->setMethod($metodo);
        
        return $this->formConfig;
    }

    public function abreForm()
    {
        return $this->formHtml->abreForm($this->formConfig);
    }

    public function fechaForm()
    {
        return $this->formHtml->fechaForm();
    }

    public function processarForm(array $campos)
    {
        foreach ($campos as $objCampos) {
            $this->objetos[$objCampos->getNome()] = $objCampos;
        }

        return $this;
    }

    public function retornaValor($nome)
    {
        switch ($this->formConfig->getMethod()) {
            case "POST" : $valor = @$_POST[$nome];
                break;
            case "GET" : $valor = @$_GET[$nome];
                break;
            default: $valor = null;
        }

        return $valor;
    }

    public function set($nome, $valor)
    {
        if (!is_null($nome) or ! is_null($nome)) {
            $this->objetos[$nome]->setValor($valor);
        } else {
            throw new FormException("set: Falta um argumento.");
        }
    }

    public function get($nome)
    {
        return $this->objetos[$nome]->getValor();
    }

    public function getFormHtml($nome = null)
    {
        $htmlCampos = array();

        $obj = $nome ? array($this->objetos[$nome]) : $this->objetos;

        foreach ($obj as $objCampos) {
            switch ($objCampos->getTipoBase()) {
                case 'hidden' :
                    $htmlCampos[$objCampos->getNome()] = $this->formHtml->montaHidden($objCampos);
                    break;
                case 'texto' :
                    $htmlCampos[$objCampos->getNome()] = $this->formHtml->montaTexto($objCampos);
                    break;
                case 'suggest' :
                    $htmlCampos[$objCampos->getNome()] = $this->formHtml->montaSuggest($objCampos);
                    break;
                case 'dateTime' :
                    $htmlCampos[$objCampos->getNome()] = $this->formHtml->montaDateTime($objCampos);
                    break;
                case 'number' :
                    $htmlCampos[$objCampos->getNome()] = $this->formHtml->montaNumber($objCampos);
                    break;
                case 'float' :
                    $htmlCampos[$objCampos->getNome()] = $this->formHtml->montaFloat($objCampos);
                    break;
                case 'cpf' :
                    $htmlCampos[$objCampos->getNome()] = $this->formHtml->montaTexto($objCampos);
                    break;
                case 'escolha':
                    $htmlCampos[$objCampos->getNome()] = $this->formHtml->montaEscolha($objCampos);
                    break;
                case 'button':
                    $htmlCampos[$objCampos->getNome()] = $this->formHtml->montaButton($objCampos);
                    break;
                default : throw new Exception('Tipo Base não encontrado!');
            }
        }

        return $nome ? $htmlCampos[$nome] : $htmlCampos;
    }

    public function validar($nome = null)
    {
        $valida = new \Zion\Form\FormValida();

        $obj = $nome ? array($this->objetos[$nome]) : $this->objetos;

        foreach ($obj as $objCampos) {
            $valida->validar($objCampos);
        }
    }

    /**
     * 
     * @return FormJavaScript
     */
    public function javaScript()
    {
        $smartJs = new \Zion\Form\FormSmartJavaScript();
        $jsStatic = \Zion\Form\FormJavaScript::iniciar();

        foreach ($this->objetos as $config) {
            $smartJs->processar($config);
        }

        $jsStatic->setLoad($smartJs->montaValidacao($this->formConfig->getNome()));

        return $jsStatic;
    }

    public function montaForm()
    {
        $html = new \Zion\Layout\Html();

        $footer = '';
        $buffer = $this->abreForm();

        $buffer.= $html->abreTagAberta('header');
        $buffer.= $this->formConfig->getHeader();
        $buffer.= $html->fechaTag('header');

        $buffer.= $html->abreTagAberta('fieldset');
        $campos = $this->getFormHtml();
        foreach ($campos as $nome => $textoHtml) {
            if ($this->objetos[$nome]->getTipoBase() == 'button') {
                $footer.= $textoHtml;
            } else {
                $buffer.= $textoHtml;
            }
        }
        $buffer.= $html->fechaTag('fieldset');

        if ($footer) {
            $buffer.= $html->abreTagAberta('footer');
            $buffer.= $footer;
            $buffer.= $html->fechaTag('footer');
        }

        $buffer .= $this->abreForm();

        return $buffer;
    }

}
