<?php
/*

    Sappiens Framework
    Copyright (C) 2014, BRA Consultoria

    Website do autor: www.braconsultoria.com.br/sappiens
    Email do autor: sappiens@braconsultoria.com.br

    Website do projeto, equipe e documentação: www.sappiens.com.br
   
    Este programa é software livre; você pode redistribuí-lo e/ou
    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
    publicada pela Free Software Foundation, versão 2.

    Este programa é distribuído na expectativa de ser útil, mas SEM
    QUALQUER GARANTIA; sem mesmo a garantia implícita de
    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
    detalhes.
 
    Você deve ter recebido uma cópia da Licença Pública Geral GNU
    junto com este programa; se não, escreva para a Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
    02111-1307, USA.

    Cópias da licença disponíveis em /Sappiens/_doc/licenca

*/

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
        return new \Pixel\Form\FormInputData('date', $nome, $identifica, $obrigatorio);
    }

    public function hora($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputHora('time', $nome, $identifica, $obrigatorio);
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
        return new \Pixel\Form\FormInputFloat('float', $nome, $identifica, $obrigatorio);
    }

    public function cpf($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputCpf('cpf', $nome, $identifica, $obrigatorio);
    }

    public function cnpj($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputCnpj('cnpj', $nome, $identifica, $obrigatorio);
    }

    public function cep($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputCep('cep', $nome, $identifica, $obrigatorio);
    }

    public function telefone($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTelefone('telefone', $nome, $identifica, $obrigatorio);
    }

    public function email($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputEmail('email', $nome, $identifica, $obrigatorio);
    }

    public function escolha($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormEscolha('escolha', $nome, $identifica, $obrigatorio);
    }

    public function chosen($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormChosen('chosen', $nome, $identifica, $obrigatorio);
    }

    public function textArea($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTextArea('textarea', $nome, $identifica, $obrigatorio);
    }

    public function editor($nome, $identifica, $obrigatorio = false)
    {
        return new \Pixel\Form\FormInputTextArea('editor', $nome, $identifica, $obrigatorio);
    }

    public function upload($nome, $identifica, $tratarComo)
    {
        return new \Pixel\Form\FormUpload('upload', $nome, $identifica, $tratarComo);
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
    
    public function botaoSalvarEContinuar()
    {
        $botaoSalvar = new \Zion\Form\FormInputButton('submit', 'sisSalvarEContinuar', 'Salvar');
        $botaoSalvar->setClassCss('btn btn-primary');

        return $botaoSalvar;
    }

    public function botaoDescartarPadrao()
    {
        $nomeForm = $this->getConfig()->getNome();
        
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

    public function abreFormManu()
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

    public function abreFormFiltro()
    {
        return $this->html->abreTagAberta('form', ['id' => $this->formConfig->getNome(), 'class' => 'form-horizontal']);
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
        $htmlCampos = [];

        $obj = $nome ? [$nome => $this->objetos[$nome]] : $this->objetos;

        foreach ($obj as $idCampo => $objCampos) {

            switch ($objCampos->getTipoBase()) {
                case 'hidden' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaHidden($objCampos);
                    break;
                case 'texto' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaTexto($objCampos);
                    break;
                case 'textarea' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaTextArea($objCampos);
                    break;
                case 'editor' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaTextArea($objCampos);
                    break;
                case 'suggest' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaSuggest($objCampos);
                    break;
                case 'data' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaData($objCampos);
                    break;
                case 'hora' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaHora($objCampos);
                    break;
                case 'number' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaNumber($objCampos);
                    break;
                case 'float' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaFloat($objCampos);
                    break;
                case 'cpf' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaCpf($objCampos);
                    break;                
                case 'cnpj' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaCnpj($objCampos);
                    break;
                case 'cep' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaCep($objCampos);
                    break;
                case 'escolha':
                    $htmlCampos[$idCampo] = $this->formPixel->montaEscolha($objCampos, $this);
                    break;
                case 'chosen':
                    $htmlCampos[$idCampo] = $this->formPixel->montaEscolha($objCampos);
                    break;
                case 'button':
                    $htmlCampos[$idCampo] = $this->formPixel->montaButton($objCampos);
                    break;
                case 'upload':                    
                    $htmlCampos[$idCampo] = $this->formPixel->montaUpload($objCampos);
                    break;
                case 'layout':
                    $htmlCampos[$idCampo] = $this->formHtml->montaLayout($objCampos);
                    break;
                default : throw new \Exception('Tipo Base não encontrado!: '.$idCampo);
            }
        }

        return $nome ? $htmlCampos[$nome] : $htmlCampos;
    }

    /**
     * 
     * @return FormJavaScript
     */
    public function javaScript($validacao = true, $javaScriptExtra = true)
    {
        $smartJs = new \Pixel\Form\FormPixelJavaScript();
        $jsStatic = \Pixel\Form\FormJavaScript::iniciar();

        //print_r($this->objetos);
        if ($validacao or $javaScriptExtra) {

            foreach ($this->objetos as $config) {
                if ($validacao) {
                    $smartJs->processarValidacao($config);
                }

                if ($javaScriptExtra) {
                    $smartJs->processarJS($this->formConfig->getNome(), $config);
                }
            }

            if ($validacao) {
                $js = $smartJs->montaValidacao($this->formConfig->getNome(), $this->getAcao());
            } else {
                $js = $smartJs->getJS();
            }

            $jsStatic->setLoad($js);
        }

        return $jsStatic;
    }

    public function processarJSObjeto($objeto)
    {
        $smartJs = new \Pixel\Form\FormPixelJavaScript();

        $smartJs->processarJS($this->formConfig->getNome(), $objeto);

        return $smartJs->getJS();
    }

    public function montaForm()
    {
        $buffer = $this->abreFormManu();

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
        $buffer = $this->abreFormManu();

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
