/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function sisSpa(p){$("#sisPaginaAtual").val(p);}
function sisSvo(q,t){$("#sisQuemOrdena").val(q);$("#sisTipoOrdenacao").val(t);}
function showHiddenFilters(){$(".showHidden").slideToggle();$(".showHidden").removeClass("hidden");}
function replaceContentElem(e){$(e).fadeToggle('slow', function(){$(e).html('');})}
function setContentElem(e,c){$(e).html(c);$(e).fadeIn('slow');}

