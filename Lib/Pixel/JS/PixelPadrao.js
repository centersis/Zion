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
    if(sisContaCheck() < 1){
        alert('Nenhum registro selecionado');
    }else{
        sisAlterarLayout();
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
}