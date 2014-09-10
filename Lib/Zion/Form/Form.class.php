<?php

namespace Zion\Form;

class Form extends FormHtml
{
    private $metodo;
    private $formValues;
    private $processarHtml;
    private $processarJs;
    private $formHtml;

    public function __construct()
    {
        $this->metodo = 'POST';
        $this->formValues = array();
        $this->processarHtml = true;
        $this->processarJs = true;
        $this->formHtml = array();
    }

    public function texto()
    {
        return new FormInputTextoVo('texto');
    }

    public function data()
    {
        return new FormInputDateVo('date');
    }

    public function hora()
    {
        return new FormInputDateVo('time');
    }

    public function senha()
    {
        return new FormInputTextoVo('password');
    }

    public function numero()
    {
        return new FormInputNumberVo('number');
    }

    public function float()
    {
        return new FormInputTextoVo('moeda');
    }

    public function botaoSimples()
    {
        return new FormInputButtonVo('button');
    }
    
    public function cpf()
    {
        return new FormInputTextoVo('texto');
    }
    
    public function mail()
    {
        return new FormInputTextoVo('email');
    }

    public function botaoSubmit()
    {
        return new FormInputButtonVo('bubmit');
    }

    public function botaoReset()
    {
        return new FormInputButtonVo('reset');
    }

    public function processarForm(array $campos)
    {
        $htmlCampos = array();

        foreach ($campos as $objCampos) {

            if ($this->processarHtml) {
                switch ($objCampos->getTipoBasico()) {
                    case 'texto' :
                        $htmlCampos[$objCampos->getNome()] = $this->montaTexto($objCampos);
                        break;
                    case 'date' :
                        $htmlCampos[$objCampos->getNome()] = $this->montaDate($objCampos);
                        break;
                    case 'date' :
                        $objCampos->setMascara('000.000.000-00');
                        $htmlCampos[$objCampos->getNome()] = $this->montaTexto($objCampos);
                        break;
                    case 'number' :                        
                        $htmlCampos[$objCampos->getNome()] = $this->montaNumber($objCampos);
                        break;
                    case 'float' :                        
                        $htmlCampos[$objCampos->getNome()] = $this->montaFloat($objCampos);
                        break;
                    case 'button':
                        $htmlCampos[$objCampos->getNome()] = $this->montaButton($objCampos);
                        break;
                }
            }

            $this->formValues[$objCampos->getNome()] = $objCampos->getValor();
        }

        if ($this->processarHtml) {
            $this->formHtml = $htmlCampos;
        }
        
        return $this;
    }

    public function retornaValor($metodo, $nome)
    {
        $metodo = strtoupper($metodo);

        switch ($metodo) {
            case "POST" : $valor = @$_POST[$nome];
                break;
            case "GET" : $valor = @$_GET[$nome];
                break;
            case "REQUEST": $valor = @$_REQUEST[$nome];
                break;
            case "SESSION": $valor = @$_SESSION[$nome];
                break;
            case "COOKIE" : $valor = @$_COOKIE[$nome];
                break;
            case "FILES" : $valor = @$_FILES[$nome];
                break;
            default: $valor = null;
        }

        return $valor;
    }

    public function setMetodo($metodo)
    {
        $this->metodo = $metodo;
    }

    public function getMetodo()
    {
        return $this->metodo;
    }

    public function set($nome, $valor)
    {
        $this->formValues[$nome] = $valor;
    }

    public function get($nome)
    {
        return $this->formValues[$nome];
    }

    public function setProcessarHtml($processarHtml)
    {
        $this->processarHtml = $processarHtml;
    }

    public function setProcessarJs($processarJs)
    {
        $this->processarJs = $processarJs;
    }

    public function getFormHtml($nome = null)
    {
        return $nome  ? $this->formHtml[$nome] : $this->formHtml;
    }
}
