/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function sisSpa(p){$("#sisPaginaAtual").val(p);}
function sisSvo(q,t){$("#sisQuemOrdena").val(q);$("#sisTipoOrdenacao").val(t);}
function showFilters(){$("#box-filters").slideToggle();$("#box-filters").removeClass("hidden");}
function hiddenFilters(){$("#box-filters").addClass("hidden");}
