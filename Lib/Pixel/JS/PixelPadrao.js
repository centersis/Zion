function sisSpa(p) {
    $("#sisPaginaAtual").val(p);
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

