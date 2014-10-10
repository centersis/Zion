<?php

namespace Pixel\Form;

class Form extends \Zion\Form\Form
{

    private $formPixel;
    private $html;

    public function __construct()
    {
        parent::__construct();

        $this->formPixel = new \Pixel\Form\FormHtml();
        $this->html = new \Zion\Layout\Html();
    }

    public function texto($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTexto('texto', $nome, $identifica, $obrigatorio);
    }

    public function suggest($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputSuggest('suggest', $nome, $identifica, $obrigatorio);
    }

    public function data($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputDateTime('date', $nome, $identifica, $obrigatorio);
    }

    public function hora($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputDateTime('time', $nome, $identifica, $obrigatorio);
    }

    public function senha($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTexto('password', $nome, $identifica, $obrigatorio);
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

    public function abreForm()
    {
        $ret = $this->html->abreTagAberta('div', array('class' => 'panel'));
            $ret .= $this->html->abreTagAberta('div', array('class' => 'panel-heading'));
            $ret .= $this->html->abreTagAberta('span', array('class' => 'panel-title'));
                $ret .= $this->formConfig->getHeader();
            $ret .= $this->html->fechaTag('span');
            $ret .= $this->html->fechaTag('div');
        $ret .= $this->html->abreTagAberta('div', array('class' => 'panel-body'));

        $this->formConfig->setClassCss($this->formConfig->getClassCss() . ' form-horizontal');

        $ret .= parent::abreForm();

        return $ret;
    }

    public function fechaForm()
    {
        $ret = parent::fechaForm();
        $ret .= $this->html->fechaTag('div');
        $ret .= $this->html->fechaTag('div');

        return $ret;
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
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaTexto($objCampos);
                    break;
                case 'suggest' :
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaSuggest($objCampos);
                    break;
                case 'dateTime' :
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaDateTime($objCampos);
                    break;
                case 'number' :
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaNumber($objCampos);
                    break;
                case 'float' :
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaFloat($objCampos);
                    break;
                case 'cpf' :
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaTexto($objCampos);
                    break;
                case 'escolha':
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaEscolha($objCampos);
                    break;
                case 'button':
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaButton($objCampos);
                    break;
                case 'layout':
                    $htmlCampos[$objCampos->getNome()] = $this->formHtml->montaLayout($objCampos);
                    break;
                default : throw new Exception('Tipo Base não encontrado!');
            }
        }

        return $nome ? $htmlCampos[$nome] : $htmlCampos;
    }

    /**
     * 
     * @return FormJavaScript
     */
    public function javaScript()
    {
        $smartJs = new \Pixel\Form\FormPixelJavaScript();
        $jsStatic = \Pixel\Form\FormJavaScript::iniciar();

        foreach ($this->objetos as $config) {
            $smartJs->processar($config);
        }

        $jsStatic->setLoad($smartJs->montaValidacao($this->formConfig->getNome()));

        return $jsStatic;
    }

    public function montaForm()
    {
        $buffer = $this->abreForm();

        $footer = '';
        
        $campos = $this->getFormHtml();
        foreach ($campos as $nome => $textoHtml) {
            if ($this->objetos[$nome]->getTipoBase() == 'button') {
                $footer.= $textoHtml."&nbsp;&nbsp;";
            } else {
                $buffer.= $textoHtml;
            }
        }

        if ($footer) {
            $buffer.= $this->html->abreTagAberta('div',array('class'=>'form-group'));          
            $buffer.= $this->html->abreTagAberta('div',array('class'=>'col-sm-12'));
            
            $buffer.= $this->html->abreTagFechada('hr', array('class'=>'panel-wide'));
            
            $buffer.= $this->html->fechaTag('div');
            $buffer.= $this->html->fechaTag('div');
            
            $buffer.= $this->html->abreTagAberta('div',array('class'=>'form-group'));          
            $buffer.= $this->html->abreTagAberta('div',array('class'=>'col-sm-offset-3 col-sm-9'));
            
            $buffer.= $footer;
            
            $buffer.= $this->html->fechaTag('div');
            $buffer.= $this->html->fechaTag('div');
        }

        $buffer .= $this->fechaForm();

        return $buffer;
    }

}
