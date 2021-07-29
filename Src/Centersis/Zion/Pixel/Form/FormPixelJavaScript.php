<?php

namespace Centersis\Zion\Pixel\Form;

use Centersis\Zion\Paginacao\Parametros;
use Centersis\Zion\Pixel\Form\FormJavaScript;

class FormPixelJavaScript
{

    private $formJavaScript;
    private $regras;
    private $mensagens;
    private $extra;
    private $upload;

    public function __construct()
    {
        $this->formJavaScript = FormJavaScript::iniciar();

        $this->regras = [];
        $this->mensagens = [];

        $this->extra = [];

        $this->upload = false;
    }

    private function suggest($formNome, FormInputSuggest $config)
    {
        $attr = [];

        $url = $config->getUrl() ? $config->getUrl() : \SIS_URL_BASE . 'Ext/Remoto/autocomplete/';
        $id = $config->getId() ? $config->getId() : $config->getNome();

        $parametros = '?t=' . $config->getTabela();
        $parametros .= '&cc=' . $config->getCampoCod();
        $parametros .= '&cd=' . $config->getCampoDesc();
        $parametros .= '&cb=' . $config->getCampoBusca();
        $parametros .= '&idc=' . $config->getIdConexao();
        $parametros .= '&l=' . $config->getLimite();
        $parametros .= $config->getParametros();

        $abre = ' $( "#' . $formNome . ' #' . $id . '" ).autocomplete({ ';
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
        if ($config->getHidden()) {
            $onSelect .= '$("#' . $formNome . ' #' . \str_replace('sisL', '', $id) . '").val(ui.item.id); ';
        }

        if ($config->getOnSelect()) {
            $onSelect .= $config->getOnSelect();
        }

        if ($onSelect) {
            $attr[] = ' select: function( event, ui ) { ' . $onSelect . ' }';
        }

        $fecha = ' });';

        $this->extra[] = $abre . implode(',', $attr) . $fecha;
    }

    public function processarValidacao($config)
    {
        if ($config->getAcao() == 'editor') {
            return;
        }

        if ($config->getAcao() == 'upload') {
            $this->upload = true;
        }

        if ($config->getTipoBase() == 'masterDetail') {

            $campos = $config->getCampos();

            foreach ($campos as $objFormMaster) {

                if ($objFormMaster->getTipoBase() === 'upload') {
                    $this->upload = true;
                    break;
                }
            }
        }

        //Validacão de obrigatório
        if (\method_exists($config, 'getObrigatorio') and $config->getObrigatorio()) {
            $this->regras[$config->getNome()][] = 'required : true';
            $this->mensagens[$config->getNome()][] = "required : '{$config->getIdentifica()} é obrigatório!'";
        }

        if (\method_exists($config, 'getMaximoCaracteres') and $config->getMaximoCaracteres()) {
            $this->regras[$config->getNome()][] = 'maxlength : ' . $config->getMaximoCaracteres();
            $this->mensagens[$config->getNome()][] = "maxlength : '{$config->getIdentifica()} deve ter no máximo {$config->getMaximoCaracteres()} caracteres!'";
        }

        if (\method_exists($config, 'getMinimoCaracteres') and $config->getMinimoCaracteres()) {
            $this->regras[$config->getNome()][] = 'minlength : ' . $config->getMinimoCaracteres();
            $this->mensagens[$config->getNome()][] = "minlength : '{$config->getIdentifica()} deve ter no mínimo {$config->getMinimoCaracteres()} caracteres!'";
        }

        if (\method_exists($config, 'getValorMaximo') and $config->getValorMaximo()) {
            $this->regras[$config->getNome()][] = 'max : ' . $config->getValorMaximo();
            $this->mensagens[$config->getNome()][] = "max : '{$config->getIdentifica()} deve ter valor máximo de {$config->getValorMaximo()}!'";
        }

        if (\method_exists($config, 'getValorMinimo') and $config->getValorMinimo()) {
            $this->regras[$config->getNome()][] = 'min : ' . $config->getValorMinimo();
            $this->mensagens[$config->getNome()][] = "min : '{$config->getIdentifica()} deve ter valor mínimo de {$config->getValorMinimo()}!'";
        }

        if (\method_exists($config, 'getDeveSerIgual') and $config->getDeveSerIgual()) {
            $this->regras[$config->getNome()][] = 'equalTo : #' . $config->getDeveSerIgual();
            $this->mensagens[$config->getNome()][] = "equalTo : '{$config->getIdentifica()} deve ser igual ao campo acima!'";
        }

        if ($config->getAcao() == 'number') {
            $this->regras[$config->getNome()][] = 'digits : true';
            $this->mensagens[$config->getNome()][] = "digits : '{$config->getIdentifica()} deve conter apenas números!'";
        }

        if ($config->getAcao() == 'date') {
            //$this->regras[$config->getNome()][] = 'dateBR : true';
            //$this->mensagens[$config->getNome()][] = " required : '{$config->getIdentifica()} deve conter uma data válida!'";
        }

        if ($config->getAcao() === 'escolha' or $config->getAcao() === 'chosen') {

            if (($config->getMultiplo() === true and $config->getExpandido() === true) or $config->getAcao() === 'chosen') {

                $selecaoMaxima = $config->getSelecaoMaxima();
                $selecaoMinima = $config->getSelecaoMinima();

                if ($selecaoMaxima) {
                    $this->regras[$config->getNome()][] = 'maxlength: ' . $selecaoMaxima;
                    $this->mensagens[$config->getNome()][] = "maxlength : 'Selecione no máximo {$selecaoMaxima} opções!'";
                }

                if ($selecaoMinima) {
                    $this->regras[$config->getNome()][] = 'minlength: ' . $selecaoMinima;
                    $this->mensagens[$config->getNome()][] = "minlength : 'Selecione no mínimo {$selecaoMinima} opções!'";
                }
            }
        }

        if ($config->getAcao() == 'email') {
            $this->regras[$config->getNome()][] = 'email : true';
            $this->mensagens[$config->getNome()][] = "email : '{$config->getIdentifica()} deve conter um e-mail válido!'";
        }

        if ($config->getAcao() == 'cpf') {

            $this->regras[$config->getNome()][] = 'cpf : true';
            $this->mensagens[$config->getNome()][] = " cpf : '{$config->getIdentifica()} deve conter um CPF válido!'";
        }

        if ($config->getAcao() == 'cnpj') {

            //$this->regras[$config->getNome()][] = 'cnpj : true';
            //$this->mensagens[$config->getNome()][] = " cnpj : '{$config->getIdentifica()} deve conter um CNPJ válido!'";
        }

        if ($config->getAcao() == 'cep') {

            //$this->regras[$config->getNome()][] = 'cep : true';
            //$this->mensagens[$config->getNome()][] = " cep : '{$config->getIdentifica()} deve conter um CEP válido!'";
        }

        if ($config->getAcao() == 'telefone') {

            //$this->regras[$config->getNome()][] = 'celular : true';
            //$this->mensagens[$config->getNome()][] = " celular : '{$config->getIdentifica()} deve conter um número de telefone válido!'";
        }
    }

    public function processarJS($formNome, $config)
    {
        if ($config->getAcao() == 'editor') {
            return;
        }

        if ($config->getAcao() == 'cor') {
            $this->extra[] = '$("#' . $formNome . ' #' . $config->getId() . '").minicolors({ theme: "bootstrap" });';
        }

        if ($config->getAcao() == 'float') {
            $this->extra[] = '$("#' . $formNome . ' #' . $config->getId() . '").maskMoney({prefix:"' . $config->getPrefixo() . '", allowZero:"' . $config->getZeroInicial() . '", thousands:".", decimal:",", affixesStay: false, allowEmpty:true});';
        }

        if ($config->getAcao() == 'date') {
            $this->extra[] = ' $("#' . $formNome . ' #' . $config->getId() . '").mask("99/99/9999").datepicker({ dateFormat: "dd/mm/yy" }); ';
        }

        if ($config->getAcao() == 'dateTime') {
            $this->extra[] = ' $("#' . $formNome . ' #' . $config->getId() . '").mask("99/99/9999 99:99").datetimepicker({ dateFormat: "dd/mm/yy" }); ';
        }

        if ($config->getAcao() === 'chosen' or $config->getAcao() === 'escolha') {

            if ($config->getAcao() === 'chosen') {

                $placeholder = $config->getInicio() ? ', placeholder: "' . $config->getInicio() . '"' : '';

                $this->extra[] = '$("#' . $formNome . ' #' . str_replace('[]', '', $config->getId()) . '").select2({ allowClear: true' . $placeholder . ' }); ';
            }

            $campoDependencia = $config->getCampoDependencia();

            if ($campoDependencia) {

                $metodoDependencia = $config->getMetodoDependencia();
                $classeDependencia = $config->getClasseDependencia();
                $nomeCampo = $config->getNome();
                $callback = $config->getCallback() ? $config->getCallback() : 'false';

                $parametros = $config->getParametros();

                $urlPar = '';
                if ($parametros) {
                    $urlPar = Parametros::addQueryString('', $parametros);
                }

                $url = \SIS_URL_BASE . 'Ext/Remoto/dependencia/?' . $urlPar;

                $this->extra[] = '$("#' . $formNome . ' #' . $campoDependencia . '").change(function() { sisCarregaDependencia(\'' . $url . '\', \'' . $formNome . '\',\'' . $config->getContainer() . '\',$(this).val(),\'' . $metodoDependencia . '\',\'' . $classeDependencia . '\',\'' . $nomeCampo . '\',' . $callback . ');  });';
            }
        }

        if ($config->getAcao() == 'time') {

            if ($config->getMostrarSegundos()) {
                $showSeconds = 'true';
                $mascara = '99:99:99';
            } else {
                $showSeconds = 'false';
                $mascara = '99:99';
            }

            $this->extra[] = ' $("#' . $formNome . ' #' . $config->getId() . '").mask("' . $mascara . '").timepicker('
                . '{ minuteStep: 1, showSeconds: ' . $showSeconds . ', defaultTime: false, showMeridian: false, showInputs: false, '
                . 'orientation: $("body").hasClass("right-to-left") ? { x: "right", y: "auto"} : { x: "auto", y: "auto"}}); ';
        }

        if ($config->getAcao() == 'cpf') {

            $this->extra[] = '$("#' . $formNome . ' #' . $config->getId() . '").mask("999.999.999-99");';
        }

        if ($config->getAcao() == 'cnpj') {

            $this->extra[] = '$("#' . $formNome . ' #' . $config->getId() . '").mask("99.999.999/9999-99");';
        }

        if ($config->getAcao() == 'cep') {

            $this->extra[] = '$("#' . $formNome . ' #' . $config->getId() . '").mask("99.999-999");';
        }

        if ($config->getAcao() == 'telefone') {

            $this->extra[] = '$("#' . $formNome . ' #' . $config->getId() . '").mask("(99) ?99999-9999");';
        }

        if ($config->getAcao() == 'suggest') {
            $this->suggest($formNome, $config);
        }

        if (\method_exists($config, 'getToolTipMsg') and $config->getToolTipMsg()) {

            $this->extra[] = '$("#' . $formNome . ' #' . $config->getId() . '").tooltip();';
        }

        if (\method_exists($config, 'getMascara') and $config->getMascara()) {

            $this->extra[] = '$("#' . $formNome . ' #' . $config->getId() . '").mask("' . $config->getMascara() . '");';
        }

        if ($config->getAcao() == 'senha' and $config->getNome() == 'validaSenhaUser') {
            $this->extra[] = '$(".fa-lock").attr("id", "iconFA").attr("title", "Informe sua senha para homologação destas alterações."); $("#' . $formNome . ' #' . $config->getId() . '").keyup(function($e){validaSenhaUser(this, "' . \SIS_URL_BASE . 'Ext/Remoto/ValidaSenha/' . '");});';
        }

        if ($config->getAcao() == 'upload' and is_array($config->getCrop())) {

            $configCrop = $config->getCrop();

            $defaultCrop = [
                'imagemLarguraMinima' => '100',
                'imagemAlturaMinima' => '100',
                'cropLarguraMinima' => '100',
                'cropAlturaMinima' => '100',
                'cropLarguraMaxima' => null,
                'cropAlturaMaxima' => null,
                'cropLarguraFixa' => null,
                'cropAlturaFixa' => null,
                'previewLargura' => '100%',
                'previewAltura' => '100',
                'arquivoLargura' => '100',
                'arquivoAltura' => '100'
            ];

            $cCrop = [];

            foreach ($defaultCrop as $chave => $valor) {
                $cCrop[$chave] = $configCrop[$chave] ?? $valor;
            }

            $txtSize = 'minSize:[' . $cCrop['cropLarguraMinima'] . ',' . $cCrop['cropAlturaMinima'] . ']';
            $txtArquivo = $cCrop['arquivoLargura'] . ',' . $cCrop['arquivoAltura'];
            $txtPreview = $cCrop['previewLargura'] . '%",' . $cCrop['previewAltura'];

            if ($cCrop['cropLarguraFixa'] or $cCrop['cropAlturaFixa']) {
                $txtSize = 'minSize:[' . $cCrop['cropLarguraFixa'] . ',' . $cCrop['cropAlturaFixa'] . '], maxSize:[' . $cCrop['cropLarguraFixa'] . ',' . $cCrop['cropAlturaFixa'] . ']';
                $txtArquivo = $cCrop['cropLarguraFixa'] . ',' . $cCrop['cropAlturaFixa'];
                $txtPreview = $cCrop['previewLargura'] . '%",' . $cCrop['cropAlturaFixa'];
            } elseif ($cCrop['cropLarguraMaxima'] or $cCrop['cropAlturaMaxima']) {
                $txtSize = 'minSize:[' . $cCrop['cropLarguraMinima'] . ',' . $cCrop['cropAlturaMinima'] . '], maxSize:[' . $cCrop['cropLarguraMaxima'] . ',' . $cCrop['cropAlturaMaxima'] . ']';
            }

            $this->extra[] = '
            $("#' . $formNome . ' #' . $config->getId() . '").change(
                function(html){
                    $imagem=$("#' . $formNome . ' #sis_demo_crop_' . $config->getId() . '");
                    $imagem.show();
                    var arquivo=new FileReader;
                    arquivo.onload=
                        function(file){
                            var imageFile=new Image;
                            imageFile.onload=
                                function(){
                                    var e=imageFile.width,t=imageFile.height;
                                    e>t?e>500&&(t*=500/e,e=500):t>500&&(e*=500/t,t=500);
                                    var r=document.createElement("canvas");
                                    r.width=e;
                                    r.height=t;
                                    r.getContext("2d").drawImage(this,0,0,e,t);
                                    this.src=r.toDataURL();
                                    this.onload = function() {
                                        if(imageFile.width < ' . $cCrop['imagemLarguraMinima'] . ' || imageFile.height < ' . $cCrop['imagemAlturaMinima'] . ') {
                                            $("#' . $formNome . ' #sis_demo_crop_' . $config->getId() . '").removeAttr("src");
                                            $("#' . $formNome . ' #sis_base64_crop_' . $config->getId() . '").removeAttr("value");
                                            $("#' . $formNome . ' #' . $config->getId() . '").val(null);
                                            sisSetAlert("false", "A imagem deve ter dimensões mínimas de ' . $cCrop['imagemAlturaMinima'] . ' pixels de altura por ' . $cCrop['imagemLarguraMinima'] . ' pixels de largura!");
                                        } else {
                                            $("#' . $formNome . ' #sis_demo_crop_' . $config->getId() . '").attr("src",file.target.result);
                                            $imagem.rcrop({' . $txtSize . ', preserveAspectRatio:!1,preview:{display:!0,size:["' . $txtPreview . '],wrapper:""}});
                                            $imagem.on("rcrop-changed rcrop-ready",
                                                function(){
                                                    var imagemCortada=$(this).rcrop("getDataURL",' . $txtArquivo . ');
                                                    $("#' . $formNome . ' #sis_base64_crop_' . $config->getId() . '").val(imagemCortada)
                                                }
                                            )
                                        }
                                    }
                                };
                            imageFile.src=file.target.result;
                        };
                    arquivo.readAsDataURL(html.target.files[0]);
                    $imagem.rcrop("destroy");
                    $("canvas").remove();
                }
            );';
        }
    }

    public function montaValidacao($objConfig, $acao, $jsExtra = true)
    {
        $formNome = $objConfig->getNome();
        $acaoSubmit = $objConfig->getAcaoSubmit();

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

        $upload = $this->upload ? 'true' : 'false';

        if (empty($acaoSubmit)) {
            if ($acao == 'cadastrar') {
                $acaoSubmit = 'sisCadastrarPadrao($(form).attr("name"),' . $upload . ');';
            } else if ($acao == 'alterar') {
                $acaoSubmit = 'sisAlterarPadrao($(form).attr("name"),' . $upload . ');';
            } else {
                $acaoSubmit = $acao;
            }
        }

        $textoSubmit = ' submitHandler: function(form) { ' . $acaoSubmit . ' } ';

        $extra = $jsExtra ? $this->getJS() : '';

        return $textoGeral . $textoRegra . ',' . $textoMensagem . ',' . $textoSubmit . ' }); ' . $extra;
    }

    public function getJS()
    {
        return \implode('', $this->extra);
    }

    public function getJsExtraObjeto($objeto, $formNome = '')
    {
        foreach ($objeto as $config) {

            $this->processarJS($formNome, $config);
        }

        return $this->getJS();
    }

}
