function sisSpa(p) {
    $("#sisPaginaAtual").val(p);
}
function replaceContentElem(e) {
    $(e).fadeToggle('slow', function () {
        $(e).html('');
    })
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

function sisAlterarPadrao()
{
    if (sisContaCheck() < 1) {

        alert('Nenhum registro selecionado');
    } else {

        sisAlterarLayout();
    }
}

function sisVisualizarPadrao()
{
    if (sisContaCheck() < 1) {

        alert('Nenhum registro selecionado');
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
        alert('Nenhum registro foi selecionado');
        return;
    }

    var plural = (conta === 1) ? '' : 's';

    if (confirm('Tem certeza que deseja apagar este' + plural + ' ' + conta + ' registro' + plural + '?')) {
        sisApagar();
    }
}

function sisRetornoRemover(retJson)
{
    var se = parseInt(retJson.selecionados);
    var ap = parseInt(retJson.apagados);
    var ms = retJson.retorno;
    var possivelMensagem = (ms !== '' && ms !== 'undefined' && ms !== undefined) ? "Motivo:\n" + ms : ms;

    if (ap > 0) {

        if (ap !== se) {

            var msgPlural = (ap === 1) ? 'apenas foi removido com sucesso' : 'foram removidos com sucesso';
            var msgRemovidos = "Entre os " + se + " registros selecionados " + ap + " " + msgPlural + ".\n\n";
            alert("Atenção, nem todos os registros puderam ser removidos!\n\n" + msgRemovidos + possivelMensagem);
            //sis_busca_filtro()
        } else {

            var plural = (ap === 1) ? '' : 's';
            alert('Registro' + plural + ' removido' + plural + ' com sucesso!');
            //sis_busca_filtro()
        }
    } else {

        alert("Atenção nenhum registro selecionado pode ser removido!\n\n" + possivelMensagem);
    }
}
