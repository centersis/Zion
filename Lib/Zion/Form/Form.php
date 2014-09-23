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

    public function hidden()
    {
        return new \Zion\Form\FormInputHidden('hidden');
    }

    public function texto()
    {
        return new \Zion\Form\FormInputTexto('texto');
    }

    public function suggest()
    {
        return new \Zion\Form\FormInputSuggest('suggest');
    }

    public function data()
    {
        return new \Zion\Form\FormInputDateTime('date');
    }

    public function hora()
    {
        return new \Zion\Form\FormInputDateTime('time');
    }

    public function senha()
    {
        return new \Zion\Form\FormInputTexto('password');
    }

    public function numero()
    {
        return new \Zion\Form\FormInputNumber('number');
    }

    public function float()
    {
        return new \Zion\Form\FormInputTexto('moeda');
    }

    public function cpf()
    {
        return new \Zion\Form\FormInputTexto('cpf');
    }

    public function cnpj()
    {
        return new \Zion\Form\FormInputTexto('cnpj');
    }

    public function cep()
    {
        return new \Zion\Form\FormInputTexto('cep');
    }

    public function telefone()
    {
        return new \Zion\Form\FormInputTexto('telefone');
    }

    public function email()
    {
        return new \Zion\Form\FormInputTexto('email');
    }

    public function escolha()
    {
        return new \Zion\Form\FormEscolha('escolha');
    }

    public function textArea()
    {
        return new \Zion\Form\FormInputTexto('email');
    }

    public function editor()
    {
        return new \Zion\Form\FormInputTexto('email');
    }

    public function upload()
    {
        return new \Zion\Form\FormInputTexto('email');
    }

    public function botaoSubmit()
    {
        return new \Zion\Form\FormInputButton('bubmit');
    }

    public function botaoSimples()
    {
        return new \Zion\Form\FormInputButton('button');
    }

    public function botaoReset()
    {
        return new \Zion\Form\FormInputButton('reset');
    }

    /**
     * @return FormTag 
     */
    public function config()
    {
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

    public function validar()
    {
        foreach ($this->objetos as $obj) {
            
        }
    }

}
