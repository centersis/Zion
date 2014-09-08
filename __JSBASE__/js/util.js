// JavaScript Document
//GLOBAIS
var SIS_SELECIONA = null;
var SIS_MENSAGEM  = false;
//Ao Carregar a Página
$(document).ready(function(){
    $("#carregando").bind("ajaxStart", function(){
        sis_carregando();
    }).bind("ajaxComplete", function(){
        sis_carregado();
    }).bind("ajaxError", function(){
        sis_falha();
    });
});
//Carregando Padrão
function sis_carregando() {
    $('#carregando').show();
    if(SIS_MENSAGEM) {
        $('#mensagem').hide();
        $('#mensagem').removeClass('errorManu');
        $('#mensagem').empty();
    }
    sis_apagaMensagem();
}
// Só faz o cheat se for o IE  - Carregando seguir a página
//if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) { document.documentElement.onscroll = sis_updatePos; } function sis_updatePos(){ var pos = document.documentElement.scrollTop;  document.getElementById('loading').style.top = pos; }  
//Após Ter Carregado
function sis_carregado() {
    $("#carregando").hide("slow");
}
//Se ocorrer alguma falha na requisição AJAX
function sis_falha() {
    alert("O sistema não conseguiu completar esta operação\nverifique sua conexão com a internet e tente novamente.");
    $("#carregando").hide();
}
//Login
function sis_autLogin(Req){
    if(Req.responseText == "true") {
        $('#tabelaLogin').fadeOut();
        window.location="principal.php";
    } else {
        $('#erro').empty().html(Req.responseText);
        $('#erro').show("slow");
        setTimeout(function(){
            $("#erro").fadeOut();
        },5000);
    }
}
//Loga novamente caso perca sessao
function novoLogin() {
    $.post(URLBASE+"login/login.php", $("#FormLogin").serialize(), function(Req) {
        if(Req == "true") {
            setTimeout($.unblockUI, 500);
        } else {
            $("#erro").empty().html(Req);
            $("#erro").show("slow");
            setTimeout(function(){
                $("#erro").fadeOut();
            },5000);
        }
    });
}
//Conta Cheks Selecionados
function sis_contaCheck(){
    var abv = document.FormGrid;
    if(!$("FormGrid")) return 0;
    var Conta = 0;
    for (i=0;i<abv.elements.length;i++) {
        if(abv.elements[i].type == 'checkbox') {
            if(abv.elements[i].name != 'checkTodos'){
                if(abv.elements[i].checked == true) Conta+=1;
            }
        }
    }
    return Conta;
}
//Interceptadora da Funãão de Visualização
function sis_visualizar() {
    if(sis_contaCheck() == 0) {
        sis_mensagem('Nenhum registro foi selecionado');
        return;
    }
    visualiza(); /*$('#corpoPrincipal').empty();*/
}
//fazer noavemnte o login
function sis_novoLogin(){
    $("#FormLogin #UserName").val('');
    $("#FormLogin #UserPass").val('');
    $.blockUI({
        message: $('#loaderNovoLogin'), 
        css: {
            border: 'none', 
            cursor:'default', 
            padding: '15px', 
            backgroundColor: '#ffffff', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            color: '#fff'
        }
    });
}
//Função que intercepta retorno e verifica se é preciso fazer um novo login
function sis_interceptaRetorno(ret){
    if(ret == "sessaoexpirada") {
        sis_novoLogin();
        return false;
    }else{
        return true;
    }
}
//Verifica se houve sucesso no cadastro
function retornoCadastrar(ret) {
    if(!sis_interceptaRetorno(ret)){
        $("#FormManu div.manuOpcoes :button").attr("disabled",false);
        return;
    }
    if(ret == "true"){
        sis_sucesso('Registro cadastrado com sucesso!');
        if(document.FormManu.FormaCadastro[1].checked == true){
            sis_cadastrar();
            setTimeout(function(){
                document.FormManu.FormaCadastro[1].checked = true;
            }, 2000);
            sis_filtrar();
        } else {
            $('#manu').empty();
            sis_busca_filtro();
        }
    } else {
        
        $("#FormManu div.manuOpcoes :button").attr("disabled",false);
        SIS_MENSAGEM = true;
        sis_mensagem(ret);
    }
}
//Trata o resultado de um cadastro por popup
function retornoCadastrarPop(ret){
    if(!sis_interceptaRetorno(ret)) return;
    if(ret == "true"){
        window.opener.updateListBox(FORMGLOBAL, CAMPOGLOBAL);
        self.close();
    } else {
        SIS_MENSAGEM = true;
        sis_mensagem(ret);
    }
}
//Atualização da Grid
function sis_atualizar(modulo){
    sis_svo('','');
    sis_spa('');
    sis_apagaMensagem();
    $('#manu').empty();
    $.ajax({
        url:modulo+'.ajax.php?Op=Fil', 
        cache: true, 
        type: 'get', 
        datatype:'script',
        success:function(Req){
            if(!sis_interceptaRetorno(Req)) return;
            $('#corpoPrincipal').html(Req);
        }
    });
}
//Atualização da Grid Para PopUp
function sis_atualizar_pop(modulo,parametroExtra){
    sis_apagaMensagem();
    $('#manu').empty();
    $.ajax({
        url:modulo+'.ajax.php?Op=Fil'+parametroExtra, 
        cache: true, 
        type: 'get', 
        datatype:'script',
        success:function(Req){
            if(!sis_interceptaRetorno(Req)) return;
            $('#corpoPrincipal').html(Req);
        }
    });
}
//Mensagem de Sucesso
function sis_sucesso(msg) {
    $('#sucesso').addClass("sucessoManu");
    $('#sucesso').html(msg);
    $('#mensagem').show();
    setTimeout(sis_apagaSucesso, 4000);
}
//Remove mensagem de sucesso
function sis_apagaSucesso(){
    $('#sucesso').removeClass("sucessoManu");
    $('#sucesso').empty();
}
//Interceptadora da Função de Remoção
function sis_alterar(){
    if(sis_contaCheck() == 0) {
        sis_mensagem('Nenhum registro foi selecionado');
        return;
    }
    alteraForm();
}
//Verifica se houve sucesso no cadastro
function retornoAlterar(ret, conteiner) {
    if(!sis_interceptaRetorno(ret)){
        $("#"+conteiner+" .manuOpcoes :button").attr("disabled",false);
        return;
    }
    if(ret == "true") {
        sis_sucesso('Registro alterado com sucesso!');
        $('#'+conteiner).remove();
        sis_filtrar();
    } else {
        $("#"+conteiner+" .manuOpcoes :button").attr("disabled",false);
        SIS_MENSAGEM = true;
        sis_mensagem(ret);
    }
}
//Interceptadora da Função de Remoção
function sis_remover() {
    var Conta = sis_contaCheck();
    if(Conta == 0) {
        sis_mensagem('Nenhum registro foi selecionado');
        return;
    }
    var plural = (Conta == 1) ? '' : 's';
    if(confirm('Tem certeza que deseja apagar este'+plural+' '+Conta+' registro'+plural+'?')) sis_apagar();
}
//Verifica se houve sucesso na remoção
function retornoRemover(ret){
    if(!sis_interceptaRetorno(ret))return;
    var ini=$.trim(ret).substring(0,7);
    if(ini=='<script'){
        $("#mensagem").html(ret);
        return
    }
    if(ret=='auxiliaretornodeletecomissao')return;
    eval(ret);
    var se=parseInt(retorno['selecionados']);
    var ap=parseInt(retorno['apagados']);
    var ms=retorno['mensagem'];
    var possivelMensagem=(ms!=''&&ms!='undefined')?"Motivo:\n"+ms:ms;
    if(ap>0){
        if(ap!=se){
            var msgPlural=(ap==1)?'apenas foi removido com sucesso':'foram removidos com sucesso';
            var msgRemovidos="Entre os "+se+" registros selecionados "+ap+" "+msgPlural+".\n\n";
            alert("Atenção, nem todos os registros puderam ser removidos!\n\n"+msgRemovidos+possivelMensagem);
            sis_busca_filtro()
            }else{
            var plural=(ap==1)?'':'s';
            sis_sucesso('Registro'+plural+' removido'+plural+' com sucesso!');
            sis_busca_filtro()
            }
        }else{
    alert("Atenção nenhum registro selecionado pode ser removido!\n\n"+possivelMensagem)
    }
}
//Adiciona mensagem ao conteiner de mensagens
function sis_mensagem(msg){
    /*alert(msg); */ $('#mensagem').addClass("errorManu");$('#mensagem').html(msg);$('#mensagem').show();document.getElementById("mensagem").scrollIntoView();SIS_MENSAGEM=true;setTimeout(sis_apagaMensagem,10000)
}
//Apaga mensagem
function sis_apagaMensagem() {
    $('#mensagem').removeClass("errorManu");
    $('#mensagem').empty();
    SIS_MENSAGEM = false;
}
//Gerencia seleção de registros da grid
function sis_selecionar(){
    var abv = document.FormGrid;
    if(SIS_SELECIONA == null) {
        for (i=0;i<abv.elements.length;i++) if(abv.elements[i].type == "checkbox") abv.elements[i].checked=1; SIS_SELECIONA = 1
        } else {
        for (i=0;i<abv.elements.length;i++) if(abv.elements[i].type == "checkbox") abv.elements[i].checked=0; SIS_SELECIONA = null
        }
    } 
//Help
function sis_help(m){
    $("#help").load(URLBASE+'includes/help.php', {
        Mod:m
    }, function(){
        $('#help').show();
    });
}
/*GERENCIANDO FILTROS*/
function sisBuscarFiltro(modulo,parametros){
    $.ajax({
        url:modulo+'.ajax.php?Op=Fil',
        type:'get',
        datatype:'script',
        data:parametros,
        success:function(Req){
            if(!sis_interceptaRetorno(Req))return;
            $('#corpoPrincipal').html(Req);
            $('#sis_filtrar_filtro_ul').remove()
            }
        })
}
//Filtrar
function sisFiltrarFiltro(modulo){
    $.ajax({
        url:URLBASE+'framework/manter_filtro.class.php',
        type:'POST',
        data:{
            'Op':'Fil',
            'ModuloNome':modulo
        },
        success:function(Req){
            if(!sis_interceptaRetorno(Req))return;
            eval('var retorno = '+Req);
            if(retorno['Erro']==true){
                alert('Não foi possível recuperar o filtro, motivo:\n'+retorno['Retorno'])
                }else{
                var conteudoFiltro=retorno['Retorno'];
                $('#sis_filtrar_filtro').append(conteudoFiltro)
                }
            }
    })
}
//Cadastra Filtro
function sisCadastrarFiltro(modulo){
    var nomeFiltro='';
    var conteudoFiltro='';
    nomeFiltro=prompt("Digite um nome para identificar o seu filtro.","");
    if(nomeFiltro==''||nomeFiltro==null){
        return
    }
    conteudoFiltro=$('#FormFiltro').formSerialize();
    $.ajax({
        url:URLBASE+'framework/manter_filtro.class.php',
        type:'POST',
        data:{
            'Op':'Cad',
            'NomeFiltro':nomeFiltro,
            'ModuloNome':modulo,
            'ConteudoFiltro':conteudoFiltro
        },
        success:function(Req){
            if(!sis_interceptaRetorno(Req))return;
            eval('var retorno = '+Req);
            if(retorno['Erro']==true){
                alert('Não foi possível gravar o filtro, motivo:\n'+retorno['Retorno'])
                }else{
                sis_sucesso('Filtro Salvo Com Sucesso!')
                }
            }
    })
}
//Remove Filtro
function sisRemoverFiltro(modulo,filtroCod){
    if(confirm("Deseja mesmo remover este filtro?")){
        $.ajax({
            url:URLBASE+'framework/manter_filtro.class.php',
            type:'POST',
            data:{
                'Op':'Del',
                'ModuloNome':modulo,
                'FiltroCod':filtroCod
            },
            success:function(Req){
                if(!sis_interceptaRetorno(Req))return;
                eval('var retorno = '+Req);
                if(retorno['Erro']==true){
                    alert('Não foi possível remover o filtro, motivo:\n'+retorno['Retorno'])
                    }else{
                    sis_sucesso('Filtro Removido Com Sucesso!');
                    $('#sis_filtrar_filtro_ul').remove()
                    }
                }
        })
}
}
/*GERENCIANDO FILTROS*/
//Seta a pagina atual em um hiddem para filtros (sis_set_pagina_atual spa)
function sis_spa(p){
    $("#SisPaginaAtual").val(p);
}
//Seta informações de ordenção (sis_set_variaveis_ordenacao q = QuemOrdena, t = TipoOrdencao)
function sis_svo(q,t){
    $("#SisQuemOrdena").val(q);
    $("#SisTipoOrdenacao").val(t);
}
//Filtros
function sisShowFiltro(){
    var src = $("#imgSisFiltro").attr("src");
    var vetDir = src.split("/");
    var ultimaPosicao = vetDir.length - 1;
    var imgAtual = vetDir[(ultimaPosicao)];
    var novoSrc = "";
    if(imgAtual == "sis_filtro_mostrar.gif"){
        vetDir.pop();
        novoSrc = vetDir.join("/");
        $("#imgSisFiltro").attr("src",novoSrc+"/sis_filtro_ocultar.gif");
        $("#sis_salvar_filtro").show();
        $("#FormFiltro").fadeIn();
    }else{
        vetDir.pop();
        novoSrc = vetDir.join("/");
        $("#imgSisFiltro").attr("src",novoSrc+"/sis_filtro_mostrar.gif");
        $("#sis_salvar_filtro").hide();
        $("#FormFiltro").fadeOut();
    }
}
/*ABAS*/
function manipulaAbas(idForm,aba){
    $("#FormManu"+idForm+" div[id^=\'AbaConteudo\']").each(function(){
        $(this).hide();
        var titulo=$(this).attr("id");
        var id=titulo.substr(11,3);
        $("#FormManu"+idForm+" #AbaTitulo"+id).css("border-bottom","solid 1px #000000");
        $("#FormManu"+idForm+" #AbaTitulo"+id).css("background","#E7EFF7")
        });
    $("#FormManu"+idForm+" #AbaConteudo"+aba).show();
    $("#FormManu"+idForm+" #AbaTitulo"+aba).css("border-bottom","solid 1px #F1F4F7");
    $("#FormManu"+idForm+" #AbaTitulo"+aba).css("background","#F2F2F2")
    }
//Manipula Abas com Loader
function manipulaAbasLoadCross(idForm,aba, Url)
{
    $("#FormManu"+idForm+" div[id^=\'AbaConteudo\']").each(function() {
        $(this).hide();
        var titulo = $(this).attr("id");
        var id = titulo.substr(11,3);
        $("#FormManu"+idForm+" #tabBox"+id+" .b1").css("background-position","0 0");
        $("#FormManu"+idForm+" #tabBox"+id+" .b2").css("background-position","0 0");
        $("#FormManu"+idForm+" #tabBox"+id+" .tabConteudo").css("background-position","0 0");
        });
    $("#FormManu"+idForm+" #AbaConteudo"+aba).show();
    var checkCarregado = $("#FormManu"+idForm+" #AbaConteudo"+aba+" [name=formCarregado]").val();
    if(checkCarregado == "N") { 
        
    $.ajax({
        url: Url,
        type: 'GET',
        crossDomain:true,
        success: function(ret){
            $("#FormManu"+idForm+" #AbaConteudo"+aba+" #content").html(ret);
            $("#FormManu"+idForm+" #AbaConteudo"+aba+" [name=formCarregado]").val('S');
        }
    });
    
    $("#FormManu"+idForm+" #tabBox"+aba+" .b1").css("background-position","0 -32px");
    $("#FormManu"+idForm+" #tabBox"+aba+" .b2").css("background-position","0 -32px");
    $("#FormManu"+idForm+" #tabBox"+aba+" .tabConteudo").css("background-position","0 -32px")
    }
}

//Manipula Abas com Loader
function manipulaAbasLoad(idForm,aba, url){
    $("#FormManu"+idForm+" div[id^=\'AbaConteudo\']").each(function() {
        $(this).hide();
        var titulo = $(this).attr("id");
        var id = titulo.substr(11,3);
        $("#FormManu"+idForm+" #tabBox"+id+" .b1").css("background-position","0 0");
        $("#FormManu"+idForm+" #tabBox"+id+" .b2").css("background-position","0 0");
        $("#FormManu"+idForm+" #tabBox"+id+" .tabConteudo").css("background-position","0 0");
    });
    $("#FormManu"+idForm+" #AbaConteudo"+aba).show();
    var checkCarregado = $("#FormManu"+idForm+" #AbaConteudo"+aba+" [name=formCarregado]").val();
    if(checkCarregado == "N") {
        $("#FormManu"+idForm+" #AbaConteudo"+aba+" #content").load(url, function() {
            $("#FormManu"+idForm+" #AbaConteudo"+aba+" [name=formCarregado]").val('S');
        });
    }
    $("#FormManu"+idForm+" #tabBox"+aba+" .b1").css("background-position","0 -32px");
    $("#FormManu"+idForm+" #tabBox"+aba+" .b2").css("background-position","0 -32px");
    $("#FormManu"+idForm+" #tabBox"+aba+" .tabConteudo").css("background-position","0 -32px")
    }
//Manipula Abas
function manipulaAbasImagem(idForm,aba){
    $("#FormManu"+idForm+" div[id^=\'AbaConteudo\']").each(function(){
        $(this).hide();
        var titulo=$(this).attr("id");
        var id=titulo.substr(11,3);
        $("#FormManu"+idForm+" #tabBox"+id+" .b1").css("background-position","0 0");
        $("#FormManu"+idForm+" #tabBox"+id+" .b2").css("background-position","0 0");
        $("#FormManu"+idForm+" #tabBox"+id+" .tabConteudo").css("background-position","0 0")
        });
    $("#FormManu"+idForm+" #AbaConteudo"+aba).show();
    $("#FormManu"+idForm+" #tabBox"+aba+" .b1").css("background-position","0 -32px");
    $("#FormManu"+idForm+" #tabBox"+aba+" .b2").css("background-position","0 -32px");
    $("#FormManu"+idForm+" #tabBox"+aba+" .tabConteudo").css("background-position","0 -32px")
    }
/*BOTï¿½O IMPRIMIR*/
function sis_imprimir(modulo){
    var idSisPrintUl=$('#sis_print_ul').attr('id');
    $('#sis_print').attr('onclick','');
    if(idSisPrintUl!=undefined){
        return
    }else{
        var op1='<li><input name="sis_check_print" id="sis_check_print" type="radio" value="TP" checked> Todos os Registros Desta Página</li>';
        var op2='<li><input name="sis_check_print" id="sis_check_print" type="radio" value="TS"> Todos os Registros Selecionados</li>';
        var op3='<li><input name="sis_check_print" id="sis_check_print" type="radio" value="TF"> Todos os Registros Filtrados</li>';
        var op4='<li><input name="sis_check_print" id="sis_check_print" type="radio" value="TM"> Todos os Registros Deste Módulo</li>';
        var hr='<hr width="95%">';
        var btPrint='<input type="button" name="sis_bt_print" id="sis_bt_print" value="Imprimir" onClick="sis_imprimir_selecionado(\''+modulo+'\')">';
        var btDel='<input type="button" name="sis_bt_print_close" id="sis_bt_print_close" value="Fechar" onClick="sis_imprimir_remover(\''+modulo+'\')">';
        $('#sis_print').append('<ul id=sis_print_ul>'+op1+op2+op3+op4+hr+btPrint+btDel+'</ul>')
    }
}
//Sis Imprimir remnover
function sis_imprimir_remover(modulo){
    $('#sis_print').attr('onclick',function(modulo) {
        sis_imprimir(modulo);
        $('#sis_print_ul').remove();
    });
}
//Imprime Selecionados
function sis_imprimir_selecionado(modulo){
    var selecionados=new Array();
    var todosDaPagina=new Array();
    var parametros='';
    var opcao='';
    $('#sis_print_ul :checked').each(function(){
        opcao=$(this).val()
    });
    if(opcao=='TP'||opcao=='TS'){
        $('#FormGrid input[name^="SisReg"]').each(function(){
            if($(this).attr('checked')==true){
                selecionados.push($(this).attr('value'))
            }
            todosDaPagina.push($(this).attr('value'))
        });
        if(opcao=='TP'){
            if(todosDaPagina.length<1){
                alert('Não Foi encontrado nenhum registro para impressão!');
                $('#sis_print_ul').remove();
                return
            }
            parametros=arrayParaGet(todosDaPagina)
        }else{
            if(selecionados.length<1){
                alert('Nenhum registro foi selecionado para impressão!\nSelecione os registros desejados e tente novamente.');
                $('#sis_print_ul').remove();
                return
            }
            parametros=arrayParaGet(selecionados)
        }
    }else if(opcao=='TM'){
        parametros='TM=true'
    }else if(opcao=='TF'){
        parametros = $('#FormFiltro').formSerialize();
    }
    else{
        alert("Opção Inválida!");
        $('#sis_print_ul').remove();
        return
    }
    $('#sis_bt_print').attr('value','imprimindo...');
    $('#sis_print_ul input').attr('disabled','disabled');
    $('#sis_bt_print_close').attr('disabled',false);
    window.open(modulo+'.print.php?Op=Fil&ModoPrint=true&'+parametros,'sis_iframe_print')
}
//Sucersso na impressão
function sucessoPrint(){
    document.getElementById('sis_iframe_print').contentWindow.focus();
    document.getElementById('sis_iframe_print').contentWindow.print();
    $('#sis_print_ul').remove();
    return false
    }
//Erro na impressão
function erroPrint(){
    $('#sis_print_ul').remove();
    alert("Não Foi Possível Imprimir o Documento!");
    return false
    }
//Retorna Array com parametros
function arrayParaGet(array){
    var parametros='';
    var cont=0;
    var e='';
    for(var i in array){
        cont+=1;
        if(cont>1)e='&';
        parametros+=e+'SisReg['+array[i]+']='+array[i]
        }
        return parametros
    }
/*BOTÂO IMPRIMIR*/
// submit para iframe se form tiver campos de upload
function bdCadastraAltera(formAction,retornoCallback,ajaxCallback,formId,formName){
    
    $('.sis_ck').each(function(){ $(this).val(eval('CKEDITOR.instances.'+$(this).attr('referencia')+'.getData()')); });
    
    sis_carregando();
    if(formId==undefined)formId='';
    if(formName==undefined)formName='FormManu';
    var formSel='#'+formName+formId;
    var form=$(formSel);
    var uploads=$("input[type=file]",form);
    if(uploads.length){
        var valueAntigo=$(formSel+" input[name='Button2']").val();
        $(formSel+" input[name='Button2']").attr('disabled','disabled').val('aguarde carregando...');
        var nome_iframe='FormManuIframe'+formId;
        form.attr('target',nome_iframe);
        form.attr('action',formAction);
        form.append('<IFRAME style="display: none;" id="'+nome_iframe+'" name="'+nome_iframe+'">');
        $('#'+nome_iframe).load(function(){
            var conteudo=$('body',this.contentWindow.document).html();
            if(retornoCallback!=''){
                retornoCallback(conteudo,'fild'+formId)
                }
                setTimeout(function(){
                $('#'+nome_iframe).remove()
                },500);
            if(conteudo!='true'){
                $(formSel+" input[name='Button2']").attr('disabled',false).val(valueAntigo)
                }
            });
    form.submit()
    }else{
    ajaxCallback()
    }
    return false
}
//Converte o valor de float cliente para float banco 0,00 0.00
//modificada em 28 de outubro para converter quando numero tiver 4 digitos pï¿½s virgula
//function converteValor(valor){if((valor!="")&&(valor!=undefined)){var tamanho=valor.length;if(!isNaN(valor))return parseFloat(valor);if(tamanho>=4){var partes=valor.split(',');var tamanhoParte2=partes[1].length;var pfinal=valor.substr((tamanho-tamanhoParte2),tamanho);var v=partes[0].replace(".","");return parseFloat(v+"."+pfinal)}else{var v=valor.replace(",",".");return parseFloat(v)}}else{return 0}}
function converteValor(valor){
    if((valor!="")&&(valor!=undefined)){
        var tamanho=valor.length;
        if(!isNaN(valor))return parseFloat(valor);
        if(tamanho>=4){
            var partes=valor.split(',');
            var tamanhoParte2=partes[1].length;
            var pfinal=valor.substr((tamanho-tamanhoParte2),tamanho);
            var v= str_replace(".","",partes[0]);
            return parseFloat(v+"."+pfinal)
            }else{
            var v=valor.replace(",",".");
            return parseFloat(v)
            }
        }else{
    return 0
    }
}
//moeda cliente
function moedaCliente(valor){
    if(valor==""){
        return"0,00"
        }
        var mCliente=formata(""+valor+"");
    return convertePartes(mCliente)
    }
//esta função trata numeros com 2 e com 4 digitos apï¿½s a virgula, de modo transparente
function formata(valor){
    var tamanho=valor.length;
    var numeros="";
    var vPosicao="";
    var separa=false;
    if(tamanho<1)return"";
    for(var i=0;i<=tamanho;i++){
        vPosicao=valor.charAt(i);
        if(separa==false){
            if((vPosicao==","&&(i==tamanho-2||i==tamanho-3))||(vPosicao=="."&&(i==tamanho-2||i==tamanho-3))||(vPosicao==","&&(i==tamanho-4||i==tamanho-5))||(vPosicao=="."&&(i==tamanho-4||i==tamanho-5))){
                separa=true;
                numeros+=","
                }
            }
        if(!isNaN(vPosicao)&&vPosicao!=" "){
        numeros+=vPosicao
        }
    }
    if(separa==false){
    numeros+=",00"
    }
    return numeros
}
//Converte Partes
function convertePartes(valor){
    if(valor.lenth<1)return"";
    var partes=valor.split(",");
    var retorno=parteInicial(partes[0])+","+parteFinal(partes[1]);
    return retorno
    }
//Parte Inicial
function parteInicial(valor){
    var tamanho=valor.length;
    if(tamanho<1){
        return"0"
        }else if(tamanho<=3){
        return valor
        }else{
        var ret="";
        var count=0;
        var ponto="";
        for(var t=tamanho;t>=0;t--){
            if(count==3&&t>0){
                ponto=".";
                count=0
                }else{
                ponto=""
                }
                count+=1;
            ret+=valor.charAt(t)+ponto
            }
            var retorno="";
        for(var i=ret.length;i>=0;i--){
            retorno+=ret.charAt(i)
            }
            return retorno
        }
    }
//Parte Final
function parteFinal(valor){
    var valorAux;
    if((valor.length>2)){
        valorAux=valor.substr(2,3)*1;
        if(valorAux){
            if((valor.length==3)){
                return valor+="0"
                }else{
                return valor
                }
            }else{
        return valor.substr(0,2)
        }
    }else if((valor.length==1)){
    return valor+="0"
    }else if(valor.length==0){
    return valor+="00"
    }else{
    return valor
    }
}
//formataSaidaValor esta funçãoo testa se o numero possui 2 ou 4 digitos
//depois da virgula, e trata a formataçãoo de acordo com o resultado
function formataSaidaValor(valor){
    var numCasa;
    var valorFinal;
    var valorS=""+valor+"";
    var partes=valorS.split('.');
    if(is_array(partes)&&(partes.length>1)){
        numCasa=partes[1].length
        }else{
        numCasa=2
        }
        if((numCasa<=2)||(numCasa=="undefined")||(numCasa=="")){
        valorFinal=Math.round(valor*100)/100
        }else if(numCasa>2){
        valorFinal=Math.round(valor*10000)/10000
        }
        return valorFinal
    }
//Is Array
function is_array(input) {
    return typeof(input)=='object'&&(input instanceof Array);
}
//In Array
function in_array(needle,haystack,argStrict){
    var key='',strict=!!argStrict;
    if(strict){
        for(key in haystack){
            if(haystack[key]===needle){
                return true
                }
            }
        }else{
    for(key in haystack){
        if(haystack[key]==needle){
            return true
            }
        }
    }
    return false
}
//Alterar Nº Paginas da Paginação -numero de paginas e módulo como parametros
function sis_altera_paginacao(n,m){
    $.get(URLBASE+'framework/ajax_base.class.php?Op=AlteraPaginacao',{
        'Registros':n,
        'Modulo':m
    },function(ret){
        if(ret == 'true'){
            sis_spa(1);
            sis_filtrar();
        } else {
            alert('Não foi possível alterar o numero de resgistros na grid.');
        }
    });
}
//Str_replace
function str_replace (search, replace, subject, count) {
    // Replaces all occurrences of search in haystack with replace
    //
    // version: 1009.2513
    // discuss at: http://phpjs.org/functions/str_replace    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Gabriel Paderni
    // +   improved by: Philip Peterson
    // +   improved by: Simon Willison (http://simonwillison.net)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)    // +   bugfixed by: Anton Ongson
    // +      input by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    tweaked by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   input by: Oleg Eremeev
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Oleg Eremeev
    // %          note 1: The count parameter must be passed as a string in order    // %          note 1:  to find a global variable in which the result will be given
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    // *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
    // *     returns 2: 'hemmo, mars'    var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
    f = [].concat(search),
    r = [].concat(replace),
    s = subject,
    ra = r instanceof Array, sa = s instanceof Array;
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }
    for (i=0, sl=s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j=0, fl=f.length; j < fl; j++) {
            temp = s[i]+'';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length-s[i].length)/f[j].length;
            }
        }
    }
return sa ? s : s[0];
}