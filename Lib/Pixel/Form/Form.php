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
        return new \Pixel\Form\FormInputData('data', $nome, $identifica, $obrigatorio);
    }

    public function hora($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputHora('hora', $nome, $identifica, $obrigatorio);
    }

    public function senha($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTexto('password', $nome, $identifica, $obrigatorio);
    }

    public function numero($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputNumber('number', $nome, $identifica, $obrigatorio);
    }

    public function float($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTexto('float', $nome, $identifica, $obrigatorio);
    }

    public function cpf($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTexto('cpf', $nome, $identifica, $obrigatorio);
    }

    public function cnpj($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTexto('cnpj', $nome, $identifica, $obrigatorio);
    }

    public function cep($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTexto('cep', $nome, $identifica, $obrigatorio);
    }

    public function telefone($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTexto('telefone', $nome, $identifica, $obrigatorio);
    }

    public function email($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('email', $nome, $identifica, $obrigatorio);
    }

    public function escolha($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormEscolha('escolha',$nome, $identifica, $obrigatorio);
    }
    
    public function chosen($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormChosen('chosen',$nome, $identifica, $obrigatorio);
    }

    public function textArea($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTextArea('textarea', $nome, $identifica, $obrigatorio);
    }

    public function editor($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTextArea('editor', $nome, $identifica, $obrigatorio);
    }

    public function upload($nome, $identifica, $obrigatorio = false)
    {
        return new \Zion\Form\FormInputTexto('email', $nome, $identifica, $obrigatorio);
    }

    public function botaoSubmit($nome, $identifica)
    {
        return new \Zion\Form\FormInputButton('submit', $nome, $identifica);
    }

    /**
     * @return \Zion\Form\FormInputButton
     */
    public function botaoSalvarPadrao()
    {
        $botaoSalvar = new \Zion\Form\FormInputButton('submit', 'sisSalvar', 'Salvar');
        $botaoSalvar->setClassCss('btn btn-primary');

        return $botaoSalvar;
    }

    public function botaoDescartarPadrao($nomeForm)
    {
        $botaoDescartar = new \Zion\Form\FormInputButton('button', 'sisDescartar', 'Descartar');

        $botaoDescartar->setClassCss('btn btn-default')
                ->setComplemento('onclick="sisDescartarPadrao(\'' . $nomeForm . '\')"');

        return $botaoDescartar;
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
        $ret = $this->html->abreTagAberta('div', array('class' => 'panel', 'id' => 'panel' . $this->formConfig->getNome()));
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
                case 'textarea' :
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaTextArea($objCampos);
                    break;
                case 'editor' :
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaTextArea($objCampos);
                    break;
                case 'suggest' :
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaSuggest($objCampos);
                    break;
                case 'data' :
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaData($objCampos);
                    break;
                case 'hora' :
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaHora($objCampos);
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
                case 'chosen':
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaEscolha($objCampos);
                    break;
                case 'button':
                    $htmlCampos[$objCampos->getNome()] = $this->formPixel->montaButton($objCampos);
                    break;
                case 'layout':
                    $htmlCampos[$objCampos->getNome()] = $this->formHtml->montaLayout($objCampos);
                    break;
                default : throw new \Exception('Tipo Base não encontrado!');
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

        $jsStatic->setLoad($smartJs->montaValidacao($this->formConfig->getNome(), $this->getAcao()));

        return $jsStatic;
    }

    public function montaForm()
    {
        $buffer = $this->abreForm();

        $footer = '';

        $campos = $this->getFormHtml();
        foreach ($campos as $nome => $textoHtml) {
            if ($this->objetos[$nome]->getTipoBase() == 'button') {
                $footer.= $textoHtml . "&nbsp;&nbsp;";
            } else {
                $buffer.= $textoHtml;
            }
        }

        if ($footer) {
            $buffer.= $this->html->abreTagAberta('div', array('class' => 'form-group'));
            $buffer.= $this->html->abreTagAberta('div', array('class' => 'col-sm-12'));

            $buffer.= $this->html->abreTagFechada('hr', array('class' => 'panel-wide'));

            $buffer.= $this->html->fechaTag('div');
            $buffer.= $this->html->fechaTag('div');

            $buffer.= $this->html->abreTagAberta('div', array('class' => 'form-group'));
            $buffer.= $this->html->abreTagAberta('div', array('class' => 'col-sm-offset-3 col-sm-9'));

            $buffer.= $footer;

            $buffer.= $this->html->fechaTag('div');
            $buffer.= $this->html->fechaTag('div');
        }

        $buffer .= $this->fechaForm();

        return $buffer;
    }

    public function montaFormVisualizar()
    {
        $buffer = $this->abreForm();

        $footer = '';

        //Desabilita campos
        foreach ($this->objetos as $objeto) {
            if (method_exists($objeto, 'setDisabled')) {

                if ($objeto->getAcao() !== 'button') {
                    $objeto->setDisabled(true);
                }
            }
        }

        $campos = $this->getFormHtml();
        foreach ($campos as $nome => $textoHtml) {

            if ($this->objetos[$nome]->getTipoBase() == 'button') {

                if ($this->objetos[$nome]->getAcao() == 'button') {
                    $footer.= $textoHtml . "&nbsp;&nbsp;";
                }
            } else {
                $buffer.= $textoHtml;
            }
        }

        if ($footer) {
            $buffer.= $this->html->abreTagAberta('div', array('class' => 'form-group'));
            $buffer.= $this->html->abreTagAberta('div', array('class' => 'col-sm-12'));

            $buffer.= $this->html->abreTagFechada('hr', array('class' => 'panel-wide'));

            $buffer.= $this->html->fechaTag('div');
            $buffer.= $this->html->fechaTag('div');

            $buffer.= $this->html->abreTagAberta('div', array('class' => 'form-group'));
            $buffer.= $this->html->abreTagAberta('div', array('class' => 'col-sm-offset-3 col-sm-9'));

            $buffer.= $footer;

            $buffer.= $this->html->fechaTag('div');
            $buffer.= $this->html->fechaTag('div');
        }

        $buffer .= $this->fechaForm();

        return $buffer;
    }

}
