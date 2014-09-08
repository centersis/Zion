<?

/**
 * 	@copyright DEMP - Solu��es em Tecnologia da Informa��o Ltda
 * 	@author Pablo Vanni - pablovanni@gmail.com
 * 	@since 23/02/2005
 * 	<br>�ltima Atualiza��o: 23/05/2006<br>
 * 	Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br>
 * 	@name Gera Script de valida��o no lado do cliente
 * 	@version 2.0
 * 	@package Framework
 */
//Classes Nescess�rias
include_once($_SESSION['FMBase'] . 'javascript.class.php');
include_once($_SESSION['FMBase'] . 'validacao_js.gs.php');

class ValidacaoJS extends ValidacaoJSGS
{

    //Atributo passivo para receber instancia
    private $JS;
    private $PopUp = false;
    private $UploadMultiplo = false;
    private $UploadMultiploJQ = false;

    //Construtor
    public function ValidacaoJS()
    {
        //Persiste classe extendida
        parent::ValidacaoJSGS();

        //Instancia Classe JavaScript
        $this->JS = new JavaScript();
    }

    /**
     * 	Retorna o codigo JavaScript para valida��o
     * 	@param Campo String - Nome do campo do formul�rio
     * 	@param Nome String - Nome do campo para identifica��o do usu�rio
     * 	@param Tipo String - Tamanho minimo que um campo pode ter
     * 	@param Max String - Tamanho maximo que um campo pode ter
     * 	@param Foco String - Se for verdadeiro o campo retorna foco 
     * 	@return Array()
     */
    public function validar($Config, $TipoCampo)
    {
        $Campo = $Config['Nome'];
        $Mensagem = $Config['Mensagem'];
        $Identifica = $Config['Identifica'];
        $ValidaJS = $Config['ValidaJS'];
        $Status = $Config['Status'];
        $Obrigatorio = $Config['Obrigatorio'];
        $Mascara = $Config['Mascara'];
        $OpcaoFiltro = $Config['TipoFiltro'];
        $Aba = $Config['Aba'];
        $NomeForm = $this->getNomeForm();
        $ChaveLoad = $NomeForm . $Campo;
        $AcessoDireto = "#$NomeForm #$Campo";

        //Maximo e Minimo
        $Max = (empty($Config['Max'])) ? 0 : $Config['Max'];
        $Min = (empty($Config['Min'])) ? 0 : $Config['Min'];
        $VMax = (empty($Config['VMax'])) ? 0 : $Config['VMax'];
        $VMin = (empty($Config['VMin'])) ? 0 : $Config['VMin'];

        //Gera C�digo de Abas
        $AcessoAba = "";

        if (!empty($Aba))
        {
            //Recupera o codigo do Form
            $CodForm = str_replace("FormManu", "", $NomeForm);

            $AcessoAba = ' manipulaAbas("' . $CodForm . '",' . $Aba . ');';
        }

        //JS de Filtro
        if (!empty($OpcaoFiltro))
            $this->setOpcaoFiltro('$("#' . $NomeForm . ' #sis_filtro_' . $Campo . '").sisFiltro("' . $TipoCampo . '");');

        //Filtra o Tipo de Campo
        switch ($TipoCampo)
        {
            //Tipo Texto
            case "Texto" :

                //Mascara se for setado
                if (!empty($Mascara))
                    $this->setMascara("$('$AcessoDireto').mask('" . $Mascara . "');");

                //Valida��o
                if ($ValidaJS === true)
                {
                    if ($Status !== false and $Obrigatorio === true)
                    {
                        $JS = $this->JS->blocoIf("!validaTexto(d.$Campo.value,$Max,$Min,'$Identifica')", $AcessoAba . $this->JS->erroValidacao($AcessoDireto) . $this->JS->blocoFocus($Campo, true) . $this->JS->blocoReturn(false));

                        $this->setStringJS($JS);
                    }
                }

                break;

            //Tipo Texto
            case "Select" :

                //Verifica Tipo ListBox
                if (!empty($Config['UrlNovo']))
                    $this->PopUp = true;

                //Valida��o
                if ($ValidaJS === true)
                {
                    if ($Status !== false and $Obrigatorio === true)
                    {
                        $JS = $this->JS->blocoIf("!validaTexto(d.$Campo.value,$Max,$Min,'$Identifica')", $AcessoAba . $this->JS->erroValidacao($AcessoDireto) . $this->JS->blocoFocus($Campo, true) . $this->JS->blocoReturn(false));

                        $this->setStringJS($JS);
                    }
                }

                break;

            //Tipo Inteiro
            case "Inteiro" :

                //Mascara se for setado
                $this->setMascara("$('$AcessoDireto').validation({ type: \"int\" });");

                if ($ValidaJS === true)
                {
                    if ($Status !== false and $Obrigatorio === true)
                    {
                        if (!is_numeric($Max))
                            $Max = "'$Max'";
                        if (!is_numeric($Min))
                            $Min = "'$Min'";
                        if (!is_numeric($VMax))
                            $VMax = "'$VMax'";
                        if (!is_numeric($VMin))
                            $VMin = "'$VMin'";

                        $JS = $this->JS->blocoIf("!validaFloat(d.$Campo.value,$Max,$Min,$VMax,$VMin,'$Identifica')", $AcessoAba . $this->JS->erroValidacao($AcessoDireto) . $this->JS->blocoFocus($Campo, true) . $this->JS->blocoReturn(false));

                        $this->setStringJS($JS);
                    }
                }

                break;

            //Tipo Flutuante
            case "Float" :

                $this->setMascara("$('$AcessoDireto').floatValue();");

                if ($ValidaJS === true)
                {
                    if ($Status !== false and $Obrigatorio === true)
                    {
                        if (!is_numeric($Max))
                            $Max = "'$Max'";
                        if (!is_numeric($Min))
                            $Min = "'$Min'";
                        if (!is_numeric($VMax))
                            $VMax = "'$VMax'";
                        if (!is_numeric($VMin))
                            $VMin = "'$VMin'";

                        $JS = $this->JS->blocoIf("!validaFloat(d.$Campo.value,$Max,$Min,$VMax,$VMin,'$Identifica')", $AcessoAba . $this->JS->erroValidacao($AcessoDireto) . $this->JS->blocoFocus($Campo, true) . $this->JS->blocoReturn(false));

                        $this->setStringJS($JS);
                    }
                }

                break;

            //Tipo Data
            case "Data" :

                //Calend�rio
                $this->Calendar = true;

                //Listagem dos Anos no Calendario
                $RangeInicial = $Config['CalendarioInicial'];
                $RangeFinal = $Config['CalendarioFinal'];

                $RangeInicial = (empty($RangeInicial) && !empty($RangeFinal)) ? ($RangeFinal <= date('Y')) ? $RangeFinal - 30 : date('Y') - 30 : $RangeInicial;

                $RangeFinal = (empty($RangeFinal) && !empty($RangeInicial)) ? ($RangeInicial <= date('Y')) ? $RangeInicial + 30 : date('Y') + 30 : $RangeFinal;

                if (!empty($RangeInicial) && !empty($RangeFinal))
                {
                    $Range = "{yearRange:'" . $RangeInicial . ":" . $RangeFinal . "'}";
                }

                //Mascara
                $this->setMascara("$('$AcessoDireto').calendar(" . $Range . ").mask('99/99/9999');");


                if ($ValidaJS === true)
                {
                    if ($Status !== false and $Obrigatorio === true)
                    {
                        $JS = $this->JS->blocoIf("!validaData(d.$Campo.value,'$VMax','$VMin','$Identifica')", $AcessoAba . $this->JS->erroValidacao($AcessoDireto) . $this->JS->blocoFocus($Campo, true) . $this->JS->blocoReturn(false));

                        $this->setStringJS($JS);
                    }
                }

                break;

            //Tipo CPF
            case "CPF" :

                //Mascara
                $this->setMascara("$('$AcessoDireto').mask('999.999.999-99');");

                if ($ValidaJS === true)
                {
                    if ($Status !== false and $Obrigatorio === true)
                    {
                        $JS = $this->JS->blocoIf("!validaCPF(d.$Campo.value, '$Identifica')", $AcessoAba . $this->JS->erroValidacao($AcessoDireto) . $this->JS->blocoFocus($Campo, true) . $this->JS->blocoReturn(false));

                        $this->setStringJS($JS);
                    }
                }

                break;

            //Tipo CNPJ
            case "CNPJ" :

                //Mascara
                $this->setMascara("$('$AcessoDireto').mask('99.999.999/9999-99');");

                if ($ValidaJS === true)
                {
                    if ($Status !== false and $Obrigatorio === true)
                    {
                        //$JS = $this->JS->blocoIf("!validaCNPJ(d.$Campo.value)", $AcessoAba.$this->JS->erroValidacao($AcessoDireto).$this->JS->blocoFocus($Campo, true).$this->JS->blocoReturn(false));						

                        $this->setStringJS($JS);
                    }
                }

                break;

            //Tipo CNPJ
            case "CEP" :

                //JS Para Filtro
                if (!empty($OpcaoFiltro))
                    $this->setOpcaoFiltro('$("#' . $NomeForm . ' #sis_filtro_' . $Campo . '").sisFiltro("CEP");');

                //Mascara
                $this->setMascara("$('$AcessoDireto').mask('99999-999');");

                if ($ValidaJS === true)
                {
                    if ($Status !== false and $Obrigatorio === true)
                    {
                        //$JS = $this->JS->blocoIf("!validaCEP(d.$Campo.value)", $AcessoAba.$this->JS->erroValidacao($AcessoDireto).$this->JS->blocoFocus($Campo, true).$this->JS->blocoReturn(false));						

                        $this->setStringJS($JS);
                    }
                }

                break;

            //Tipo E-Mail
            case "Email" :

                //JS Para Filtro
                if (!empty($OpcaoFiltro))
                    $this->setOpcaoFiltro('$("#' . $NomeForm . ' #sis_filtro_' . $Campo . '").sisFiltro("Email");');

                if ($ValidaJS === true)
                {
                    if ($Status !== false and $Obrigatorio === true)
                    {
                        $JS = $this->JS->blocoIf("!validaEmail(d.$Campo.value)", $AcessoAba . $this->JS->erroValidacao($AcessoDireto) . $this->JS->blocoFocus($Campo, true) . $this->JS->blocoReturn(false));

                        $this->setStringJS($JS);
                    }
                }

                break;

            //Tipo Telefone
            case "Fone" :

                //Mascara
                //$this->setMascara("$('$AcessoDireto').mask('(99)-9999-9999');");

                if ($ValidaJS === true)
                {
                    if ($Status !== false and $Obrigatorio === true)
                    {
                        $JS = $this->JS->blocoIf("!validaFone(d.$Campo.value)", $AcessoAba . $this->JS->erroValidacao($AcessoDireto) . $this->JS->blocoFocus($Campo, true) . $this->JS->blocoReturn(false));

                        $this->setStringJS($JS);
                    }
                }

                break;

            //Tipo Suggest
            case "Suggest" :

                $this->Suggest = true;

                $Tb = $Config['Tabela'];
                $Cp = $Config['Campo'];
                $Cpd = $Config['CampoCod'];
                $Cd = $Config['Condicao'];
                $Lm = $Config['Limite'];
                $Hidden = $Config['Hidden'];
                $Largura = (($Config['Largura'] * 6.8) < 260) ? 260 : ($Config['Largura'] * 6.8);
                $Pars = (empty($Config['Pars'])) ? "" : "," . $Config['Pars'];
                $Url = (empty($Config['Url'])) ? $_SESSION['UrlBase'] . 'framework/complete.class.php' : $Config['Url'];

                if (empty($Hidden))
                {
                    $this->setMascara('$("' . $AcessoDireto . '").autocomplete("' . $Url . '", { delay: 150,width: ' . $Largura . ',max:15,formatItem: formatItem,formatResult: formatResult, selectFirst: true, extraParams:{Tabela:"' . $Tb . '",Campo:"' . $Cp . '",Condicao:"' . $Cd . '",Limite:"' . $Lm . '"' . $Pars . '} }); $("' . $AcessoDireto . '").result( function(event, data, formatted){ $(this).find("..+/input").val(data[1]);});');
                }
                else
                {
                    $this->setMascara('$("' . $AcessoDireto . '").autocomplete("' . $Url . '", { delay: 150,width: ' . $Largura . ',max:15,formatItem: formatItem,formatResult: formatResult, selectFirst: true, extraParams:{Tabela:"' . $Tb . '",Campo:"' . $Cp . '",CampoCod:"' . $Cpd . '",Condicao:"' . $Cd . '",Limite:"' . $Lm . '"' . $Pars . '} }); $("' . $AcessoDireto . '").result( function(event, data, formatted){ $("#' . $NomeForm . ' #' . $Hidden . '").val(data[1]); $(this).find("..+/input").val(data[2]);});');
                }

                break;

            //Multiplos Arquivos
            case "UploadMultiplo":

                $this->UploadMultiplo = true;

                break;

            //Multiplos Arquivos
            case "UploadMultiploJQ":

                $this->UploadMultiploJQ = true;

                break;
        }
    }

    //Arquivos
    public function chamaArquivos()
    {
        $Arquivos = "";
        $Mascara = $this->getMascara();

        if ($this->Suggest == true)
        {
            $Arquivos .= $this->JS->chamaArquivoCss('autocomplete.css');
            $Arquivos .= $this->JS->chamaArquivoJs('autocomplete.js');
        }

        if ($this->Calendar == true)
        {
            $Arquivos .= $this->JS->chamaArquivoCss('calendar.css');
            $Arquivos .= $this->JS->chamaArquivoJs('calendar.js');
        }

        if (is_array($Mascara))
        {
            $Arquivos .= $this->JS->chamaArquivoJs('mascara.js');
        }

        if ($this->UploadMultiplo == true)
        {
            $Arquivos .= $this->JS->chamaArquivoJs('jquery.MultiFile1.4.js');
        }

        if ($this->UploadMultiploJQ == true)
        {
            $Arquivos .='
			<script type="text/javascript" src="' . $_SESSION['JSBase'] . 'js/jquery.MultiFile.js"></script>
			<script type="text/javascript" src="' . $_SESSION['JSBase'] . 'js/jquery.blockUI.js"></script>';
        }
        
        $Arquivos .= $this->JS->chamaArquivoJs('ckeditor/ckeditor.js');

        return $Arquivos;
    }

    public function geraMascaras()
    {
        $ArrayMascara = $this->getMascara();

        if (is_array($ArrayMascara) and !empty($ArrayMascara))
        {
            foreach ($ArrayMascara as $Valor)
                $Mascaras .= $Valor . "\n";

            return $this->JS->entreJS($this->JS->onLoad($Mascaras));
        }
    }

    public function geraOpcoesDeFiltro()
    {
        $ArrayOpcoes = $this->getOpcaoFiltro();

        if (is_array($ArrayOpcoes) and !empty($ArrayOpcoes))
        {
            foreach ($ArrayOpcoes as $Valor)
                $Opcoes .= $Valor . "\n";

            return $this->JS->entreJS($this->JS->onLoad($Opcoes));
        }
    }

    public function geraFuncoes()
    {
        //AddNovo
        if ($this->PopUp === true)
            $this->setFuncoes($this->JS->poupUp(600, 500));

        $Funcoes = $this->getFuncoes();

        if (is_array($Funcoes) and !empty($Funcoes))
        {
            foreach ($Funcoes as $StringFuncao)
            {
                $Script .= $StringFuncao;
            }

            return $this->JS->entreJS($Script);
        }
    }

    public function geraOnLoad()
    {
        $OnLoad = $this->getOnLoad();

        if (is_array($OnLoad) and !empty($OnLoad))
        {
            foreach ($OnLoad as $Valor)
                $Load .= $Valor . "\n";

            return $this->JS->entreJS($this->JS->onLoad($Load));
        }
    }

    /**
     * 	Gera fun�ao de valida�ao no Cliente
     * 	@param StringResultado String - Contem o c�digo para cada fun��o
     * 	@return String
     */
    public function geraValidacao($NomeFuncao, $NomeForm)
    {
        //String JS
        $Script = "";
        $StringJS = $this->getStringJS();

        //Seta abrevia��o para formul�rio
        $this->setNomeForm("d = document.$NomeForm;\n");

        //Monsta Valida��o
        if (is_array($StringJS))
        {
            $Script .= $this->JS->iniciaFuncao($NomeFuncao, NULL);
            $Script .= $this->getNomeForm();

            if (is_array($StringJS))
                foreach ($StringJS as $StringResultado)
                    $Script .= $StringResultado;

            //Código para desabilitar Botão
            $Script .= "\n" . '$("#' . $NomeForm . ' .manuOpcoes :button").attr("disabled","disabled");' . "\n";

            $Script .= $this->JS->blocoReturn(true);
            $Script .= $this->JS->finalizaFuncao();

            return $this->JS->entreJS($Script);
        }
        else
        {
            echo $this->JS->entreJS($this->JS->iniciaFuncao($NomeFuncao, NULL) . $this->JS->blocoReturn(true) . $this->JS->finalizaFuncao());
        }
    }

}

?>