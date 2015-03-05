/**
 *
 *    Sappiens Framework
 *    Copyright (C) 2014, BRA Consultoria
 *
 *    Website do autor: www.braconsultoria.com.br/sappiens
 *    Email do autor: sappiens@braconsultoria.com.br
 *
 *    Website do projeto, equipe e documentação: www.sappiens.com.br
 *   
 *    Este programa é software livre; você pode redistribuí-lo e/ou
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *    publicada pela Free Software Foundation, versão 2.
 *
 *    Este programa é distribuído na expectativa de ser útil, mas SEM
 *    QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *    detalhes.
 * 
 *    Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *    junto com este programa; se não, escreva para a Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *    02111-1307, USA.
 *
 *    Cópias da licença disponíveis em /Sappiens/_doc/licenca
 *
 */

function sisSpa(p) {
    $("#sisPaginaAtual").val(p);
}
function replaceContentElem(e) {
    $(e).fadeToggle('slow', function () {
        $(e).html('');
    });
}
function setContentElem(e, c) {
    $(e).html(c);
    $(e).fadeIn('slow');
}

function sisSvo(q, t) {
    $("#sisQuemOrdena").val(q);
    $("#sisTipoOrdenacao").val(t);
}

function showHiddenFilters() {
    $(".showHidden").slideToggle();
    $(".showHidden").removeClass("hidden");
}

$(document).ready(function () {

    $('#sisBuscaGridA, #sisBuscaGridB').on('itemRemoved', function (event) {
        sisFiltrarPadrao('sisBuscaGeral=' + $(this).val());
    });

    $('#sisBuscaGridA, #sisBuscaGridB').on('itemAdded', function (event) {
        sisFiltrarPadrao('sisBuscaGeral=' + $(this).val());
    });

});

/* CRUD BÁSICO */

/* FUNÇÕES ESPECIAIS */
function cKupdate() {

    try {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    }
    catch (e)
    {
    }
}

function sisSerialize(container)
{
    //return new FormData( $(container)[0] );
    cKupdate();
    return $(container).serialize();
}

function sisSerializeUpload(container)
{
    cKupdate();
    return new FormData($(container)[0]);
}

/* FUNÇÔES DEFAULT */
function sisMsgFailPadrao()
{
    sisSetCrashAlert('Erro', 'Houve um erro ao enviar sua solicitação!<br>Tente novamente mais tarde.');
}

function sisContaCheck()
{
    var abv = document.formGrid;
    var conta = 0;

    if ($("#formGrid").length < 1) {
        return 0;
    }

    for (i = 0; i < abv.elements.length; i++) {
        if (abv.elements[i].name === 'sisReg' || abv.elements[i].name === 'sisReg[]') {
            if (abv.elements[i].type === "checkbox" || abv.elements[i].type === "radio") {
                if (abv.elements[i].checked === true) {
                    conta += 1;
                }
            }
        }
    }

    return conta;
}

function sisDescartarPadrao(form)
{
    var cod = $("#" + form + " #cod").val();

    if ($('#sisTab' + cod + 'Global').length > 0) {
        $('#sisTab' + cod + 'Global').remove();
    } else {
        $('#panel' + form).remove();
    }
}

/* FILTRO */
function sisFiltrarPadrao(p) {
    $.ajax({type: "get", url: "?acao=filtrar&sisOrigem=n", data: p, dataType: "json"}).done(function (ret) {
        $("#sisContainerGrid").html(ret.retorno);
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

function sisMarcarTodos()
{
    if ($("#sisContainerGrid").find(':checkbox').length < 1) {
        sisSetAlert('false', 'nenhum resultado encontrado na grid!');
    }
    else {
        $("#sisContainerGrid").find(':checkbox').prop('checked', true);
    }
}

function sisDesmarcarTodos()
{
    if ($("#sisContainerGrid").find(':checkbox').length < 1) {
        sisSetAlert('false', 'nenhum resultado encontrado na grid!');
    }
    else {
        $("#sisContainerGrid").find(':checkbox').prop('checked', false);
    }
}

/* CADASTRO */
function sisCadastrarLayoutPadrao(param) {
    if(!param) {
        param = {};
    }
    $.ajax({type: "get", url: "?acao=cadastrar", data:param, dataType: "json"}).done(function (ret) {
        $("#sisContainerManu").html(ret.retorno);
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

function sisCadastrarPadrao(nomeForm, upload) {

    var cod = $("#" + nomeForm + " #cod").val();

    if (upload === true) {
        var config = {type: "post", url: "?acao=cadastrar", dataType: "json", data: sisSerializeUpload("#" + nomeForm), processData: false, contentType: false};
    }
    else {
        var config = {type: "post", url: "?acao=cadastrar", dataType: "json", data: sisSerialize("#" + nomeForm)};
    }

    $.ajax(config).done(function (ret) {

        if (ret.sucesso === 'true') {
            sisSetAlert('true', 'Registro cadastrado com sucesso!');

            if ($('#sisTab' + cod + 'Global').length < 1) {
                $("#sisContainerManu").empty();
            }

            sisFiltrarPadrao('');
        }
        else {
            sisSetCrashAlert('Erro', ret.retorno);
        }
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

/* ALTERACAO */

function sisAlterarLayoutPadrao(param) {

    if(!param) {
        param = '';
    } else {
        var v;
        for(v in param) {
            param += '&' + v + '=' + param[v];
        }
    }
    
    if (sisContaCheck() < 1) {
        sisSetAlert('false', 'Nenhum registro selecionado.');
    } else {

        $.ajax({type: "get", url: "?acao=alterar"+param, data: sisSerialize("#formGrid"), dataType: "json"}).done(function (ret) {
            $("#sisContainerManu").html(ret.retorno);
        }).fail(function ()
        {
            sisMsgFailPadrao();
        });
    }
}

function sisAlterarPadrao(nomeForm, upload) {

    var cod = $("#" + nomeForm + " #cod").val();

    if (upload === true) {
        var config = {type: "post", url: "?acao=alterar", dataType: "json", data: sisSerializeUpload("#" + nomeForm), processData: false, contentType: false};
    }
    else {
        var config = {type: "post", url: "?acao=alterar", dataType: "json", data: sisSerialize("#" + nomeForm)};
    }

    $.ajax(config).done(function (ret) {
        if (ret.sucesso === 'true') {

            sisSetAlert('true', 'Registro alterado com sucesso!');

            if ($('#sisTab' + cod + 'Global').length < 1) {
                $("#panel" + nomeForm).remove();
            }

            sisFiltrarPadrao('');
        }
        else {
            sisSetCrashAlert('Erro', ret.retorno);
        }
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

/* REMOÇÃO */

function sisRemoverPadrao()
{
    var conta = sisContaCheck();

    if (conta === 0) {
        sisSetAlert('false', 'Nenhum registro selecionado.');
        return;
    }

    var plural = (conta === 1) ? '' : 's';
    var msg = 'Tem certeza que deseja apagar este' + plural + ' ' + conta + ' registro' + plural + '?';

    sisSetDialog(msg, sisRemovePadrao);
}

function sisRemovePadrao() {

    $.ajax({type: "get", url: "?acao=remover", data: sisSerialize("#formGrid"), dataType: "json"}).done(function (ret) {

        var se = parseInt(ret.selecionados);
        var ap = parseInt(ret.apagados);
        var ms = ret.retorno;
        var possivelMensagem = (ms !== '' && ms !== 'undefined' && ms !== undefined) ? " Motivo:\n" + ms : ms;

        if (ap > 0) {

            if (ap !== se) {

                var msgPlural = (ap === 1) ? 'apenas foi removido com sucesso' : 'foram removidos com sucesso';
                var msgRemovidos = "Entre os " + se + " registros selecionados " + ap + " " + msgPlural + ".\n\n";
                var msg = "Atenção, nem todos os registros puderam ser removidos!\n\n" + msgRemovidos + possivelMensagem;
                sisSetCrashAlert('Erro', msg);
                sisFiltrarPadrao('');
            } else {

                var plural = (ap === 1) ? '' : 's';
                var msg = 'Registro' + plural + ' removido' + plural + ' com sucesso!';
                sisSetAlert('true', msg);
                sisFiltrarPadrao('');
            }
        } else {

            var msg = "Atenção nenhum registro selecionado pode ser removido!\n\n" + possivelMensagem;
            sisSetAlert('false', msg);
        }

    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

/* VISUALIZAÇÃO */

function sisVisualizarPadrao()
{
    if (sisContaCheck() < 1) {

        sisSetAlert('false', 'Nenhum registro selecionado.');

    } else {

        $.ajax({type: "get", url: "?acao=visualizar", data: sisSerialize("#formGrid"), dataType: "json"}).done(function (ret) {
            $("#sisContainerManu").html(ret.retorno);
        }).fail(function ()
        {
            sisMsgFailPadrao();
        });
    }
}

/*UPLOAD*/
function sisUploadMultiplo(id) {
    var obj = document.getElementById(id);
    var html = "";

    if ('files' in obj) {
        if (obj.files.length === 0) {
            html = "Nenhum arquivo selecionado!";
        } else {
            for (var i = 0; i < obj.files.length; i++) {
                html += "<br><strong>" + (i + 1) + " - </strong>";
                var file = obj.files[i];
                if ('name' in file) {
                    html += file.name + " - ";
                }
                if ('size' in file) {
                    html += file.size + " bytes";
                }
            }
        }
    }
    else {
        if (obj.value === "") {
            html = "Nenhum arquivo selecionado!";
        } else {
            html += "Seu navegador não suporta este recurso!";
        }
    }

    $("#sisUploadMultiploLista" + id).html(html);
}

/*MASTER DETAIL*/

function sisAddMasterDetail(container) {

    var conf = $.parseJSON($("#sisMasterDetailConf" + container).val().replace(/'/g, '"'));

    var novoCoringa = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 10);

    var modeloHtml = converteModelo($("#sisMasterDetailModeloHtml" + container).html(), String(conf.coringa), novoCoringa);
    var modeloJs = converteModelo($("#sisMasterDetailModeloJS" + container).html(), String(conf.coringa), novoCoringa);
    $("#sisMasterDetailAppend" + container).append(modeloHtml);
    eval(modeloJs);
}

function converteModelo(modelo, coringa, novoCoringa) {

    var modeloConvertido = modelo.replace(new RegExp(coringa, "g"), novoCoringa);
    return modeloConvertido;
}

function sisRemoverMasterDetail(container, id) {

    var conf = $.parseJSON($("#sisMasterDetailConf" + container).val().replace(/'/g, '"'));

    var atual = $("#sisMasterDetail" + container + " div[id^='sisMasterDetailIten" + container + "']").length - 1; //Ignorando o campo modelo

    if (atual <= conf.addMin) {
        sisSetAlert('', 'Não foi possível remover, pois este grupo requer no mínimo ' + conf.addMin + ' itens');
    }
    else {
        $("#sisMasterDetailIten" + container + id).remove();
    }
}

// DIALOG
function sisSetDialog(msg, actionTrue)
{

    bootbox.confirm({
        message: msg,
        callback: function (result) {
            if (result == true) {
                actionTrue();
            } else {
                sisSetAlert('', 'Sua solicitação foi cancelada!');
            }
        },
        className: "bootbox-sm"
    });

}
// ALERT
function sisSetAlert(a, b, c)
{

    if (c == 'static') {
        var time = 9999 * 9999;
    } else {
        var time = 4000;
    }

    if (a == 'true' && b == undefined) {
        var b = "Salvo com sucesso!";
    } else if (a == 'false' && b == undefined) {
        var b = "Problemas na execução. Tente novamente mais tarde...";
    }

    if (a == 'false') {
        $.growl.error({title: 'Oops!', message: b, size: 'large', duration: time});
    } else if (a == 'true') {
        $.growl.notice({title: 'Ueba!', message: b, size: 'large', duration: time});
    } else if (a == 'warning') {
        $.growl.warning({title: 'Atenção!', message: b, size: 'large', duration: time});
    } else if (a == '') {
        $.growl({title: 'Humm?!', message: b, size: 'large', duration: time});
    }

}

function sisSetCrashAlert(a, b)
{
    $('#modal-titulo').html(a);
    $('#modal-descricao').html(b);
    $('#modal-msg').modal();

}

/* FILTROS */
function sisChangeFil(origem)
{
    var campos = $('#sisFormFiltro').serializeArray();
    var contN = 0;
    var contE = 0;
    var contO = 0;

    $.each(campos, function (pos, campo) {

        if ($('#' + campo.name).attr('type') !== 'hidden' && campo.value !== '') {
            var valor = $('#' + campo.name).val();
            var tipo = $('#' + campo.name).attr('name').substr(0, 1);

            if (tipo === 'n' && valor !== '') {
                contN++;
            }

            if (tipo === 'e' && valor !== '') {
                contE++;
            }

            if (tipo === 'o' && valor !== '') {
                contO++;
            }
        }
    });

    //Corrige Badges
    if (origem === 'n') {
        $('#sisBadgeN').removeClass('tachado');
        $('#sisBadgeE').addClass('tachado');
        $('#sisBadgeO').addClass('tachado');
    }
    else if (origem === 'e')
    {
        $('#sisBadgeN').addClass('tachado');
        $('#sisBadgeE').removeClass('tachado');
        $('#sisBadgeO').addClass('tachado');
    }
    else if (origem === 'o')
    {
        $('#sisBadgeN').addClass('tachado');
        $('#sisBadgeE').addClass('tachado');
        $('#sisBadgeO').removeClass('tachado');
    }

    if (contN > 0) {
        $('#sisBadgeN').html(contN).removeClass('hidden');
    }
    else {
        $('#sisBadgeN').html(contN).addClass('hidden');
    }

    if (contE > 0) {
        $('#sisBadgeE').html(contE).removeClass('hidden');
    }
    else {
        $('#sisBadgeE').html(contE).addClass('hidden');
    }

    if (contO > 0) {
        $('#sisBadgeO').html(contO).removeClass('hidden');
    }
    else {
        $('#sisBadgeO').html(contO).addClass('hidden');
    }

    sisFiltrarPadrao(parametrosFiltro(origem));
}

function sisOpFiltro(nomeCampo, tipo, origem)
{
    $("#sho" + nomeCampo).val(tipo);

    $("#sisIcFil" + nomeCampo).html('&nbsp;&nbsp;' + tipo);

    if ($("#" + nomeCampo).val()) {
        sisFiltrarPadrao(parametrosFiltro(origem));
    }
}

function parametrosFiltro(origem)
{
    var campos = $('#sisFormFiltro').serializeArray();

    var par = [];

    par.push({
        name: 'sisOrigem',
        value: origem
    });

    $.each(campos, function (pos, campo) {

        var nome = $('#' + campo.name).attr('name');
        var valor = $('#' + campo.name).val();
        var p = nome.substr(0, 1);
        var h = nome.substr(0, 4);

        if (valor !== '') {
            if (p === origem || h === 'sho' + origem || h === 'sha' + origem) {
                par.push({
                    name: campo.name,
                    value: campo.value
                });
            }
        }
    });

    return par;
}

//DEPENDENCIA - INICIO
function sisCarregaDependencia(ur, fo, co, id, me, cl, nc, fc)
{
    var par = {'m': me, 'c': cl, 'r': id, 'n': nc};

    if (fc) {
        var pE = fc(fo);
        var v;
        for (v in pE) {
            par[v] = pE[v];
        }
    }

    $.ajax({type: "get", url: ur, data: par, dataType: "json"}).done(function (ret) {

        if (ret.sucesso === 'true') {
            $("#" + fo + " #" + co + " select").html(ret.retorno);
        }
        else {
            sisSetCrashAlert('Erro', ret.retorno);
        }
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

//DEPENDENCIA - FIM

function chNxt(a, b, c, d)
{
    var aa = $(a).val();
    $.ajax({type: "get", url: "?acao=" + d + "&a=" + aa + "&b=" + c, dataType: "json", beforeSend: function () {
            $(b).html('<i class="fa fa-refresh fa-spin"></i>');
        }}).done(function (ret) {
        $(b).html(ret.retorno);
        $("#" + c).val(ret.retorno);
    }).fail(function () {
        sisMsgFailPadrao();
    });
}

function chChosen(a, b, c)
{
    var aa = $(a).val();
    $.ajax({type: "get", url: "?acao=" + c + "&a=" + aa, dataType: "json", beforeSend: function () {
            $(b).html('<i class="fa fa-refresh fa-spin"></i>');
        }}).done(function (ret) {
        $(b).html(ret.retorno);
    }).fail(function () {
        sisMsgFailPadrao();
    });
}

function sisImprimir()
{
    window.open("?acao=imprimir&sisModoImpressao=1", 'imprimir');
}

function sisSalvarPDF() {

    var ifr = $('<iframe/>', {
        id: 'iframeDownload',
        name: 'iframeDownload',
        src: '?acao=salvarPDF&sisModoImpressao=1',
        style: 'display:none',
        load: function () {

            var conteudo = $('#iframeDownload').contents().find('body').html();
            try {
                var ret = $.parseJSON(conteudo);
            } catch (fail) {
                var ret = Array();
                ret['sucesso'] = 'false';
            }

            if (ret['sucesso'] === 'false')
            {
                sisSetAlert('true', ret['retorno']);
            }
            else
            {
                alert('Houve um erro ao enviar sua solicitação!\n\nTente novamente mais tarde.\n');
            }
        }
    });

    if ($('#iframeDownload').attr('name') !== "iframeDownload") {
        $('#formGrid').append(ifr);
    } else {
        $('#iframeDownload').remove();
        $('#formGrid').append(ifr);
    }

}

function validaSenhaUser(campo, url)
{
    valor = campo.value;

    if(valor.length >= 6 && valor.length <= 30) {
        $.ajax({type: "post", url: url, dataType: "json", data:  {'s': valor}, beforeSend: function () {
            $('#iconFA').attr('class', 'fa fa-refresh form-control-feedback');
            $('#iconFA').attr('title', 'Verificando autenticidade da senha.');
        }}).done(function (ret) {

            if(ret.sucesso === 'true'){
                if(ret.retorno === 'true'){
                    $('#iconFA').attr('class', 'fa fa-check-circle form-control-feedback');
                    $('#iconFA').attr('title', 'Senha autêntica.');
                } else {
                    $('#iconFA').attr('class', 'fa fa-lock form-control-feedback');
                    $('#iconFA').attr('title', 'Senha inválida.');
                }
            } else {
                $('#iconFA').attr('class', 'fa fa-times-circle form-control-feedback');
                $('#iconFA').attr('title', 'Sessão expirada! Faça login novamente para continuar...');
            }
        }).fail(function (event) {
            $('#iconFA').attr('class', 'fa fa-check form-control-feedback');
            $('#iconFA').attr('title', 'Não pudemos verificar a autenticidade de sua senha no momento, mas o faremos ao salvar.');
        });
    }
    
    return true;
}

function webSocket(){
    
}