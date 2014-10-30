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

/* FUNÇÔES DEFAULT */
function sisMsgFailPadrao()
{
    sisSetCrashAlert('Erro', 'Houve um erro ao enviar sua solicitação!<br>Tente novamente mais tarde.');
}

function sisContaCheck()
{
    var abv = document.formGrid;
    var conta = 0;

    if (!$("formGrid")) {
        return 0;
    }

    for (i = 0; i < abv.elements.length; i++) {
        if (abv.elements[i].type === "checkbox") {
            if (abv.elements[i].checked === true) {
                conta += 1;
            }
        }
    }

    return conta;
}

function sisDescartarPadrao(form)
{
    $('#panel'+form).remove();
}

/* FILTRO */
function sisFiltrarPadrao(p) {
    $.ajax({type: "get", url: "?acao=filtrar", data: p, dataType: "json"}).done(function (ret) {
        $("#sisContainerGrid").html(ret.retorno);
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

function sisMarcarTodos()
{
    $("#sisContainerGrid").find(':checkbox').prop('checked',true);
}

function sisDesmarcarTodos()
{
    $("#sisContainerGrid").find(':checkbox').prop('checked',false);
}

/* CADASTRO */
function sisCadastrarLayoutPadrao() {
    $.ajax({type: "get", url: "?acao=cadastrar", dataType: "json"}).done(function (ret) {
        $("#sisContainerManu").html(ret.retorno);
    }).fail(function ()
    {
        sisMsgFailPadrao();
    });
}

function sisCadastrarPadrao(nomeForm) {
    $.ajax({type: "post", url: "?acao=cadastrar", data: $("#" + nomeForm).serialize(), dataType: "json"}).done(function (ret) {

        if (ret.sucesso === 'true') {
            sisSetAlert('true', 'Registro cadastrado com sucesso!');
            $("#sisContainerManu").empty();
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

function sisAlterarLayoutPadrao() {

    if (sisContaCheck() < 1) {
        sisSetAlert('false', 'Nenhum registro selecionado.');
    } else {

        $.ajax({type: "get", url: "?acao=alterar", data: $("#formGrid").serialize(), dataType: "json"}).done(function (ret) {
            $("#sisContainerManu").html(ret.retorno);
        }).fail(function ()
        {
            sisMsgFailPadrao();
        });
    }
}

function sisAlterarPadrao(nomeForm) {
    $.ajax({type: "post", url: "?acao=alterar", data: $("#" + nomeForm).serialize(), dataType: "json"}).done(function (ret) {
        if (ret.sucesso === 'true') {
            sisSetAlert('true', 'Registro alterado com sucesso!');
            $("#panel"+nomeForm).remove();
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

    $.ajax({type: "post", url: "?acao=remover", data: $("#formGrid").serialize(), dataType: "json"}).done(function (ret) {

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

        $.ajax({type: "get", url: "?acao=visualizar", data: $("#formGrid").serialize(), dataType: "json"}).done(function (ret) {
            $("#sisContainerManu").html(ret.retorno);
        }).fail(function ()
        {
            sisMsgFailPadrao();
        });
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
