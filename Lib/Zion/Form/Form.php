<?php

namespace Zion\Form;

class Form
{

    protected $formConfig;
    protected $objetos;
    protected $formHtml;

    public function __construct()
    {
        $this->formHtml = new \Zion\Form\FormHtml();

        $this->formConfig = new \Zion\Form\FormTag();

        $this->formConfig->setNome('Form1')
                ->setMethod('POST');
    }

    public function layout($nome, $conteudo)
    {
        return new \Zion\Form\FormLayout($nome, $conteudo);
    }
    
    public function hidden($nome)
    {
        return new \Zion\Form\FormInputHidden('hidden', $nome);
    }

    public function texto($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('texto', $nome, $identifica, $obrigatorio);
    }

    public function data($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputData('data', $nome, $identifica, $obrigatorio);
    }

    public function hora($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputHora('hora', $nome, $identifica, $obrigatorio);
    }

    public function senha($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputSenha('password', $nome, $identifica, $obrigatorio);
    }

    public function numero($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputNumber('number', $nome, $identifica, $obrigatorio);
    }

    public function float($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputFloat('float', $nome, $identifica, $obrigatorio);
    }

    public function cpf($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputCpf('cpf', $nome, $identifica, $obrigatorio);
    }

    public function cnpj($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputCnpj('cnpj', $nome, $identifica, $obrigatorio);
    }

    public function cep($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputCep('cep', $nome, $identifica, $obrigatorio);
    }

    public function telefone($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTelefone('telefone', $nome, $identifica, $obrigatorio);
    }

    public function email($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputEmail('email', $nome, $identifica, $obrigatorio);
    }

    public function escolha()
    {
        return new \Zion\Form\FormEscolha('escolha');
    }

    public function textArea($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTextArea('textArea', $nome, $identifica, $obrigatorio);
    }

    public function upload($nome, $identifica, $obrigatorio = false)
    {
        throw new \BadMethodCallException("Aina nao implementado");
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
            case "POST" : $valor = filter_input(INPUT_POST, $nome);
                break;
            case "GET" : $valor = filter_input(INPUT_GET, $nome);
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
    
    public function getObjetos()
    {
        return $this->objetos;
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
                case 'layout':
                    $htmlCampos[$objCampos->getNome()] = $this->formHtml->montaLayout($objCampos);
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
}
