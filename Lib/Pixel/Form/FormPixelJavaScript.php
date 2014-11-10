<?php

namespace Pixel\Form;

class FormPixelJavaScript
{

    private $formJavaScript;
    private $regras;
    private $mensagens;
    private $extra;

    public function __construct()
    {
        $this->formJavaScript = \Pixel\Form\FormJavaScript::iniciar();

        $this->regras = [];
        $this->mensagens = [];

        $this->extra = '';
    }

    public function processar($config)
    {
        if ($config->getAcao() == 'editor') {
            return;
        }        

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
            $this->extra.= '$("#' . $config->getId() . '").maskMoney({prefix:"' . $config->getPrefixo() . '", allowZero:false, thousands:".", decimal:",", affixesStay: false});';
        }

        if ($config->getAcao() == 'date') {
            $this->regras[$config->getNome()][] = 'dateBR : true';
            $this->mensagens[$config->getNome()][] = " dateBR : '{$config->getIdentifica()} deve conter uma data válida!'";
            $this->extra.= ' $("#' . $config->getId() . '").mask("99/99/9999").datepicker(); ';
        }

        if ($config->getAcao() === 'escolha' or $config->getAcao() === 'chosen') {
            
            if(($config->getMultiplo() === true and $config->getExpandido() === true) or $config->getAcao() === 'chosen'){
                
                $selecaoMaxima = $config->getSelecaoMaxima();
                $selecaoMinima = $config->getSelecaoMinima();
                
                if($selecaoMaxima){
                    $this->regras[$config->getNome()][] = 'maxlength: '.$selecaoMaxima;
                    $this->mensagens[$config->getNome()][] = "maxlength : 'Selecione no máximo {$selecaoMaxima} opções!'";
                }
                
                if($selecaoMinima){
                    $this->regras[$config->getNome()][] = 'minlength: '.$selecaoMinima;
                    $this->mensagens[$config->getNome()][] = "minlength : 'Selecione no mínimo {$selecaoMinima} opções!'";
                }
            }            
        }
        
        if ($config->getAcao() === 'chosen') {
            
            $placeholder = $config->getInicio() ? ', placeholder: "'.$config->getInicio().'"' : '';
            
            $this->extra.= '$("#' . str_replace('[]', '',$config->getId()) . '").select2({ allowClear: true'.$placeholder.' }); ';
        }
        
        if ($config->getAcao() == 'time') {

            if ($config->getMostrarSegundos()) {
                $showSeconds = 'true';
                $mascara = '99:99:99';
            } else {
                $showSeconds = 'false';
                $mascara = '99:99';
            }

            $this->extra.= ' $("#' . $config->getId() . '").mask("' . $mascara . '").timepicker('
                    . '{ minuteStep: 1, showSeconds: ' . $showSeconds . ', defaultTime: false, showMeridian: false, showInputs: false, '
                    . 'orientation: $("body").hasClass("right-to-left") ? { x: "right", y: "auto"} : { x: "auto", y: "auto"}}); ';
        }

        if ($config->getAcao() == 'email') {
            $this->regras[$config->getNome()][] = 'email : true';
            $this->mensagens[$config->getNome()][] = "email : '{$config->getIdentifica()} deve conter um e-mail válido!'";
        }

        if ($config->getAcao() == 'cpf') {

            $this->regras[$config->getNome()][] = 'cpf : true';
            $this->mensagens[$config->getNome()][] = " cpf : '{$config->getIdentifica()} deve conter um CPF válido!'";
            $this->extra.= '$("#' . $config->getId() . '").mask("999.999.999-99");';
        }

        if ($config->getAcao() == 'cnpj') {

            $this->regras[$config->getNome()][] = 'cnpj : true';
            $this->mensagens[$config->getNome()][] = " cnpj : '{$config->getIdentifica()} deve conter um CNPJ válido!'";
            $this->extra.= '$("#' . $config->getId() . '").mask("99.999.999/9999-99");';
        }

        if ($config->getAcao() == 'cep') {

            $this->regras[$config->getNome()][] = 'cep : true';
            $this->mensagens[$config->getNome()][] = " cep : '{$config->getIdentifica()} deve conter um CEP válido!'";
            $this->extra.= '$("#' . $config->getId() . '").mask("99.999-99");';
        }

        if ($config->getAcao() == 'telefone') {

            $this->regras[$config->getNome()][] = 'celular : true';
            $this->mensagens[$config->getNome()][] = " celular : '{$config->getIdentifica()} deve conter um número de telefone válido!'";
            $this->extra.= '$("#' . $config->getId() . '").mask("(99) 9999-9999?9");';
        }

        if ($config->getAcao() == 'suggest') { 
            $this->suggest($config);
        }
    }

    private function suggest(FormInputSuggest $config)
    {
        $attr = [];

        $url = $config->getUrl() ? $config->getUrl() : SIS_DEFAULT_AUTOCOMPLETE;
        $id = $config->getId() ? $config->getId() : $config->getNome();

        $parametros = '?t=' . $config->getTabela();
        $parametros .= '&cc=' . $config->getCampoCod();
        $parametros .= '&cd=' . $config->getCampoDesc();
        $parametros .= '&cb=' . $config->getCampoBusca();
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
        
        $this->extra.= $abre . implode(',', $attr) . $fecha;
    }

    public function montaValidacao($formNome, $acao)
    {
        $textoGeral = ' $("#' . $formNome . '").validate({ ';

        $textoRegra = ' rules : { ';
        $textoMensagem = ' messages : { ';

        $cont = 0;
        $total = count($this->regras);
        foreach ($this->regras as $nome => $regras) {
            $cont++;
            $textoRegra .= "'$nome'" . ' : {' . implode(',', $regras) . ' } ';
            $textoMensagem .= "'$nome'" . ' : {' . implode(',', $this->mensagens[$nome]) . ' } ';
            $virgula = $cont == $total ? '' : ',';
            $textoRegra .= $virgula;
            $textoMensagem .= $virgula;
        }

        $textoRegra .= ' } ';
        $textoMensagem .= ' } ';

        if ($acao == 'cadastrar') {
            $funcaoAcao = 'sisCadastrarPadrao($(form).attr("name"));';
        } else if ($acao == 'alterar') {
            $funcaoAcao = 'sisAlterarPadrao($(form).attr("name"));';
        } else if ($acao == 'alterar') {
            $funcaoAcao = 'sisFiltrarPadrao($(form).attr("name"));';
        } else {
            $funcaoAcao = $acao;
        }

        $textoSubmit = ' submitHandler: function(form) { ' . $funcaoAcao . ' } ';

        return $textoGeral . $textoRegra . ',' . $textoMensagem . ',' . $textoSubmit . ' }); ' . $this->extra;
    }
    
    public function getJS()
    {
        return $this->extra;
    }

}
