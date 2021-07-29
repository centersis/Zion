<?php

namespace Centersis\Zion\Pixel\Form;

use Zion\Form\Form as FormZion;
use Zion\Pixel\Form\FormHtml;
use Zion\Layout\Html;
use Zion\Form\FormInputButton;
use Zion\Pixel\Form\MasterDetail\FormMasterDetail;
use App\Sistema\Ajuda\AjudaView;
use Zion\Exception\ErrorException;

class Form extends FormZion
{

    protected $formPixel;
    protected $html;

    public function __construct()
    {
        parent::__construct();

        $this->formPixel = new FormHtml();
        $this->html = new Html();
    }

    public function texto($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputTexto('texto', $nome, $identifica, $obrigatorio);
    }

    public function suggest($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputSuggest('suggest', $nome, $identifica, $obrigatorio);
    }

    public function data($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputData('date', $nome, $identifica, $obrigatorio);
    }

    public function hora($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputHora('time', $nome, $identifica, $obrigatorio);
    }

    public function dataHora($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputDataHora('dateTime', $nome, $identifica, $obrigatorio);
    }

    public function senha($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputSenha('senha', $nome, $identifica, $obrigatorio);
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

    public function escolha($nome, $identifica, $obrigatorio = false)
    {
        return new FormEscolha('escolha', $nome, $identifica, $obrigatorio);
    }

    public function chosen($nome, $identifica, $obrigatorio = false)
    {
        return new FormChosen('chosen', $nome, $identifica, $obrigatorio);
    }

    public function textArea($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputTextArea('textarea', $nome, $identifica, $obrigatorio);
    }

    public function editor($nome, $identifica, $obrigatorio = false)
    {
        return new FormInputTextArea('editor', $nome, $identifica, $obrigatorio);
    }

    public function upload($nome, $identifica, $tratarComo)
    {
        return new FormUpload('upload', $nome, $identifica, $tratarComo);
    }

    public function botaoSubmit($nome, $identifica)
    {
        return new FormInputButton('submit', $nome, $identifica);
    }

    /**
     * @return \Zion\Form\FormInputButton
     */
    public function botaoSalvarPadrao($identifica = 'Salvar')
    {
        $botaoSalvar = new FormInputButton('submit', 'sisSalvar', $identifica);
        $botaoSalvar->setClassCss('btn btn-primary');

        return $botaoSalvar;
    }

    public function botaoSalvarEContinuar()
    {
        $botaoSalvar = new FormInputButton('submit', 'sisSalvarEContinuar', 'Salvar');
        $botaoSalvar->setClassCss('btn btn-primary');

        return $botaoSalvar;
    }

    public function botaoDescartarPadrao()
    {
        $nomeForm = $this->getConfig()->getNome();

        $botaoDescartar = new FormInputButton('button', 'sisDescartar', 'Descartar');

        $botaoDescartar->setClassCss('btn btn-default')
                ->setComplemento('onclick="sisDescartarPadrao(\'' . $nomeForm . '\')"');

        return $botaoDescartar;
    }

    public function botaoDescartarAbas()
    {
        $botaoDescartarAbas = new FormInputButton('button', 'sisDescartaAbas', 'Descartar');

        $botaoDescartarAbas->setClassCss('btn btn-default')
                ->setComplemento('onclick="sisDescartarAbas();"');

        return $botaoDescartarAbas;
    }

    /**
     * @return \Zion\Form\FormInputButton
     */
    public function botaoLabeled($nome, $identifica)
    {
        return (new FormInputButton('button', $nome, $identifica))
                ->setClassCss('btn btn-labeled');
    }

    public function botaoSimples($nome, $identifica)
    {
        return new FormInputButton('button', $nome, $identifica);
    }

    public function botaoReset($nome, $identifica)
    {
        return new FormInputButton('reset', $nome, $identifica);
    }

    public function cor($nome, $identifica, $obrigatorio = false)
    {
        return new FormColor('cor', $nome, $identifica, $obrigatorio);
    }

    public function masterDetail($nome, $identifica)
    {
        return new FormMasterDetail($nome, $identifica);
    }

    public function getFormHtml($nomeOuObjeto = null)
    {
        $htmlCampos = [];

        if (is_object($nomeOuObjeto)) {
            $obj[$nomeOuObjeto->getNome()] = $nomeOuObjeto;
            $nome = $nomeOuObjeto->getNome();
        } else {
            $nome = $nomeOuObjeto;
            $obj = $nomeOuObjeto ? [$nomeOuObjeto => $this->objetos[$nomeOuObjeto]] : $this->objetos;
        }

        foreach ($obj as $idCampo => $objCampos) {

            switch ($objCampos->getTipoBase()) {
                case 'hidden' :
                    $htmlCampos[$idCampo] = $this->formHtml->montaHiddenHtml($objCampos);
                    break;
                case 'texto' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaTexto($objCampos);
                    break;
                case 'senha' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaSenha($objCampos);
                    break;
                case 'textarea' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaTextArea($objCampos);
                    break;
                case 'editor' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaTextArea($objCampos);
                    break;
                case 'suggest' :

                    $objCampos->setMethod($this->formConfig->getMethod());

                    $htmlCampos[$idCampo] = $this->formPixel->montaSuggest($objCampos);
                    break;
                case 'data' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaData($objCampos);
                    break;
                case 'hora' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaHora($objCampos);
                    break;

                case 'dataHora' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaDataHora($objCampos);
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
                    $htmlCampos[$idCampo] = $this->formHtml->montaLayoutHtml($objCampos);
                    break;
                case 'masterDetail':
                    $htmlCampos[$idCampo] = $this->formPixel->montaMasterDetail($objCampos, $this->formConfig->getNome());
                    break;
                case 'telefone' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaTelefone($objCampos);
                    break;
                case 'email' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaEmail($objCampos);
                    break;
                case 'cor' :
                    $htmlCampos[$idCampo] = $this->formPixel->montaCor($objCampos);
                    break;
                default : throw new ErrorException('Tipo Base não encontrado! ' . $idCampo);
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
        $smartJs = new FormPixelJavaScript();
        $jsStatic = FormJavaScript::iniciar();

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
                $js = $smartJs->montaValidacao($this->formConfig, $this->getAcao());
            } else {
                $js = $smartJs->getJS();
            }

            $jsStatic->setLoad($js);
        }

        return $jsStatic;
    }

    public function processarJSObjeto($objeto)
    {
        $smartJs = new FormPixelJavaScript();

        $smartJs->processarJS($this->formConfig->getNome(), $objeto);

        return $smartJs->getJS();
    }

    public function montaForm($disabled = false)
    {
        $buffer = [];

        if ($disabled) {
            foreach ($this->objetos as $objeto) {
                if (method_exists($objeto, 'setDisabled')) {

                    if ($objeto->getAcao() !== 'button') {
                        $objeto->setDisabled(true);
                    }
                }
            }
        }

        $campos = $this->getFormHtml();

        $buffer['formConfig']['id'] = $this->formConfig->getId();
        $buffer['formConfig']['nome'] = $this->formConfig->getNome();
        $buffer['formConfig']['action'] = $this->formConfig->getAction();
        $buffer['formConfig']['autoComplete'] = $this->formConfig->getAutoComplete();
        $buffer['formConfig']['enctype'] = $this->formConfig->getEnctype();
        $buffer['formConfig']['method'] = $this->formConfig->getMethod();
        $buffer['formConfig']['novalidate'] = $this->formConfig->getNovalidate();
        $buffer['formConfig']['target'] = $this->formConfig->getTarget();
        $buffer['formConfig']['complemento'] = $this->formConfig->getComplemento();
        $buffer['formConfig']['classCss'] = $this->formConfig->getClassCss();
        $buffer['formConfig']['header'] = $this->formConfig->getHeader();

        $ajudaViewClass = null;

        if ($this->getAcao()) {
            try {
                $ajudaViewClass = new AjudaView();
                #$buffer['ajuda'] = $ajudaViewClass->getAjuda(\MODULO, $this->getAcao());
            } catch (\Exception $e) {
                // noop
            }
        }

        foreach ($campos as $nome => $textoHtml) {

            if ($disabled) {

                if ($this->objetos[$nome]->getTipoBase() == 'button') {

                    if ($this->objetos[$nome]->getAcao() !== 'button') {
                        continue;
                    }
                }
            }

            if (method_exists($this->objetos[$nome], 'getLabelAntes') and $this->objetos[$nome]->getLabelAntes()) {
                $buffer['labelAntes'][$nome] = $this->objetos[$nome]->getLabelAntes();
            }

            if (method_exists($this->objetos[$nome], 'getHashAjuda') and $this->objetos[$nome]->getHashAjuda()) {

                try {
                    $ajudaViewClass = (is_object($ajudaViewClass)) ? $ajudaViewClass : new AjudaView();

                    $buffer['ajudaHash'][$nome] = $ajudaViewClass->getAjudaHash($this->objetos[$nome]->getHashAjuda());
                } catch (\Exception $e) {
                    // noop
                }
            }

            if (method_exists($this->objetos[$nome], 'getLabelAntes') and $this->objetos[$nome]->getLabelDepois()) {
                $buffer['labelAntes'][$nome] = $this->objetos[$nome]->getLabelDepois();
            }

            if (method_exists($this->objetos[$nome], 'getIconFA') and $this->objetos[$nome]->getIconFA()) {
                $buffer['iconFA'][$nome] = 'fa ' . $this->objetos[$nome]->getIconFA() . ' form-control-feedback';
            }

            if (method_exists($this->objetos[$nome], 'getComplementoExterno') and $this->objetos[$nome]->getComplementoExterno()) {
                $buffer['complementoExterno'][$nome] = $this->objetos[$nome]->getComplementoExterno();
            }

            $buffer['tipos'][$nome] = $this->objetos[$nome]->getTipoBase();

            if ($this->objetos[$nome]->getTipoBase() == 'button') {
                $buffer['botoes'][$nome] = $textoHtml;
            } else {

                $buffer['campos'][$nome] = $textoHtml;

                $colunas = 12;
                $offsetColunaA = 3;

                if (method_exists($this->objetos[$nome], 'getEmColunaDeTamanho')) {
                    $colunas = $this->objetos[$nome]->getEmColunaDeTamanho();
                    $offsetColunaA = $this->objetos[$nome]->getOffsetColuna();
                }

                $buffer['colunas'][$nome] = $colunas;
                $buffer['offsetColunaA'][$nome] = $offsetColunaA;
                $buffer['offsetColunaB'][$nome] = (12 - $offsetColunaA);

                if (method_exists($this->objetos[$nome], 'getIdentifica')) {
                    $buffer['identifica'][$nome] = $this->objetos[$nome]->getIdentifica();
                }
            }
        }

        $buffer['javascript'] = $this->javaScript()->getLoad(true);

        return $buffer;
    }

}
