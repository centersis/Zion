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
        sisFiltrar('sisBuscaGeral=' + $(this).val());
    });

    $('#sisBuscaGridA, #sisBuscaGridB').on('itemAdded', function (event) {
        sisFiltrar('sisBuscaGeral=' + $(this).val());
    });
});

/* CRUD BÁSICO */
function sisFiltrarPadrao(p) {
    $.ajax({type: "get", url: "?acao=filtrar", data: p, dataType: "json"}).done(function (ret) {
        $("#sisContainerGrid").html(ret.retorno);
    });
}

function sisCadastrarLayoutPadrao() {
    $.ajax({type: "get", url: "?acao=cadastrar", dataType: "json"}).done(function (ret) {
        $("#sisContainerManu").html(ret.retorno);
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
            sisSetAlert('false', ret.retorno);
        }
    });
}

function sisAlterarPadrao()
{
    if (sisContaCheck() < 1) {

        sisSetAlert('false', 'Nenhum registro selecionado.');

    } else {

        sisAlterarLayout();
    }
}

function sisVisualizarPadrao()
{
    if (sisContaCheck() < 1) {

        sisSetAlert('false', 'Nenhum registro selecionado.');
    } else {

        sisVisualizar();
    }
}

function sisContaCheck()
{
    var abv = document.formGrid;

    if (!$("formGrid")) {
        return 0;
    }

    var conta = 0;

    for (i = 0; i < abv.elements.length; i++) {

        if (abv.elements[i].type === "checkbox") {

            if (abv.elements[i].checked === true) {

                conta += 1;
            }
        }
    }

    return conta;
}

function sisRemoverPadrao()
{
    var conta = sisContaCheck();

    if (conta === 0) {
        sisSetAlert('false', 'Nenhum registro selecionado.');
        return;
    }

    var plural = (conta === 1) ? '' : 's';
    var msg = 'Tem certeza que deseja apagar este' + plural + ' ' + conta + ' registro' + plural + '?';

    sisSetDialog(msg, sisApagar);
}

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

function sisRetornoRemover(retJson)
{
    var se = parseInt(retJson.selecionados);
    var ap = parseInt(retJson.apagados);
    var ms = retJson.retorno;
    var possivelMensagem = (ms !== '' && ms !== 'undefined' && ms !== undefined) ? " Motivo:\n" + ms : ms;

    if (ap > 0) {

        if (ap !== se) {

            var msgPlural = (ap === 1) ? 'apenas foi removido com sucesso' : 'foram removidos com sucesso';
            var msgRemovidos = "Entre os " + se + " registros selecionados " + ap + " " + msgPlural + ".\n\n";
            //alert("Atenção, nem todos os registros puderam ser removidos!\n\n" + msgRemovidos + possivelMensagem);
            var msg = "Atenção, nem todos os registros puderam ser removidos!\n\n" + msgRemovidos + possivelMensagem;
            sisSetAlert('false', msg);
            //sis_busca_filtro()
        } else {

            var plural = (ap === 1) ? '' : 's';
            //alert('Registro' + plural + ' removido' + plural + ' com sucesso!');
            var msg = 'Registro' + plural + ' removido' + plural + ' com sucesso!';
            sisSetAlert('true', msg);
            //sis_busca_filtro()
        }
    } else {

        var msg = "Atenção nenhum registro selecionado pode ser removido!\n\n" + possivelMensagem;
        sisSetAlert('false', msg);
    }
}

function sisSetAlert(a, b, c) {

    if (c == 'static') {
        var time = 9999 * 9999;
    } else {
        var time = 1500;
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
