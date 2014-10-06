<?php

namespace Zion\Form;

class FormSmartJavaScript
{

    private $formJavaScript;
    private $regras;
    private $mensagens;
    private $extra;

    public function __construct()
    {
        $this->formJavaScript = \Zion\Form\FormJavaScript::iniciar();

        $this->regras = [];
        $this->mensagens = [];

        $this->extra = '';
    }

    public function processar($config)
    {
        //Validacão de obrigatório
        if (method_exists($config, 'getObrigatorio') and $config->getObrigatorio()) {
            $this->regras[$config->getNome()][] = 'required : true';
            $this->mensagens[$config->getNome()][] = "required : '{$config->getIdentifica()} é obrigatório!'";
        }

        if (method_exists($config, 'getMaximoCaracteres') and $config->getMaximoCaracteres()) {
            $this->regras[$config->getNome()][] = 'maxlength : ' . $config->getMaximoCaracteres();
            $this->mensagens[$config->getNome()][] = "maxlength : '{$config->getIdentifica()} deve ter no máximo {$config->getMaximoCaracteres()} caracteres!'";
        }

        if (method_exists($config, 'getMinimoCaracteres') and $config->getMinimoCaracteres()) {
            $this->regras[$config->getNome()][] = 'minlength : ' . $config->getMinimoCaracteres();
            $this->mensagens[$config->getNome()][] = "minlength : '{$config->getIdentifica()} deve ter no mínimo {$config->getMinimoCaracteres()} caracteres!'";
        }

        if (method_exists($config, 'getValorMaximo') and $config->getValorMaximo()) {
            $this->regras[$config->getNome()][] = 'max : ' . $config->getValorMaximo();
            $this->mensagens[$config->getNome()][] = "max : '{$config->getIdentifica()} deve ter valor máximo de {$config->getValorMaximo()}!'";
        }

        if (method_exists($config, 'getValorMinimo') and $config->getValorMinimo()) {
            $this->regras[$config->getNome()][] = 'min : ' . $config->getValorMinimo();
            $this->mensagens[$config->getNome()][] = "min : '{$config->getIdentifica()} deve ter valor mínimo de {$config->getValorMinimo()}!'";
        }

        if (method_exists($config, 'getDeveSerIgual') and $config->getDeveSerIgual()) {
            $this->regras[$config->getNome()][] = 'equalTo : #' . $config->getDeveSerIgual();
            $this->mensagens[$config->getNome()][] = "equalTo : '{$config->getIdentifica()} deve ser igual ao campo acima!'";
        }

        if ($config->getAcao() == 'number') {
            $this->regras[$config->getNome()][] = 'digits : true';
            $this->mensagens[$config->getNome()][] = "digits : '{$config->getIdentifica()} deve conter apenas números!'";
        }

        if ($config->getAcao() == 'float') {
            $this->regras[$config->getNome()][] = 'number : true';
            $this->mensagens[$config->getNome()][] = "number : '{$config->getIdentifica()} deve conter um valor válido!'";
        }

        if ($config->getAcao() == 'date') {
            $this->regras[$config->getNome()][] = 'date : true';
            $this->mensagens[$config->getNome()][] = " date : '{$config->getIdentifica()} deve conter uma data válida!'";
        }

        if ($config->getAcao() == 'email') {
            $this->regras[$config->getNome()][] = 'email : true';
            $this->mensagens[$config->getNome()][] = "email : '{$config->getIdentifica()} deve conter um e-mail válido!'";
        }

        if ($config->getAcao() == 'suggest') {
            $this->extra .= $this->montaSuggest($config);
        }
    }

    private function montaSuggest(FormInputSuggest $config)
    {
        $attr = [];

        $url = $config->getUrl() ? $config->getUrl() : DEFAULT_AUTOCOMPLETE;
        $id = $config->getId() ? $config->getId() : $config->getNome();

        $parametros = '?t=' . $config->getTabela();
        $parametros .= '&cc=' . $config->getCampoCod();
        $parametros .= '&cd=' . $config->getCampoDesc();
        $parametros .= '&cb' . $config->getCampoBusca();
        $parametros .= '&idc=' . $config->getIdConexao();
        $parametros .= '&cnd=' . $config->getCondicao();
        $parametros .= '&l=' . $config->getLimite();
        $parametros .= $config->getParametros();


        $abre = ' $( "#' . $id . '" ).autocomplete({ ';
        $attr[] = ' source: "' . $url . $parametros . '"';

        if ($config->getEspera()) {
            $attr[] = ' delay: ' . $config->getEspera();
        }

        if ($config->getTamanhoMinimo()) {
            $attr[] = ' minLength: ' . $config->getTamanhoMinimo();
        }

        if ($config->getDisabled() === false) {
            $attr[] = ' disabled: false';
        }

        $onSelect = '';
        if ($config->getHiddenValue()) {
            $onSelect .= '$("#' . $config->getHiddenValue() . '").val(ui.item.id); ';
        }

        if ($config->getOnSelect()) {
            $onSelect.= $config->getOnSelect();
        }

        if ($onSelect) {
            $attr[] = ' select: function( event, ui ) { ' . $onSelect . ' }';
        }

        $fecha = ' });';

        return $abre . implode(',', $attr) . $fecha;
    }

    public function montaValidacao($formNome)
    {
        if (!$this->regras) {
            return '';
        }

        $textoGeral = ' var $registerForm = $("#' . $formNome . '").validate({ ';

        $textoRegra = ' rules : { ';
        $textoMensagem = ' messages : { ';

        $cont = 0;
        $total = count($this->regras);
        foreach ($this->regras as $nome => $regras) {
            $cont++;
            $textoRegra .= $nome . ' : {' . implode(',', $regras) . ' } ';
            $textoMensagem .= $nome . ' : {' . implode(',', $this->mensagens[$nome]) . ' } ';
            $virgula = $cont == $total ? '' : ',';
            $textoRegra .= $virgula;
            $textoMensagem .= $virgula;
        }

        $textoRegra .= ' } ';
        $textoMensagem .= ' } ';

        $textoSubmit = ' submitHandler : function(form) { $(form).ajaxSubmit({ success : function() { $("#' . $formNome . '").addClass("submited"); } });} ';
        $textoErro = 'errorPlacement : function(error, element) { error.insertAfter(element.parent()); }';

        return $textoGeral . $textoRegra . ',' . $textoMensagem . ',' . $textoSubmit . ',' . $textoErro . ' }); ' . $this->extra;
    }

}
