sisRedirAlterar();

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
}

function showHiddenFilters() {

    var caretClass = $("#caretFilter").attr('class');

    $("#caretFilter").removeAttr("class");

    if (caretClass === "fa fa-caret-down")
        $("#caretFilter").addClass('fa fa-caret-up');
    else if (caretClass === "fa fa-caret-up")
        $("#caretFilter").addClass('fa fa-caret-down');
    else
        return false;

    if ($(".showHidden").hasClass('hidden')) {
        $(".showHidden").removeClass("hidden");
    } else {
        $(".showHidden").addClass("hidden");
    }
//    $(".showHidden").slideToggle();
//    $(".showHidden").removeClass("hidden");
}

$(document).ready(function () {

    $('#sisBuscaGridA, #sisBuscaGridB').on('itemRemoved', function (event) {
        sisFiltrarPadrao('sisBuscaGeral=' + $(this).val() + '&tab=' + $('#sis_tab').val());

        if (!$(".showHidden").hasClass('hidden')) {
            $(".showHidden").addClass("hidden");
        }
    });

    $('#sisBuscaGridA, #sisBuscaGridB').on('itemAdded', function (event) {
        sisFiltrarPadrao('sisBuscaGeral=' + $(this).val() + '&tab=' + $('#sis_tab').val());

        if (!$(".showHidden").hasClass('hidden')) {
            $(".showHidden").addClass("hidden");
        }
    });

});
/* CRUD BÁSICO */

function sisRedirAlterar() {

    var qS = document.location.search;

    if (qS.search(/\?[sisRedir]{8}/) !== -1 && qS.search(/\&[id=]{3}[0-9]{1,}/) !== -1) {

        var id = qS.replace(/\?[sisRedir=]{9}\w{1,}\&[id=]{3}/, '');
        var param = new Array();

        param['sisReg[]'] = parseInt(id);
        sisAlterarLayoutPadrao(param);

    } else {

        return true;
    }

}

/* FUNÇÕES ESPECIAIS */
function cKupdate() {

    try {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    } catch (e)
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
    sisSetCrashAlert('Ops, encontramos problemas com a sua solicitação', 'Houve um erro ao enviar sua solicitação!<br>Tente novamente mais tarde.<br><br>Se o problema persistir entre em contato com os administradores do sistema!', 1);
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

function sisDescartarAbas()
{
    $("#sisContainerManu").empty();
}

/* FILTRO */
function sisFiltrarPadrao(p) {

    if (!p) {
        $('#sisBuscaGridA').tagsinput('removeAll');
        $('#sisBuscaGridB').tagsinput('removeAll');
    }

    $.ajax({type: "get", url: "?acao=filtrar", data: p, dataType: "json"}).done(function (ret) {
        if (ret.sucesso === "true") {
            $("#sisContainerGrid").html(ret.retorno);
        } else {
            sisSetCrashAlert('Erro', ret.retorno, ret.tipo_erro);
        }
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

function sisMarcarTodos()
{
    if ($("#sisContainerGrid").find(':checkbox').length < 1) {
        sisSetAlert('false', 'nenhum resultado encontrado na grid!');
    } else {
        $("#sisContainerGrid").find(':checkbox').prop('checked', true);
    }
}

function sisDesmarcarTodos()
{
    if ($("#sisContainerGrid").find(':checkbox').length < 1) {
        sisSetAlert('false', 'nenhum resultado encontrado na grid!');
    } else {
        $("#sisContainerGrid").find(':checkbox').prop('checked', false);
    }
}

/*BOTOES*/
function botoesPadrao(nomeForm, acao)
{
    var bs = $("#" + nomeForm + " #sisSalvar");
    var bd = $("#" + nomeForm + " #sisDescartar");

    var txtBs = '';
    
    if(!bs.attr('aux')){
        bs.attr('aux',bs.html());
    }
    
    if(acao == true){
        txtBs = '<i class="fa fa-spinner fa-spin"></i> aguarde processando...';
    }
    else{
        txtBs = bs.attr('aux');
    }

    bs.prop('disabled', acao).html(txtBs);
    bd.prop('disabled', acao);
}

/* CADASTRO */
function sisCadastrarLayoutPadrao(param) {
    if (!param) {
        param = {};
    }
    $.ajax({type: "get", url: "?acao=cadastrar", data: param, dataType: "json"}).done(function (ret) {

        if (ret.retorno == 'sessaoexpirada') {
            sisNovaSessao();
        } else {
            $("#sisContainerManu").html(ret.retorno);
        }
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

function sisCadastrarPadrao(nomeForm, upload) {

    botoesPadrao(nomeForm, true);

    var cod = $("#" + nomeForm + " #cod").val();

    if (upload === true) {
        var config = {type: "post", url: "?acao=cadastrar", dataType: "json", data: sisSerializeUpload("#" + nomeForm), processData: false, contentType: false};
    } else {
        var config = {type: "post", url: "?acao=cadastrar", dataType: "json", data: sisSerialize("#" + nomeForm)};
    }

    $.ajax(config).done(function (ret) {

        if (ret.sucesso === 'true') {

            sisSetAlert('true', 'Registro cadastrado com sucesso!');

            botoesPadrao(nomeForm, false);

            if ($('#sisTab' + cod + 'Global').length < 1) {
                $("#sisContainerManu").empty();
            }

            sisFiltrarPadrao('');
        } else {
            botoesPadrao(nomeForm, false);
            sisSetCrashAlert('Erro', ret.retorno, ret.tipo_erro);
        }
    }).fail(function ()
    {
        botoesPadrao(nomeForm, false);
        sisMsgFailPadrao();
    });
}

/* ALTERACAO */

function sisAlterarLayoutPadrao(param) {

    if (!param) {
        param = '';
    } else {
        var v;
        for (v in param) {
            param += '&' + v + '=' + param[v];
        }
    }

    if (sisContaCheck() < 1 && !param) {
        sisSetAlert('false', 'Nenhum registro selecionado.');
    } else {

        $.ajax({type: "get", url: "?acao=alterar" + param, data: sisSerialize("#formGrid"), dataType: "json"}).done(function (ret) {

            if (ret.retorno == 'sessaoexpirada') {
                sisNovaSessao();
            } else {
                $("#sisContainerManu").html(ret.retorno);
            }
        }).fail(function ()
        {
            sisMsgFailPadrao();
        });
    }
}

function sisAlterarPadrao(nomeForm, upload) {

    botoesPadrao(nomeForm, true);
    var cod = $("#" + nomeForm + " #cod").val();

    if (upload === true) {
        var config = {type: "post", url: "?acao=alterar", dataType: "json", data: sisSerializeUpload("#" + nomeForm), processData: false, contentType: false};
    } else {
        var config = {type: "post", url: "?acao=alterar", dataType: "json", data: sisSerialize("#" + nomeForm)};
    }

    $.ajax(config).done(function (ret) {
        if (ret.sucesso === 'true') {

            sisSetAlert('true', 'Registro alterado com sucesso!');

            botoesPadrao(nomeForm, false);

            if ($('#sisTab' + cod + 'Global').length < 1) {
                $("#panel" + nomeForm).remove();
            }

            sisFiltrarPadrao('');
        } else {
            botoesPadrao(nomeForm, false);
            sisSetCrashAlert('Erro', ret.retorno, ret.tipo_erro);
        }
    }).fail(function ()
    {
        botoesPadrao(nomeForm, false);
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

        if (ret.retorno == 'sessaoexpirada') {
            sisNovaSessao();
        } else {

            var se = parseInt(ret.selecionados);
            var ap = parseInt(ret.apagados);
            var ms = ret.retorno;
            var possivelMensagem = (ms !== '' && ms !== 'undefined' && ms !== undefined) ? " Motivo:\n" + ms : ms;

            if (ap > 0) {

                if (ap !== se) {

                    var msgPlural = (ap === 1) ? 'apenas foi removido com sucesso' : 'foram removidos com sucesso';
                    var msgRemovidos = "Entre os " + se + " registros selecionados " + ap + " " + msgPlural + ".\n\n";
                    var msg = "Atenção, nem todos os registros puderam ser removidos!\n\n" + msgRemovidos + possivelMensagem;
                    sisSetCrashAlert('Erro', msg, ret.tipo_erro);
                    sisFiltrarPadrao('');
                } else {

                    var plural = (ap === 1) ? '' : 's';
                    var msg = 'Registro' + plural + ' removido' + plural + ' com sucesso!';
                    sisSetAlert('true', msg, ret.tipo_erro);
                    sisFiltrarPadrao('');
                }
            } else {

                var msg = "Atenção nenhum registro selecionado pode ser removido!\n\n" + possivelMensagem;
                sisSetCrashAlert('Erro', msg, ret.tipo_erro);
            }
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

            if (ret.retorno == 'sessaoexpirada') {
                sisNovaSessao();
            } else {

                $("#sisContainerManu").html(ret.retorno);
            }
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
    } else {
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

    var atual = $("#sisMasterDetail" + container + " div[id^='sisMasterDetailIten" + container + "']").length - 1; //Ignorando o campo modelo

    if (conf.addMax !== 0 && atual >= conf.addMax) {
        sisSetAlert('', 'Não foi possível adicionar, pois este grupo permite no máximo ' + conf.addMax + ' itens');
    } else {
        var novoCoringa = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 10);

        var modeloHtml = converteModelo($("#sisMasterDetailModeloHtml" + container).html(), String(conf.coringa), novoCoringa);
        var modeloJs = converteModelo($("#sisMasterDetailModeloJS" + container).html(), String(conf.coringa), novoCoringa);
        $("#sisMasterDetailAppend" + container).append(modeloHtml);

        var novoModelo = modeloJs.replace(/&amp;/g, '&');
        eval(novoModelo);
    }
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
    } else {
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
        $.growl.notice({title: 'Sucesso!', message: b, size: 'large', duration: time});
    } else if (a == 'warning') {
        $.growl.warning({title: 'Atenção!', message: b, size: 'large', duration: time});
    } else if (a == '') {
        $.growl({title: 'Humm?!', message: b, size: 'large', duration: time});
    }

}

function sisSetCrashAlert(a, b, cod)
{
    $('#modal-msg').removeClass('modal-danger');
    $('#modal-msg').removeClass('modal-warning');

    if (b == 'sessaoexpirada') {
        sisNovaSessao();
    } else {
        if (cod == 2) {

            if (a == 'Erro') {
                a = 'Validação';
            }

            $('#modal-msg').addClass('modal-warning');
        } else {
            $('#modal-msg').addClass('modal-danger');
        }

        $('#modal-titulo').html(a);
        $('#modal-descricao').html(b);
        $('#modal-msg').modal();
    }

}

function sisNovaSessao() {
    $('#sis-login-alert').hide();
    $('#sis-modal-login').modal('show');
}

function sis_login_interno(urlBase, oh) {

    $('#sis-login-alert').html('');

    var user = $('#sis-login-nome').val();
    var pass = $('#sis-login-senha').val();

    if (user == '') {
        alert('Informe o usuário corretamente!');
        return;
    }

    if (pass == '') {
        alert('Informe a senha corretamente!');
        return;
    }

    $.ajax({type: "post", url: urlBase + "Accounts/Login/?acao=loginSistema", data: {'u': user, 'p': pass, 'oh': oh}, dataType: "json"}).done(function (ret) {

        if (ret.sucesso === 'true') {
            $('#sis-modal-login').modal('hide');
            $('#sis-login-nome').val('');
            $('#sis-login-senha').val('');

            sisSetAlert('true', 'Login efetuado com sucesso, repita a sua última operação!');
        } else {
            $('#sis-login-alert').html('Oops! Dados incorretos...').show();
        }
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

/* FILTROS */
function sisChangeFil(origem, tab)
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
    } else if (origem === 'e')
    {
        $('#sisBadgeN').addClass('tachado');
        $('#sisBadgeE').removeClass('tachado');
        $('#sisBadgeO').addClass('tachado');
    } else if (origem === 'o')
    {
        $('#sisBadgeN').addClass('tachado');
        $('#sisBadgeE').addClass('tachado');
        $('#sisBadgeO').removeClass('tachado');
    }

    if (contN > 0) {
        $('#sisBadgeN').html(contN).removeClass('hidden');
    } else {
        $('#sisBadgeN').html(contN).addClass('hidden');
    }

    if (contE > 0) {
        $('#sisBadgeE').html(contE).removeClass('hidden');
    } else {
        $('#sisBadgeE').html(contE).addClass('hidden');
    }

    if (contO > 0) {
        $('#sisBadgeO').html(contO).removeClass('hidden');
    } else {
        $('#sisBadgeO').html(contO).addClass('hidden');
    }

    sisFiltrarPadrao(parametrosFiltro(origem, tab));

    $('#sisBuscaGridA').tagsinput('removeAll');
    $('#sisBuscaGridB').tagsinput('removeAll');
}

function sisOpFiltro(nomeCampo, tipo, origem)
{
    $("#sho" + nomeCampo).val(tipo);

    $("#sisIcFil" + nomeCampo).html('&nbsp;&nbsp;' + tipo);

    if ($("#" + nomeCampo).val()) {
        sisFiltrarPadrao(parametrosFiltro(origem));
    }
}

function parametrosFiltro(origem, tab)
{
    var campos = $('#sisFormFiltro').serializeArray();

    var par = [];

    par.push({
        name: 'sisOrigem',
        value: origem
    });

    $.each(campos, function (pos, campo) {

        if (campo.value !== '' && $('#' + campo.name).attr('type') != 'hidden') {
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

                    par.push({
                        name: 'sho' + campo.name,
                        value: $('#sho' + campo.name).val()
                    });

                    par.push({
                        name: 'sha' + campo.name,
                        value: $('#sha' + campo.name).val()
                    });
                }
            }
        }
    });

    if (tab) {
        par.push({
            name: 'tab',
            value: tab
        });
    }

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
            $("#" + fo + " #" + co + "").html(ret.retorno);
        } else {
            sisSetCrashAlert('Erro', ret.retorno, ret.tipo_erro);
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
    var queryString = ($('#sisQueryString').val() ? '&sisOrigem=n&' + $('#sisQueryString').val() : '');
    window.open("?acao=imprimir&sisModoImpressao=1" + queryString, 'imprimir');
}

function sisSalvarCSV()
{
    var queryString = ($('#sisQueryString').val() ? '&sisOrigem=n&' + $('#sisQueryString').val() : '');
    window.open("?acao=salvarCSV&sisModoImpressao=1" + queryString, 'imprimircsv');
}

function sisSetOrientacaoPDF()
{
    $('#sisModalPDF').modal();
}

function sisSalvarPDF(orientacao) {

    if (typeof orientacao === 'undefined') {
        sisSetOrientacaoPDF();
    } else {

        $('#closePDF').click();

        var queryString = ($('#sisQueryString').val() ? '&sisOrigem=n&' + $('#sisQueryString').val() : '');

        var ifr = $('<iframe/>', {
            id: 'iframeDownload',
            name: 'iframeDownload',
            src: '?acao=salvarPDF&sisModoImpressao=1&orientacao=' + orientacao + queryString,
            style: 'display:none',
            load: function () {

                var conteudo = $('#iframeDownload').contents().find('body').html();

                try {
                    if (conteudo.length > 0) {
                        var ret = Array();
                        ret['sucesso'] = 'false';
                    } else {
                        var ret = Array();
                        ret['sucesso'] = 'true';
                        ret['retorno'] = 'PDF gerado com sucesso!'
                    }
                } catch (fail) {
                    var ret = Array();
                    ret['sucesso'] = 'false';
                }

                if (ret['sucesso'] === 'false')
                {
                    sisSetAlert('false', ret['retorno']);
                } else if (ret['sucesso'] === 'true')
                {
                    sisSetAlert('true', ret['retorno']);
                } else
                {
                    sisSetAlert('false', ret['retorno']);
                }
            }
        });

        if ($('body').attr('name') !== "iframeDownload") {
            $('body').append(ifr);
        } else {
            $('#iframeDownload').remove();
            $('body').append(ifr);
        }
    }
}
function getPDF(url) {

    var ifr = $('<iframe/>', {
        id: 'iframeDownload',
        name: 'iframeDownload',
        src: url,
        style: 'display:none',
        load: function () {

            var conteudo = $('#iframeDownload').contents().find('body').html();

            try {
                if (conteudo.length > 0) {
                    var ret = Array();
                    ret['sucesso'] = 'false';
                } else {
                    var ret = Array();
                    ret['sucesso'] = 'true';
                    ret['retorno'] = 'PDF gerado com sucesso!'
                }
            } catch (fail) {
                var ret = Array();
                ret['sucesso'] = 'false';
            }

            if (ret['sucesso'] === 'false')
            {
                sisSetAlert('false', ret['retorno']);
            } else if (ret['sucesso'] === 'true')
            {
                sisSetAlert('true', ret['retorno']);
            } else
            {
                sisSetAlert('false', ret['retorno']);
            }
        }
    });

    if ($('#iframeDownload').attr('name') !== "iframeDownload") {
        $('body').append(ifr);
    } else {
        $('#iframeDownload').remove();
        $('body').append(ifr);
    }

}

function validaSenhaUser(campo, url)
{
    valor = campo.value;

    if (valor.length >= 6 && valor.length <= 30) {
        $.ajax({type: "post", url: url, dataType: "json", data: {'s': valor}, beforeSend: function () {
                $('#iconFA').attr('class', 'fa fa-refresh form-control-feedback');
                $('#iconFA').attr('title', 'Verificando autenticidade da senha.');
            }}).done(function (ret) {

            if (ret.sucesso === 'true') {
                if (ret.retorno === 'true') {
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

function array_diff(array1, array2) {

    var diff = [];
    diff = $.grep(array2, function (el) {
        return $.inArray(el, array1) === -1;
    });

    return diff;
}

/* CONFIGURAÇÃO DE COLUNAS DA GRID */
function sisSalvarColunasDinamicas(urlBase, moduloCod)
{
    var config = {type: "get", url: urlBase + 'Ext/Remoto/colunas_grid/?moduloCod=' + moduloCod, dataType: "json",
        data: $("#formGrid").serialize()};

    $.ajax(config).done(function (ret) {

        if (ret.sucesso === 'true') {

            sisSetAlert('true', 'Configuração de colunas da grid aletarda com sucesso!');

            sisFiltrarPadrao('');
        } else {
            sisSetCrashAlert('Erro', ret.retorno, ret.tipo_erro);
        }
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

/* CONFIGURAÇÃO DE COLUNAS DA GRID */

/* ALTERAR O NÙMERO DE LINHAS */
function sisAlterarLinhas(urlBase, moduloCod)
{
    var linhas = $("#sisAlteraLinhas").val();

    var config = {type: "get", url: urlBase + 'Ext/Remoto/linhas_grid/', dataType: "json",
        data: {'qLinhas': linhas, 'moduloCod': moduloCod}};

    $.ajax(config).done(function (ret) {

        if (ret.sucesso === 'true') {

            sisSetAlert('true', 'Número de linhas da grid aletardo com sucesso!');

            sisFiltrarPadrao('');
        } else {
            sisSetCrashAlert('Erro', ret.retorno, ret.tipo_erro);
        }
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}
/* ALTERAR O NÙMERO DE LINHAS */

/* CONFIGURAÇÃO PARA SALVAMENTO DO FILTRO DA GRID */
function sisSalvarFiltro(urlBase, moduloCod)
{
    var nome = $("#sisSalvarFiltroNome").val();
    var titulo = $("#sisSalvarFiltroTitulo").val();
    var colunas = $("#sisGridListaColunas").val();
    var queryString = $("#sisQueryString").val();

    if (nome === '') {
        alert('O campo "nome do filtro" deve ser informado corretamente!"');
        return;
    }

    var config = {type: "get", url: urlBase + 'Ext/Remoto/filtro/?moduloCod=' + moduloCod, dataType: "json",
        data: {'nome': nome, 'titulo': titulo, 'colunas': colunas, 'qs': queryString}};

    $.ajax(config).done(function (ret) {

        if (ret.sucesso === 'true') {

            sisSetAlert('true', 'Filtro salvo com sucesso!');

            $("#sisFiltroSalvo").attr('carregado', 'N');
            sisCarregaFiltrosSalvos(urlBase, moduloCod);

            $("#sisSalvarFiltroNome").val('');
            $("#sisSalvarFiltroTitulo").val('');
        } else {
            sisSetCrashAlert('Erro', ret.retorno, ret.tipo_erro);
        }
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });

    $('#sisModalSalvarFiltro').modal('hide');
}

/* CONFIGURAÇÃO PARA SALVAMENTO DO FILTRO DA GRID */


/* FILTROS SALVOS */
function sisCarregaFiltrosSalvos(urlBase, moduloCod) {

    var carregado = $("#sisFiltroSalvo").attr('carregado');

    if (carregado === 'N') {

        var config = {type: "get", url: urlBase + 'Ext/Remoto/filtro/?acao=carregar&moduloCod=' + moduloCod, dataType: "json"};

        $.ajax(config).done(function (ret) {

            if (ret.sucesso === 'true') {

                $("#bs-tabdrop-tab3").html(ret.retorno);
                $("#sisFiltroSalvo").attr('carregado', 'S');
            } else {
                sisSetCrashAlert('Erro', ret.retorno, ret.tipo_erro);
            }
        }).fail(function ()
        {
            sisMsgFailPadrao();
        });

    }
}

/* FILTROS SALVOS */

/* REMOVER FILTROS SALVOS */

function sisRemoverFiltroSalvo(usuarioFiltroCod, urlBase, moduloCod) {

    if (!confirm('Tem certeza que desja remover este filtro?')) {
        return;
    }

    var config = {type: "get", url: urlBase + 'Ext/Remoto/filtro/?acao=remover', dataType: "json", data: {'cod': usuarioFiltroCod}};

    $.ajax(config).done(function (ret) {

        if (ret.sucesso === 'true') {

            sisSetAlert('true', 'Filtro removido com sucesso!');
            $("#sisFiltroSalvo").attr('carregado', 'N');
            sisCarregaFiltrosSalvos(urlBase, moduloCod);
        } else {
            sisSetCrashAlert('Erro', ret.retorno, ret.tipo_erro);
        }
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

/* REMOVER FILTROS SALVOS */

function sisPopUp(url, nome)
{
    window.open(url, nome, "toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=no,width=900,height=450");
}

/* REMOVER FILTROS SALVOS */

/* FINAL DO ARQUIVO */