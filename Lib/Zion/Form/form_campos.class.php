<?php

include_once($_SESSION['FMBase'] . 'formulario.class.php');
include_once($_SESSION['FMBase'] . 'validacao_php.class.php');
include_once($_SESSION['FMBase'] . 'validacao_js.class.php');
include_once($_SESSION['FMBase'] . 'conexao.class.php');
include_once($_SESSION['FMBase'] . 'form_campos.gs.php');

class FormCampos extends FormCamposGS
{

    private $FPHP, $ValidaJS, $ValidaPHP, $ZIndex;
    //Decodificação UTF8
    private $Decodificacao;
    //Processar
    private $ProcessarHtml;
    private $ProcessarValidacao;

    public function FormCampos()
    {
        $this->FPHP = new FuncoesPHP();
        $this->ValidaJS = new ValidacaoJS();
        $this->ValidaPHP = new ValidacaoPHP();

        $this->setModFiltro(false);

        $this->setProcessarHtml(true);
        $this->setProcessarValidacao(true);

        $this->ZIndex = 100;

        $this->Decodificacao = true;
    }

    public function setDecodificacao($Valor)
    {
        $this->Decodificacao = $Valor === false ? false : true;
    }

    public function setProcessarHtml($Valor)
    {
        $this->ProcessarHtml = $Valor === false ? false : true;
    }

    public function setProcessarValidacao($Valor)
    {
        $this->ProcessarValidacao = $Valor === false ? false : true;
    }

    public function geraValidacaoJS($NomeFuncao, $NomeForm)
    {
        return $this->ValidaJS->geraValidacao($NomeFuncao, $NomeForm);
    }

    public function chamaArquivos()
    {
        return $this->ValidaJS->chamaArquivos();
    }

    public function geraMascaras()
    {
        return $this->ValidaJS->geraMascaras();
    }

    public function geraFuncoes()
    {
        return $this->ValidaJS->geraFuncoes();
    }

    public function geraOnLoad()
    {
        return $this->ValidaJS->geraOnLoad();
    }

    public function geraOpcoesDeFiltro()
    {
        return $this->ValidaJS->geraOpcoesDeFiltro();
    }

    public function getNomeForm()
    {
        return $this->ValidaJS->getNomeForm();
    }

    public function setFuncoes($Funcao)
    {
        $this->ValidaJS->setFuncoes($Funcao);
    }

    public function resetStringJS()
    {
        $this->ValidaJS->resetStringJS();
    }

    public function resetTodos()
    {
        $this->ValidaJS->resetTodos();
        $this->resetFiltro();
    }

    public function setNomeForm($Valor)
    {
        $this->ValidaJS->setNomeForm($Valor);
    }

    public function setOnLoad($Nome, $Valor)
    {
        $this->ValidaJS->setOnLoad($Nome, $Valor);
    }

    public function setStringJS($Valor)
    {
        $this->ValidaJS->setStringJS($Valor);
    }

    public function getStringJS()
    {
        return $this->ValidaJS->getStringJS();
    }

    public function validar($CFG, $TipoValidacao)
    {
        //Busca Confirmação de Envio
        $Env = parent::getEnv();

        //Decodificando
        if ($Env === true and $this->Decodificacao)
            $CFG['Valor'] = @utf8_decode($CFG['Valor']);

        //Mensagem de Erro
        $Mensagem = ($CFG['Mensagem'] == "") ? parent::mensagemPadrao($CFG['Identifica']) : $CFG['Mensagem'];

        //JavaScript
        $this->ValidaJS->validar($CFG, $TipoValidacao);

        //Caixa - Alta ou Baixa
        if ($CFG['Caixa'] != '') {
            $CFG['Caixa'] = strtoupper($CFG['Caixa']);

            if ($CFG['Caixa'] == "ALTA") {
                $CFG['Valor'] = strtoupper($CFG['Valor']);
            } else if ($CFG['Caixa'] == "BAIXA") {
                $CFG['Valor'] = strtolower($CFG['Valor']);
            }
        }


        //Verifica Obrigatoriedade
        if ($CFG['Obrigatorio'] === true or $CFG['Valor'] != '') {
            //Verifica se não Houve Erros
            if ($Env === true and $CFG['Status'] !== false) {
                try {
                    $Valor = $this->ValidaPHP->validar($CFG, $TipoValidacao);

                    parent::setCampoRetorna($CFG['Nome'], $Valor);
                    return parent::getCampoRetorna($CFG['Nome']);
                } catch (Exception $E) {
                    $Valor = $CFG['Valor'];
                    parent::setErro($CFG['Identifica'], $Mensagem);
                    parent::setErro($CFG['Identifica'], $E->getMessage());
                    return $Valor;
                }
            }
        }

        return $CFG['Valor'];
    }

    public function retornaValor($Metodo, $Nome)
    {
        $Metodo = strtoupper($Metodo);

        switch ($Metodo) {
            case "POST" : return $_POST[$Nome];
                break;
            case "GET" : return $_GET[$Nome];
                break;
            case "REQUEST": return $_REQUEST[$Nome];
                break;
            case "SESSION": return $_SESSION[$Nome];
                break;
            case "COOKIE" : return $_COOKIE[$Nome];
                break;
            case "FILES" : return $_FILES[$Nome];
                break;
            default: return null;
        }
    }

    public function htmlInput($CFG, $Valor, $Type)
    {
        $Nome = $CFG['Nome'];      //Nome do Campo                              - Obrigatório
        $Id = $CFG['Id'];        //Id do Campo                                - Opcional     Default:$Nome;
        $Largura = $CFG['Largura'];   //Largura do Campo                           - Opcional     Default:Sem Largura;
        $Max = $CFG['Max'];       //Máximo de Caracteres Permitido             - Opcional     Default:null;
        $Status = $CFG['Status'];    //Status do Campo true ativo false inativo   - Opcional     Default:true;
        $Estilo = $CFG['Estilo'];    //Estilo CSS                                 - Opcional     Default:null;
        $Adicional = $CFG['Adicional']; //Adcional
        $Conteiner = $CFG['Conteiner'];

        //Modo Filtro
        $ModFil = $this->getModFiltro();

        //Nome
        $Name = "name=\"$Nome\"";

        //ID do Campo
        $Id = ($Id == '') ? "id=\"$Nome\" " : "id=\"$Id\"";

        //Tipo
        $Type = strtolower($Type);

        switch ($Type) {
            case "password" : $Tipo = "type=\"password\"";
                $ModFil = false;
                break;
            case "hidden" : $Tipo = "type=\"hidden\"";
                $ModFil = false;
                break;
            case "text" : $Tipo = "type=\"text\"";
                break;
            case "submit" : $Tipo = "type=\"submit\"";
                $ModFil = false;
                break;
            case "reset" : $Tipo = "type=\"reset\"";
                $ModFil = false;
                break;
            case "button" : $Tipo = "type=\"button\"";
                $ModFil = false;
                break;
            case "checkbox" : $Tipo = "type=\"checkbox\"";
                $ModFil = false;
                break;
            case "radio" : $Tipo = "type=\"radio\"";
                $ModFil = false;
                break;
            case "file" : $Tipo = "type=\"file\"";
                $ModFil = false;
                break;
            case "image" : $Tipo = "type=\"image\"";
                break;
            default : $Tipo = "type=\"text\"";
                $ModFil = false;
        }

        //CheckBox e RadioBox
        $Checado = null;

        if ($Type == "checkbox" or $Type == "radio") {
            $Checked = $CFG['Checked'];

            if ($Checked == $Nome or $CFG['Vale'] == $Valor)
                $Checado = "checked=\"checked\"";

            $Valor = $CFG['Vale'];
        }

        if ($Type == "image") {
            $SRC = "src=\"" . $CFG['SRC'] . "\"";
        }

        if ($Type == "hidden") {
            $CFG['Obrigatorio'] = false;
        }

        //Valor
        $Value = " value=\"$Valor\" ";

        //Largura
        $Size = (is_numeric($Largura)) ? "size=\"$Largura\"" : "";

        //Mï¿½ximo
        $Len = (is_numeric($Max)) ? "maxlength=\"$Max\"" : "";

        //Estilo
        $Estilo = ($Estilo != '') ? "style=\"$Estilo\"" : "";

        //Caixa - Alta ou Baixa
        if ($CFG['Caixa'] != '') {
            $CFG['Caixa'] = strtoupper($CFG['Caixa']);

            if ($CFG['Caixa'] == "ALTA") {
                $Estilo = "style=\"text-transform: uppercase; $Estilo\"";
            } else if ($CFG['Caixa'] == "BAIXA") {
                $Estilo = "style=\"text-transform: lowercase; $Estilo\"";
            }
        }

        //Habilitaï¿½ï¿½o do Campo - Status
        $Disable = ($Status === false) ? "disabled=\"disabled\"" : "";

        if ($Type == "checkbox" or $Type == "radio") {
            $Retorno = sprintf("<label><input %s %s %s %s %s %s %s %s %s %s %s/>", $Name, $Id, $Tipo, $Value, $Size, $Len, $Estilo, $Adicional, $Checado, $Disable, $SRC) . $this->obrigatorio($CFG['Obrigatorio'] . "</label>");
        } else {
            $Retorno = sprintf("<input %s %s %s %s %s %s %s %s %s %s %s/>", $Name, $Id, $Tipo, $Value, $Size, $Len, $Estilo, $Adicional, $Checado, $Disable, $SRC) . $this->obrigatorio($CFG['Obrigatorio']);
        }

        if ($Conteiner != '') {
            return ($ModFil == true) ? "<span id=\"$Conteiner\">" . $this->opFiltro($CFG, $Retorno) . "</span>" : "<span id=\"$Conteiner\">" . $Retorno . "</span>";
        } else {
            return ($ModFil == true) ? $this->opFiltro($CFG, $Retorno) : $Retorno;
        }
    }

    public function htmlEditorInput($CFG, $Valor)
    {
        $Nome = $CFG['Nome'];      //Nome do Campo                              - Obrigatório
        $Largura = $CFG['Largura'];   //Largura do Campo                           - Opcional     Default:Sem Largura;
        $Altura = $CFG['Altura'];    //Máximo de Caracteres Permitido           - Opcional     Default:null;
        $Valor = $CFG['Valor'];
        $Ferramentas = ($CFG['Ferramentas'] == '') ? "Default" : $CFG['Ferramentas'];

        //Instancia Editor
        include_once($_SESSION['FMBase'] . 'editor.class.php');

        $Edit = new Editor();

        $Edit->InstanceName = $Nome;
        $Edit->Width = $Largura;
        $Edit->Height = $Altura;
        $Edit->ToolbarSet = $Ferramentas;
        $Edit->Value = $Valor;

        return $Edit->CreateHtml();
    }

    public function htmlTextArea($CFG)
    {
        $Nome = $CFG['Nome'];      //Nome do Campo                              - Obrigatório
        $Valor = $CFG['Valor'];     //Valor do Campo                             - Obrigatório
        $Id = $CFG['Id'];        //Id do Campo                                - Opcional     Default:$Nome;
        $Linhas = $CFG['Linhas'];    //Máximo de Caracteres Permitido             - Opcional     Default:null;
        $Colunas = $CFG['Colunas'];   //Mínimo de Caracteres Permitido             - Opcional     Default:null;
        $Status = $CFG['Status'];    //Status do Campo true ativo false inativo   - Opcional     Default:true;
        $Estilo = $CFG['Estilo'];    //Estilo CSS                                 - Opcional     Default:null;
        $Adicional = $CFG['Adicional']; //Adcional                                   - Opcional     Default null;
        //Nome
        $Name = "name=\"$Nome\"";

        //ID do Campo
        $Id = ($Id == '') ? "id=\"$Nome\" " : "id=\"$Id\"";

        //Linhas e Colunas
        $LC = " cols=\"" . $Colunas . "\" rows=\"" . $Linhas . "\" ";

        //Estilo
        $Estilo = ($Estilo != '') ? "style=\"$Estilo\"" : "";

        //Caixa - Alta ou Baixa
        if ($CFG['Caixa'] != '') {
            $CFG['Caixa'] = strtoupper($CFG['Caixa']);

            if ($CFG['Caixa'] == "ALTA") {
                $Estilo = "style=\"text-transform: uppercase; $Estilo\"";
            } else if ($CFG['Caixa'] == "BAIXA") {
                $Estilo = "style=\"text-transform: lowercase; $Estilo\"";
            }
        }

        //Habilitação do Campo - Status
        $Disable = ($Status === false) ? "disabled" : "";

        return sprintf("<textarea %s %s %s %s %s %s >%s</textarea>", $Name, $Id, $LC, $Estilo, $Adicional, $Disable, $Valor) . $this->obrigatorio($CFG['Obrigatorio']);
    }

    public function htmlSelect($CFG, $Valor)
    {
        $Nome = $CFG['Nome'];      //Nome do Campo                              - Obrigatório
        $Id = $CFG['Id'];        //Id do Campo                                - Opcional     Default:$Nome;
        $Multiplo = $CFG['Multiplo'];
        $Altura = $CFG['Altura'];
        $Status = $CFG['Status'];    //Status do Campo true ativo false inativo   - Opcional     Default:true;
        $Estilo = $CFG['Estilo'];    //Estilo CSS                                 - Opcional     Default:null;
        $Adicional = $CFG['Adicional']; //Adcional
        $UrlNovo = null; //$CFG['UrlNovo'];
        //Modo Filtro
        $ModFil = $this->getModFiltro();

        //Verifica a existencia de opï¿½ï¿½o de novo registro
        $DadosNovo = "";
        if (!empty($UrlNovo)) {
            $NomeForm = $this->getNomeForm();
            $PopLargura = $CFG['PopLargura'];
            $PopAltura = $CFG['PopAltura'];

            if (!empty($NomeForm))
                $DadosNovo = '<img src="' . $_SESSION['UrlBase'] . 'figuras/bt_novo.gif" onClick="add_novo(\'' . $UrlNovo . '\',\'' . $NomeForm . '\',\'' . $Nome . '\',\'' . $PopLargura . '\',\'' . $PopAltura . '\')" style="cursor:pointer; padding-left:5px;">';
        }

        //Nome
        $Name = "name=\"$Nome\"";

        //ID do Campo
        $Id = ($Id == '') ? "id=\"$Nome\" " : "id=\"$Id\"";

        //Habilitaçao do Campo - Status
        $Disable = ($Status === false) ? "disabled=\"disabled\"" : null;

        //Tipo
        if ($Multiplo === true) {
            $Alt = ($Altura == '') ? 1 : $Altura;

            $Mult = "multiple=\"multiple\" size=\"$Alt\"";
        }

        //Estilo
        $Estilo = ($Estilo != '') ? "style=\"$Estilo\"" : null;

        $Retorno = sprintf("<select %s %s %s %s %s %s>%s</select>", $Name, $Id, $Adicional, $Disable, $Mult, $Estilo, $Valor) . $this->obrigatorio($CFG['Obrigatorio']) . $DadosNovo;

        return ($ModFil == true) ? $this->opFiltro($CFG, $Retorno) : $Retorno;
    }

    /**
     * 	Cria Campo do Tipo Input Simples
     * 	@param Config Array() - Array de Configuraï¿½ï¿½es
     * 	@param Env String
     * 	@return String
     */
    public function inputTexto($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Texto";

        $CFG['Obrigatorio'] = $Obrigatorio;

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "Texto");
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $Valor, "text");
        }
    }

    public function inputHtmlEditor($CFG, $Obrigatorio = true)
    {

        $Env = parent::getEnv();
        if ($CFG['Popup'] == true) {
            $C["Id"] = $CFG["Id"];
            $C["Nome"] = $CFG["Nome"];
            $C["Tratar"] = $CFG["Tratar"];
            if ($Env) {
                $C["Valor"] = $CFG["Valor"];
            } else {

                $C["Valor"] = htmlspecialchars($CFG["Valor"]);
            }
            $Hidden = $this->inputHidden($C, $Obrigatorio);
            $Html = $this->divEditorPopup($CFG);
            return $Hidden . $Html;
        } else {

            if ($CFG['ProcessarComo'] == '')
                $CFG['ProcessarComo'] = "Texto";

            $CFG['Obrigatorio'] = $Obrigatorio;

            parent::setBufferCFG($CFG);

            //Valida pro YouTube
            $CFG['Valor'] = str_replace("youtube.com/watch?v=", "youtube.com/v/", $CFG['Valor']);

            if ($this->ProcessarValidacao) {
                $Valor = $this->validar($CFG, "Texto");
            }

            if ($this->ProcessarHtml) {
                return $this->htmlEditorInput($CFG, $Valor);
            }
        }
    }

    public function divEditorPopup($CFG)
    {

        $Nome = $CFG['Nome'];      //Nome do Campo                              - Obrigatório
        $Largura = $CFG['Largura'];   //Largura do Campo                           - Opcional     Default:Sem Largura;
        $Altura = $CFG['Altura'];    //Máximo de Caracteres Permitido           - Opcional     Default:null;
        $Valor = $CFG['Valor'];
        $NomeEditar = (empty($CFG['NomeEditar'])) ? "editar" : $CFG['NomeEditar'];
        $TituloGravar = (empty($CFG['NomeGravar'])) ? "Gravar" : $CFG['NomeGravar'];
        $Pasta = (empty($CFG['Pasta'])) ? "editor" : $CFG['Pasta'];
        $Ferramentas = ($CFG['Ferramentas'] == '') ? "Default" : $CFG['Ferramentas'];
        $Html = '


                <div class="editor_popup" style="width:' . $Largura . ';">
                        <div class="editor_popup_titulo"  style="cursor:pointer" onClick=\'window.open("' . $_SESSION['UrlBase'] . $Pasta . '/editor.tpl.php?Nome=' . $Nome . '&Ferramentas=' . $Ferramentas . '&Pasta=' . $Pasta . '&TituloGravar=' . $TituloGravar . '", "' . $Nome . '", "status=no, scrollbars=yes, width=680, height=520")\'>

                    ' . $NomeEditar . '
                    </div>
                    <div class="editor_popup_conteudo" style="height:' . $Altura . ';" id ="' . $Nome . '_html">
                    ' . $Valor . '
                    </div>
                </div>';

        return $Html;
    }

    public function inputSuggest($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Texto";

        $CFG['Obrigatorio'] = $Obrigatorio;

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "Suggest");
            $Valor = $this->validar($CFG, "Texto");
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $Valor, "text");
        }
    }

    public function inputSenha($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Texto";

        $CFG['Obrigatorio'] = $Obrigatorio;

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "Texto");
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $Valor, "password");
        }
    }

    public function inputHidden($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Texto";

        $CFG['Obrigatorio'] = $Obrigatorio;

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "Hidden");
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $CFG['Valor'], "hidden");
        }
    }

    public function inputInteiro($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Inteiro";

        $CFG['Obrigatorio'] = $Obrigatorio;

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "Inteiro");
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $Valor, "text");
        }
    }

    public function inputFloat($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Float";

        $CFG['Obrigatorio'] = $Obrigatorio;
        $CFG['Casas'] = ($CFG['Casas'] == '') ? 2 : $CFG['Casas'];

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "Float");
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $Valor, "text");
        }
    }

    public function inputData($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Data";

        $CFG['Obrigatorio'] = $Obrigatorio;
        $CFG['Largura'] = ($CFG['Largura'] == '') ? 10 : $CFG['Largura'];

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "Data");
            $Valor = ($Valor == "00/00/0000") ? "" : $Valor;
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $Valor, "text");
        }
    }

    public function inputCPF($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Texto";

        $CFG['Obrigatorio'] = $Obrigatorio;
        $CFG['Largura'] = ($CFG['Largura'] == '') ? 14 : $CFG['Largura'];
        $CFG['Max'] = 14;
        $CFG['Min'] = 14;

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "CPF");
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $Valor, "text");
        }
    }

    public function inputCNPJ($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Texto";

        $CFG['Obrigatorio'] = $Obrigatorio;
        $CFG['Largura'] = ($CFG['Largura'] == '') ? 18 : $CFG['Largura'];
        $CFG['Max'] = 18;
        $CFG['Min'] = 18;

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "CNPJ");
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $Valor, "text");
        }
    }

    public function inputCEP($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Texto";

        $CFG['Obrigatorio'] = $Obrigatorio;
        $CFG['Largura'] = ($CFG['Largura'] == '') ? 10 : $CFG['Largura'];
        $CFG['Max'] = 9;
        $CFG['Min'] = 9;

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "CEP");
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $Valor, "text");
        }
    }

    public function inputEmail($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Texto";

        $CFG['Obrigatorio'] = $Obrigatorio;
        $CFG['Largura'] = ($CFG['Largura'] == '') ? 30 : $CFG['Largura'];
        $CFG['Max'] = ($CFG['Max'] == '') ? 50 : $CFG['Max'];
        $CFG['Min'] = ($CFG['Min'] == '') ? 06 : $CFG['Max'];

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "Email");
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $Valor, "text");
        }
    }

    public function inputFone($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Texto";

        $CFG['Obrigatorio'] = $Obrigatorio;
        $CFG['Largura'] = ($CFG['Largura'] == '') ? 20 : $CFG['Largura'];
        $CFG['Max'] = ($CFG['Max'] == '') ? 20 : $CFG['Max'];
        $CFG['Min'] = ($CFG['Min'] == '') ? 20 : $CFG['Max'];

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "Fone");
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $Valor, "text");
        }
    }

    public function uploadMultiplo($CFG, $CodigoReferencia = null)
    {
        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $this->ValidaJS->validar($CFG, "UploadMultiplo");
        }

        //Processamento
        if (!$this->ProcessarHtml)
            return;

        $Op = parent::getOp();
        $NomeForm = $this->getNomeForm();

        $Nome = $CFG['Nome'] . $CodigoReferencia; //Nome do Campo - Obrigatório
        $Tabela = $CFG['Tabela']; //Tabela onde estão os arquivos
        $Status = $CFG['Status']; //Status do Campo true ativo false inativo - Opcional Default:true;
        $Adicional = $CFG['Adicional']; //Adcional - Opcional Default null;
        $MaximoArquivos = $CFG['MaximoArquivos']; //Máximo de Arquivos Permitido - Opcional Default:null;
        $ExtensaoPermitida = $CFG['ExtensaoPermitida']; //Extenssões de arquivos permitidos - Opcional Default:null;
        $HabilitarPrincipal = $CFG['HabilitarPrincipal'];
        $HtmlDepois = addslashes($CFG['HtmlDepois']); //Html a adicionar em cada campo - Opcional Default:null;
        $HtmlAntes = addslashes($CFG['HtmlAntes']); //Html a adicionar em cada campo - Opcional Default:null;
        $Linguagem = $CFG['Linguagem']; //Strings de localização a ser usado - Opcional Default:array(ver abaixo);
        $DiretorioDestino = $CFG['DiretorioDestino'];
        $DiretorioDestinoTB = $CFG['DiretorioDestinoTB'];

        //Verificações basicas
        if ($MaximoArquivos == "") {
            $MaximoPermitido = ini_get("max_file_uploads");
        } else {
            if ($MaximoArquivos{0} == "+") {
                $MaximoPermitido = substr($MaximoArquivos, 1);
            } else { //if($MaximoArquivos{0} != "+" and $Op != "Alt")
                $MaximoPermitido = $MaximoArquivos;
            }
        }

        $HtmlArquivos = "";

        if ($Op == "Alt" and $CodigoReferencia) {
            $Con = Conexao::conectar();
            $RS = $Con->executar("SELECT ArquivoCod, CodigoReferencia, Nome, Extensao, Principal FROM " . $Tabela . " WHERE CodigoReferencia = $CodigoReferencia");
            $NR = $Con->nLinhas($RS);

            $TotalPermitido = $MaximoPermitido;

            if ($MaximoArquivos{0} != "+") {
                $MaximoPermitido = $MaximoArquivos - $NR;
            }

            if ($NR > 0) {
                $HtmlArquivos .= '<div style="clear:both"></div>';
                while ($Dados = mysqli_fetch_array($RS)) {
                    $HtmlArquivos.= '<div class="upload_texto">';
                    //HTML Antes e Depois
                    if ($HabilitarPrincipal == true) {
                        $Checked = $Dados['Principal'] == 'S' ? "checked='checked'" : "";

                        $Depois = "<label><input name='" . $Nome . "Principal' type='radio' value='" . $Dados['ArquivoCod'] . "' " . $Checked . "> Marcar como principal</label>";
                    }

                    $CaminhoTB = (empty($DiretorioDestinoTB)) ? urlencode($DiretorioDestino) : urlencode($DiretorioDestinoTB);
                    $Caminho = urlencode($DiretorioDestino);

                    $MostraArquivo = '<a href="' . $_SESSION['UrlFMBase'] . 'arquivo_download.class.php?Caminho=' . $Caminho . '&TB=' . $Tabela . '&ArquivoCod=' . $Dados['ArquivoCod'] . '&Modo=Download" target="_blank"><img src="' . $_SESSION['UrlFMBase'] . 'arquivo_download.class.php?Caminho=' . $CaminhoTB . '&TB=' . $Tabela . '&ArquivoCod=' . $Dados['ArquivoCod'] . '&Modo=Ver" alt="' . $Dados['Nome'] . '" border="0" /></a>';
                    //Debug para Windows
                    //$MostraArquivo = '<a href="'.$_SESSION['UrlFMBase'].'arquivo_download.class.php?Caminho='.$Caminho.'&TB='.$Tabela.'&ArquivoCod='.$Dados['ArquivoCod'].'&Modo=Download" target="_blank"><img src="'.$_SESSION['UrlBase'].'arquivos/produtos/2/tb/11qTsnXw452ms12w1KVPry0Rcg10" alt="'.$Dados['Nome'].'" border="0" /></a>';

                    $HtmlArquivos .= '<div class="upload_imagem">' . $MostraArquivo . '</div>';
                    $HtmlArquivos .= '<div>';
                    $HtmlArquivos .= '	<input name="' . $Nome . 'IdDoBanco[]" type="hidden" value="' . $Dados['ArquivoCod'] . '">';
                    $HtmlArquivos .= $HtmlAntes . '<strong>Arquivo:</strong> ' . $Dados['Nome'] . '<br />
										<label><input type="checkbox" name="' . $Nome . 'Removidos[]" id="' . $Nome . 'Removidos' . $Dados['ArquivoCod'] . '" value="' . $Dados['ArquivoCod'] . '" checked="checked" onclick="sis_analiza_quantidade(\'' . $Nome . '\',' . $TotalPermitido . ',  \'' . $MaximoArquivos{0} . '\', this)" /> Desejo manter este arquivo</label> ' . $HtmlDepois . '<br />
										' . $Depois;
                    $HtmlArquivos .= '</div>';
                    $HtmlArquivos .= '<div style="clear:both"></div>';
                    $HtmlArquivos .= '</div>';
                }
            }
        }

        //Desabilita Campo
        if ($MaximoPermitido < 1) {
            $Status = false;
        }

        //Nome
        $Name = 'name="' . $Nome . '[{Id}]"';

        //Habilitação do Campo - Status
        $Disabled = ($Status === false) ? "disabled" : "";

        //HTML Antes e Depois
        if ($HabilitarPrincipal == true)
            $HtmlDepois .= "<label class='upload_principal'><input name='" . $Nome . "Principal' type='radio' value='{Id}'> Principal</label>";

        // se o campo nï¿½o estï¿½ desabilitado:
        $MaximoArquivosJs = (empty($MaximoPermitido) ? '' : 'max:' . intval($MaximoPermitido) . ',');
        $ExtensaoPermitidaJs = (empty($ExtensaoPermitida) ? '' : "accept:'" . join('|', $ExtensaoPermitida) . "',");
        $HtmlAntes = (empty($HtmlAntes) ? '' : 'html_elem_prepend: "' . $HtmlAntes . '",');
        $HtmlDepois = (empty($HtmlDepois) ? '' : 'html_elem_append: "' . $HtmlDepois . '",');
        $AoSelecionar = $Op == "Alt" ? 'afterFileSelect: function(element, value, master_element){ sis_analiza_quantidade(\'' . $Nome . '\',' . $TotalPermitido . ',  \'' . $MaximoArquivos{0} . '\', $("#' . $Nome . '")); },' : '';

        if (empty($Linguagem)) {
            $Linguagem = array(
                'remove' => '<img src="' . $_SESSION['CSSBase'] . 'figuras/bt_remover_img.gif" alt="Remover" border="0" align="absmiddle" />',
                'selected' => 'Selecionado: $file',
                'denied' => 'Arquivo tipo $ext não é aceito!',
                'duplicate' => 'Arquivo ja selecionado:\n$file!');
        }

        $StrJs = "
        $('#" . $Nome . "').MultiFile({
            $ExtensaoPermitidaJs
            $HtmlAntes
            $HtmlDepois
            $MaximoArquivosJs
            $AoSelecionar 
            STRING: {
                remove:'" . $Linguagem['remove'] . "',
                selected:'" . $Linguagem['selected'] . "',
                denied:'" . $Linguagem['denied'] . "',
                duplicate:'" . $Linguagem['duplicate'] . "'
            }
        });
        ";

        $this->setOnLoad("uploadMultiplo" . $Nome, $StrJs);

        //ID do Campo
        $Id = "id=\"$Nome\"";

        return sprintf("<input type=\"file\" %s %s %s %s >", $Name, $Id, $Adicional, $Disabled) . $this->obrigatorio($CFG['Obrigatorio']) . $HtmlArquivos;
    }

    /**
     * Upload multiplo de JQuery
     *
     * @author Bruno Alexandre Blank Cassol, DEMP
     * @version 0.2
     * @since 10/12/2008
     */
    public function uploadMultiploJQuery($CFG)
    {
        //JavaScript
        $this->ValidaJS->validar($CFG, "UploadMultiploJQ");

        $Nome = $CFG['Nome'];  //Nome do Campo						- Obrigatório
        $Id = $CFG['Id'];  //Id do Campo						- Opcional     Default:$Nome;
        $Status = $CFG['Status'];  //Status do Campo true ativo false inativo		- Opcional     Default:true;
        $Adicional = $CFG['Adicional']; //Adcional						- Opcional     Default null;
        $Max = $CFG['Max'];  //Máximo de Arquivos Permitido				- Opcional     Default:null;
        $Tipos = $CFG['Tipos'];  //Extenssões de arquivos permitidos			- Opcional     Default:null;
        $HtmlDepois = $CFG['HtmlDepois']; //Html a adicionar em cada campo			- Opcional     Default:null;
        $HtmlAntes = $CFG['HtmlAntes']; //Html a adicionar em cada campo			- Opcional     Default:null;
        $Linguagem = $CFG['Linguagem']; //Strings de localização a ser usado			- Opcional     Default:array(ver abaixo);
        //Nome
        $Name = 'name="' . $Nome . '[]"';

        //ID do Campo
        if ($Id == '')
            $Id = $Nome;

        //Habilitaï¿½ï¿½o do Campo - Status
        $Disabled = ($Status === false) ? "disabled" : "";

        // se o campo nï¿½o estï¿½ desabilitado:
        if (!$Disabled) {
            $Max = (empty($Max) ? '' : 'max:' . intval($Max) . ',');
            $Tipos = (empty($Tipos) ? '' : "accept:'" . join('|', $Tipos) . "',");
            $HtmlAntes = (empty($HtmlAntes) ? '' : 'html_elem_prepend: "' . addslashes($HtmlAntes) . '",');
            $HtmlDepois = (empty($HtmlDepois) ? '' : 'html_elem_append: "' . addslashes($HtmlDepois) . '",');
            if (empty($Linguagem)) {
                $Linguagem = array(
                    'remove' => 'Remover',
                    'selected' => 'Selecionado: $file',
                    'denied' => 'Invalido arquivo de tipo $ext!',
                    'duplicate' => 'Arquivo ja selecionado:\n$file!'
                );
            }

            $strJs = "
                $('#$Id').MultiFile({
                        $Tipos
                        $HtmlAntes
                        $HtmlDepois
                        $Max
                        STRING: {
                                remove:'" . $Linguagem['remove'] . "',
                                selected:'" . $Linguagem['selected'] . "',
                                denied:'" . $Linguagem['denied'] . "',
                                duplicate:'" . $Linguagem['duplicate'] . "'
                        }
                });
                ";
            $this->setOnLoad("UploadMultiploJQuery" . $Id, $strJs);
        }

        //ID do Campo
        $Id = ($Id == '') ? "id=\"$Nome\" " : "id=\"$Id\"";

        return sprintf("<input type=\"file\" %s %s %s %s >", $Name, $Id, $Adicional, $Disabled) . $this->obrigatorio($CFG['Obrigatorio']);
    }

    public function textArea($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "Texto";

        $CFG['Obrigatorio'] = $Obrigatorio;
        $CFG['Linhas'] = ($CFG['Linhas'] == '') ? 30 : $CFG['Linhas'];
        $CFG['Colunas'] = ($CFG['Colunas'] == '') ? 5 : $CFG['Colunas'];

        parent::setBufferCFG($CFG);

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "Texto");
        }

        if ($this->ProcessarHtml) {
            $Campo = $this->htmlTextArea($CFG, $Valor);
        }

        return $Campo;
    }

    public function checkBox($CFG, $Obrigatorio = true)
    {
        $CFG['Obrigatorio'] = $Obrigatorio;

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $CFG['Valor'], "checkbox") . " " . $CFG['Identifica'] . " ";
        }
    }

    public function checkBoxGrupo($Config, $Obrigatorio = true)
    {
        $CFG['Obrigatorio'] = $Obrigatorio;

        if ($this->ProcessarHtml) {
            foreach ($Config as $Nome => $CFG) {
                $CFG['Nome'] = $Nome;

                $Retorno .= $this->htmlInput($CFG, $CFG['Valor'], "checkbox") . $Nome . " ";
            }

            return $Retorno;
        }
    }

    public function radioBox($CFG, $Obrigatorio = true, $TipoValidacao)
    {
        $CFG['Obrigatorio'] = $Obrigatorio;

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, $TipoValidacao);
        }

        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $Valor, "radio");
        }
    }

    public function listBox($CFG, $Obrigatorio = true, $Con = null)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "TextoInteiro";

        $CFG['Obrigatorio'] = $Obrigatorio;

        parent::setBufferCFG($CFG);

        $Tabela = $CFG['Tabela'];
        $CampoCod = $CFG['CampoCod'];
        $CampoDesc = $CFG['CampoDesc'];
        $Condicao = $CFG['Condicao'];
        $AutoSelect = $CFG['AutoSelect'];
        $FullSelect = $CFG['FullSelect'];

        //Array de Valores
        $ArrayValores = array();

        if ($this->ProcessarHtml) {
            if (!$Con)
                $Con = Conexao::conectar();

            //Verifica o Tipo de Busca
            if (!empty($AutoSelect))
                $Sql = "SELECT $AutoSelect FROM $Tabela $Condicao";
            elseif (!empty($FullSelect))
                $Sql = $FullSelect;
            else
                $Sql = "SELECT $CampoCod, $CampoDesc FROM $Tabela $Condicao";

            $Rs = $Con->executar($Sql);

            while ($Linha = @mysqli_fetch_array($Rs))
                $ArrayValores[$Linha[$CampoCod]] = $Linha[$CampoDesc];
        }

        //Adiciona o Vetor
        $CFG['Vetor'] = $ArrayValores;

        return $this->listaVetor($CFG, $Obrigatorio);
    }

    public function listaVetor($CFG, $Obrigatorio = true)
    {
        if ($CFG['ProcessarComo'] == '')
            $CFG['ProcessarComo'] = "TextoInteiro";

        $CFG['Obrigatorio'] = $Obrigatorio;

        parent::setBufferCFG($CFG);

        $Inicio = $CFG['Inicio'];
        $Vetor = $CFG['Vetor'];
        $Padrao = $CFG['Padrao'];
        $Conteiner = $CFG['Conteiner'];
        $Ordena = $CFG['Ordena'];
        $Multiplo = $CFG['Multiplo'];
        $Selecionados = $CFG['Selecionados'];

        //Ordenaï¿½ao
        if (!is_bool($Ordena)) {
            strtoupper($Ordena);
        }

        if ($Ordena !== false) {
            if ($Ordena == "ASC" or $Ordena == "") {
                $Vetor = $this->FPHP->ordenaVetor($Vetor);
            } elseif ($Ordena == "DESC") {
                $Vetor = $this->FPHP->ordenaVetor($Vetor);

                $Vetor = array_reverse($Vetor);
            }
        }

        //Obrigatorio
        $CFG['Obrigatorio'] = $Obrigatorio;

        if (!is_array($Vetor))
            throw new Exception("O argumento passado não é um array()");

        if ($this->ProcessarValidacao) {
            $Valor = $this->validar($CFG, "Select");
        }

        //Verifica se o valor apresentado esta contido no array
        /* if($Valor <> '' and !empty($Vetor))
          {
          if(!array_key_exists($Valor,$Vetor))
          throw new Exception("O valor selecionado nï¿½o ï¿½ vï¿½lido");
          } */

        if ($this->ProcessarHtml) {
            //Verifica Inicio
            if ($Inicio != "")
                $Campo = ($Inicio === true) ? "<option value=\"\">Selecione...</option>" : "<option value=\"\">$Inicio</option>";

            //É Selecionado ?
            $ESelecionado = false;

            //Gera Campo
            if ($Multiplo == true and $Selecionados != '') {
                foreach ($Vetor as $Chave => $Vale) {
                    $Campo .= "<option value=\"" . $Chave . "\" ";

                    if (in_array($Chave, $Selecionados)) {
                        $Campo .= "selected";
                    }

                    $Campo .= " > " . $Vale . " </option> ";
                }
            } else {
                foreach ($Vetor as $Chave => $Vale) {
                    $Campo .= "<option value=\"" . $Chave . "\" ";

                    if ($ESelecionado === false) {
                        if ($Valor == '') {
                            if ("$Padrao" === "$Chave") {
                                $ESelecionado = true;
                                $Campo .= "selected";
                            }
                        } elseif ("$Chave" === "$Valor") {
                            $ESelecionado = true;
                            $Campo .= "selected";
                        }
                    }

                    $Campo .= " > " . $Vale . " </option>";
                }
            }

            if ($Conteiner != '') {
                $Retorno = "<span id=\"$Conteiner\">" . $this->htmlSelect($CFG, $Campo) . "</span>";
            } else {
                $Retorno = $this->htmlSelect($CFG, $Campo);
            }
        }

        return $Retorno;
    }

    public function inputUF($Campo, $ValorCampo, $SetUF, $Adicional = NULL, $Inicio = NULL)
    {
        if ($this->ProcessarHtml) {
            if ($ValorCampo != '')
                $SetUF = $ValorCampo;

            parent::setCampoRetorna($Campo, $ValorCampo);

            $S = 'selected="selected"';


            //$ArrayEstados = array("AC"=>"Acre","AL"=>"Alagoas","AM"=>"Amazonas","AP"=>"Amapï¿½","BA"=>"Bahia","CE"=>"Cearï¿½","DF"=>"Distrito Federal","ES"=>"Espirito Santo","GO"=>"Goiï¿½s","MA"=>"Maranhï¿½o","MG"=>"Minas Gerais","MS"=>"Mato Grosso do Sul","MT"=>"Mato Grosso","PA"=>"Parï¿½","PB"=>"Paraï¿½ba","PE"=>"Pernambuco","PI"=>"Piauï¿½","PR"=>"Paranï¿½","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RO"=>"Rondï¿½nia","RR"=>"Roraima","RS"=>"Rio Grande do Sul","SC"=>"Santa Catarina","SE"=>"Sergipe","SP"=>"Sï¿½o Paulo","TO"=>"Tocantins");

            $StringInput = '<select name="' . $Campo . '" id="' . $Campo . '" ' . $Adicional . '>';

            if ($Inicio != '')
                $StringInput .= '<option value="">Todos</option>';

            $StringInput .= '<option value="AC"';
            if ($SetUF == "AC")
                $StringInput .= $S;
            $StringInput .='>AC</option>';
            $StringInput .= '<option value="AL"';
            if ($SetUF == "AL")
                $StringInput .= $S;
            $StringInput .='>AL</option>';
            $StringInput .= '<option value="AM"';
            if ($SetUF == "AM")
                $StringInput .= $S;
            $StringInput .='>AM</option>';
            $StringInput .= '<option value="AP"';
            if ($SetUF == "AP")
                $StringInput .= $S;
            $StringInput .='>AP</option>';
            $StringInput .= '<option value="BA"';
            if ($SetUF == "BA")
                $StringInput .= $S;
            $StringInput .='>BA</option>';
            $StringInput .= '<option value="CE"';
            if ($SetUF == "CE")
                $StringInput .= $S;
            $StringInput .='>CE</option>';
            $StringInput .= '<option value="DF"';
            if ($SetUF == "DF")
                $StringInput .= $S;
            $StringInput .='>DF</option>';
            $StringInput .= '<option value="ES"';
            if ($SetUF == "ES")
                $StringInput .= $S;
            $StringInput .='>ES</option>';
            $StringInput .= '<option value="GO"';
            if ($SetUF == "GO")
                $StringInput .= $S;
            $StringInput .='>GO</option>';
            $StringInput .= '<option value="MA"';
            if ($SetUF == "MA")
                $StringInput .= $S;
            $StringInput .='>MA</option>';
            $StringInput .= '<option value="MG"';
            if ($SetUF == "MG")
                $StringInput .= $S;
            $StringInput .='>MG</option>';
            $StringInput .= '<option value="MS"';
            if ($SetUF == "MS")
                $StringInput .= $S;
            $StringInput .='>MS</option>';
            $StringInput .= '<option value="MT"';
            if ($SetUF == "MT")
                $StringInput .= $S;
            $StringInput .='>MT</option>';
            $StringInput .= '<option value="PA"';
            if ($SetUF == "PA")
                $StringInput .= $S;
            $StringInput .='>PA</option>';
            $StringInput .= '<option value="PB"';
            if ($SetUF == "PB")
                $StringInput .= $S;
            $StringInput .='>PB</option>';
            $StringInput .= '<option value="PE"';
            if ($SetUF == "PE")
                $StringInput .= $S;
            $StringInput .='>PE</option>';
            $StringInput .= '<option value="PI"';
            if ($SetUF == "PI")
                $StringInput .= $S;
            $StringInput .='>PI</option>';
            $StringInput .= '<option value="PR"';
            if ($SetUF == "PR")
                $StringInput .= $S;
            $StringInput .='>PR</option>';
            $StringInput .= '<option value="RJ"';
            if ($SetUF == "RJ")
                $StringInput .= $S;
            $StringInput .='>RJ</option>';
            $StringInput .= '<option value="RN"';
            if ($SetUF == "RN")
                $StringInput .= $S;
            $StringInput .='>RN</option>';
            $StringInput .= '<option value="RO"';
            if ($SetUF == "RO")
                $StringInput .= $S;
            $StringInput .='>RO</option>';
            $StringInput .= '<option value="RR"';
            if ($SetUF == "RR")
                $StringInput .= $S;
            $StringInput .='>RR</option>';
            $StringInput .= '<option value="RS"';
            if ($SetUF == "RS")
                $StringInput .= $S;
            $StringInput .='>RS</option>';
            $StringInput .= '<option value="SC"';
            if ($SetUF == "SC")
                $StringInput .= $S;
            $StringInput .='>SC</option>';
            $StringInput .= '<option value="SE"';
            if ($SetUF == "SE")
                $StringInput .= $S;
            $StringInput .='>SE</option>';
            $StringInput .= '<option value="SP"';
            if ($SetUF == "SP")
                $StringInput .= $S;
            $StringInput .='>SP</option>';
            $StringInput .= '<option value="TO"';
            if ($SetUF == "TO")
                $StringInput .= $S;
            $StringInput .='>TO</option>';
            $StringInput .= '</select>';

            return $StringInput;
        }
    }

    public function inputMes($Campo, $ValorCampo, $SetMes)
    {
        if ($ValorCampo != '')
            $SetMes = $ValorCampo;

        parent::setCampoRetorna($Campo, $ValorCampo);

        $S = 'selected="selected"';

        $StringInput = '<select name="' . $Campo . '" id="' . $Campo . '">';
        $StringInput .= '<option value="1"';
        if ($SetMes == "1")
            $StringInput .= $S;
        $StringInput .='>Janeiro</option>';
        $StringInput .= '<option value="2"';
        if ($SetMes == "2")
            $StringInput .= $S;
        $StringInput .='>Fevereiro</option>';
        $StringInput .= '<option value="3"';
        if ($SetMes == "3")
            $StringInput .= $S;
        $StringInput .='>Março</option>';
        $StringInput .= '<option value="4"';
        if ($SetMes == "4")
            $StringInput .= $S;
        $StringInput .='>Abril</option>';
        $StringInput .= '<option value="5"';
        if ($SetMes == "5")
            $StringInput .= $S;
        $StringInput .='>Maio</option>';
        $StringInput .= '<option value="6"';
        if ($SetMes == "6")
            $StringInput .= $S;
        $StringInput .='>Junho</option>';
        $StringInput .= '<option value="7"';
        if ($SetMes == "7")
            $StringInput .= $S;
        $StringInput .='>Julho</option>';
        $StringInput .= '<option value="8"';
        if ($SetMes == "8")
            $StringInput .= $S;
        $StringInput .='>Agosto</option>';
        $StringInput .= '<option value="9"';
        if ($SetMes == "9")
            $StringInput .= $S;
        $StringInput .='>Setembro</option>';
        $StringInput .= '<option value="10"';
        if ($SetMes == "10")
            $StringInput .= $S;
        $StringInput .='>Outubro</option>';
        $StringInput .= '<option value="11"';
        if ($SetMes == "11")
            $StringInput .= $S;
        $StringInput .='>Novembro</option>';
        $StringInput .= '<option value="12"';
        if ($SetMes == "12")
            $StringInput .= $S;
        $StringInput .='>Dezembro</option>';
        $StringInput .= '</select>';

        return $StringInput;
    }

    public function botao($CFG)
    {
        if ($this->ProcessarHtml) {
            return $this->htmlInput($CFG, $CFG['Identifica'], $CFG['Tipo']);
        }
    }

    public function obrigatorio($Obrigatorio)
    {
        return($Obrigatorio === true) ? "<b class=\"obrigatorio\">*</b>" : "";
    }

    public function opFiltro($CFG, $Campo)
    {
        //Variaveis de Configurações
        $NomeCampo = $CFG['Nome'];
        $Identifica = $CFG['Identifica'];
        $TipoFiltro = $CFG['TipoFiltro'];
        $AddFiltro = $CFG['AddFiltro'];
        $PadraoFiltro = $CFG['PadraoFiltro'];

        //Index CSS
        $this->ZIndex -= 1;

        //Array de Tipos
        $ArrayTipos = $this->getTipoFiltro($TipoFiltro);

        //Adiciona Tipos Extras
        if (is_array($AddFiltro))
            $ArrayTipos += $AddFiltro;


        //Monta Extrutura Html
        if (count($ArrayTipos) > 0) {
            //Valor default
            if ($PadraoFiltro != '') {
                $PadraoFiltro = (in_array($PadraoFiltro, $ArrayTipos) and $PadraoFiltro != '') ? $PadraoFiltro : "=";
            } else {
                $PadraoFiltro = "=";
            }

            $HtmlFil = "";
            //Monta Html de opções
            foreach ($ArrayTipos as $Valor) {
                $HtmlFil .= ' <li>' . $Valor . '</li>';
            }

            //Html do Filtro
            $OpFiltro = '<div id="sis_filtro_' . $NomeCampo . '" class="s_filtro" style="z-index:' . $this->ZIndex . '; width:25px; float:left;"><ul id="fil_ini"  style="z-index:' . $this->ZIndex . ';">
                                     <li>' . $PadraoFiltro . '</li></ul><ul id="tudo" style="display:none; z-index:' . $this->ZIndex . ';">' . $HtmlFil . '</ul></div>';

            //Hidden que guarda o valor selecionado
            $Hidden = '<input name="hidden_sis_filtro_' . $NomeCampo . '" id="hidden_sis_filtro_' . $NomeCampo . '" type="hidden" value="' . $PadraoFiltro . '"/>';

            //Hidden que guarda o Tipo de Campo
            $Hidden .= '<input name="hiddent_sis_filtro_' . $NomeCampo . '" id="hiddent_sis_filtro_' . $NomeCampo . '" type="hidden" value=""/>';

            //Hidden que guarda a Identificaï¿½ï¿½o do Campo
            $Hidden .= '<input name="hiddeni_sis_filtro_' . $NomeCampo . '" id="hiddeni_sis_filtro_' . $NomeCampo . '" type="hidden" value="' . $Identifica . '"/>';

            //Retorno
            return $OpFiltro . $Hidden . $Campo;
        } else {
            //Se nï¿½o existem elementos retona somente o campo
            return $Campo;
        }
    }

    public function getTipoFiltro($TipoFiltro)
    {
        $TipoFiltro = strtolower($TipoFiltro);

        switch ($TipoFiltro) {
            case "completo":
                return array("=" => "=", "<>" => "<>", ">" => ">", "<" => "<", "<=" => "<=", ">=" => ">=", "*" => "*", "A*" => "A*", "*A" => "*A", "E" => "E", "OU" => "OU");
                break;

            case "valorvariavel":
                return array("=" => "=", "<>" => "<>", ">" => ">", "<" => "<", "<=" => "<=", ">=" => ">=", "E" => "E", "OU" => "OU");
                break;

            case "texto":
                return array("=" => "=", "<>" => "<>", "*" => "*", "A*" => "A*", "*A" => "*A", "E" => "E", "OU" => "OU");
                break;

            case "valorfixo":
                return array("=" => "=", "<>" => "<>", "E" => "E", "OU" => "OU");
                break;

            case "suggest":
                return array("=" => "=", "<>" => "<>", "*" => "*", "A*" => "A*", "*A" => "*A");
                break;

            case "igual":
                return array("=" => "=");
                break;

            case "diferente":
                return array("<>" => "<>");
                break;

            default: return array();
        }
    }

}
