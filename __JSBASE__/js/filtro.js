jQuery.fn.sisFiltro = function(tipoCampo)
    {	
        var conteiner = $(this).attr("id");
        var clique = true;
        var t;//time out
	
        //verifica se tipo é vário
        if($("#hiddent_"+conteiner).val() == "") $("#hiddent_"+conteiner).val(tipoCampo);
	
        $("#"+conteiner+" #fil_ini").click(
            function () {
                if(clique == true)
                {
                    $("#"+conteiner+" #tudo").show();
                }
            }
            );
	
        $("#FormFiltro #"+conteiner).hover(
            function () {
                clearTimeout(t);
            },function(){
                t = setTimeout(function(){
                    $("#FormFiltro #"+conteiner+" #tudo").hide();
                },500);
            });

        $("#FormFiltro #"+conteiner+" #tudo li").click(
            function(){
                clique = false;
                $("#FormFiltro #"+conteiner+" #tudo").hide();
                sisMontaFiltro(conteiner, this);
                setTimeout(function(){
                    clique = true
                },0.1);
            });

        $("#FormFiltro #"+conteiner+" #tudo li").hover(
            function(){
                $(this).addClass("fundo_fil");
            },
            function(){
                $(this).removeClass("fundo_fil");
            }
            );

        function getOpcoesFiltro(tipoCampo)
        {
            if(tipoCampo == "Float" || tipoCampo == "Inteiro" || tipoCampo == "Data")
                return '<ul id="fil_ini"><li>=</li></ul><ul id="tudo" style="display:none"><li>=</li><li><></li><li>></li><li><</li><li>>=</li><li><=</li></ul>';
		
            else if(tipoCampo == "Texto" || tipoCampo == "Suggest")
                return '<ul id="fil_ini"><li>=</li></ul><ul id="tudo" style="display:none"><li>=</li><li><></li><li>*</li><li>A*</li><li>*A</li></ul>';
		
            else if(tipoCampo == "CPF" || tipoCampo == "CNPJ" || tipoCampo == "CEP")
                return '<ul id="fil_ini"><li>=</li></ul><ul id="tudo" style="display:none"><li>=</li><li><></li></ul>';
		
            else if(tipoCampo == "Select")
                return '<ul id="fil_ini"><li>=</li></ul><ul id="tudo" style="display:none"><li>=</li><li><></li></ul>';	
		
            else
                return '<ul id="fil_ini"><li>=</li></ul><ul id="tudo" style="display:none"><li>=</li><li><></li></ul>';
        }
	
        function sisMontaFiltro(conteiner, obj)
        {
            //Pega o Valor Selecionado
            var selecao = $.trim($(obj).text());
		
            //Adiciona no hidden
            $("#FormFiltro #hidden_"+conteiner).val(selecao);
				
            //Atualiza Lista
            $("#FormFiltro #"+conteiner+" #fil_ini li").text(selecao); 
		
            //Interpreta Selecionado E - OU
            if(selecao == "E" || selecao == "OU")
            {			
                //Campo
                var campo = conteiner.substring(11,conteiner.length);
			
                var type = $("#FormFiltro #"+campo).attr("type");
               
                var identifica = $("#hiddeni_"+conteiner).val();			                
               
                //Clona os Campos
                var cpA  = $("#FormFiltro #"+campo).clone();
                
                if(type != "select-one")
                var cpB  = $("#FormFiltro #"+campo).clone();
		
                //Desabilita Campos
                $("#FormFiltro #"+conteiner).css("display",'none');
                $("#FormFiltro #"+campo).attr('disabled','disabled');
			
                //Muda Name e Atrubutos
                if(type == "select-one")
                {
                    cpA.attr('id',campo+'A').attr('name',campo+'A[]');
                    cpA.attr('id',campo+'A').attr("multiple","multiple");
                    cpA.attr('id',campo+'A').attr("size","5");                    
                }
                else
                {
                    cpA.attr('id',campo+'A').attr('name',campo+'A');
                }
                
                
                if(type != "select-one")
                cpB.attr('id',campo+'B').attr('name',campo+'B').val("");
			
                //Cria Div se não existir
                var idCloneCampos = $("#FormFiltro #cloneCampos").attr('id');
			
                //Se já existe não precisa criar denovo né :D
                if(idCloneCampos != "cloneCampos") $("<div id='cloneCampos'></div>").prependTo("#FormFiltro");
			
                //Inicia Conteudos
                if(type != "select-one")
                var conteudoA = '<div id="sis_filtro_'+campo+'A" class="s_filtro" style="z-index:'+50+'; width:25px; float:left;">'+getOpcoesFiltro(tipoCampo)+'</div>';
                
                if(type != "select-one")
                var conteudoB = '<div id="sis_filtro_'+campo+'B" class="s_filtro" style="z-index:'+51+'; width:25px; float:left;">'+getOpcoesFiltro(tipoCampo)+'</div>';
                
                var selecaoA = (type == "select-one") ? ' - <span style="color:#FF0000;"> '+selecao+'</span> ' : '';
                
                //Adiciona os Campos a Div
                var $divClone = $("<div class='cloneCPs'></div>");
                $divClone.append("<h1 id='nomeCampos' style='float:left;'> "+identifica+selecaoA+": </h1>");
                $divClone.append(conteudoA);
                
                var largura = (type == "select-one") ? '90%' : '200px';
                
                $divClone.append('<div id="CPA" style="float:left; width:'+largura+'; overflow:hidden;"></div>');
                $divClone.find("#CPA").append(cpA);
                
                if(type != "select-one")
                {
                    $divClone.append("<h1 id='CPspan' style='float:left; color:#FF0000;'> "+selecao+" </h1>");
                    $divClone.append("<h1 id='nomeCampos' style='float:left;'> "+identifica+": </h1>");
                    $divClone.append(conteudoB);
                    $divClone.append('<div id="CPB" style="float:left"></div>');
                    $divClone.find("#CPB").append(cpB);
                }
                
                $divClone.append("<h1 id='CPDel' style='float:left;'><a href='#' onClick='removeSisFiltro(this,\""+campo+"\")'><img src='"+URLBASE+"figuras/del_2.gif' border='0' /> Remover este filtro</a></h1> <br clear=\"all\" />");
			
                //Hidden que guarda o valor selecionado
                $divClone.append('<input name="hidden_sis_filtro_'+campo+'A" id="hidden_sis_filtro_'+campo+'A" type="hidden" value="="/>');
                
                if(type != "select-one")
                $divClone.append('<input name="hidden_sis_filtro_'+campo+'B" id="hidden_sis_filtro_'+campo+'B" type="hidden" value="="/>');							 
			
                //Adiciona ao Conteiner os Objetos Clonados e Processados
                $("#FormFiltro #cloneCampos").append($divClone);
			
                if(tipoCampo == "Float")
                {
                    $('#FormFiltro #sis_filtro_'+campo+'A').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
                    $('#FormFiltro #sis_filtro_'+campo+'B').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
				
                    $('#FormFiltro #'+campo+'A').livequery(function(){
                        $(this).floatValue();
                    });
                    $('#FormFiltro #'+campo+'B').livequery(function(){
                        $(this).floatValue();
                    });
                }
                else if(tipoCampo == "Inteiro")
                {
                    $('#FormFiltro #sis_filtro_'+campo+'A').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
                    $('#FormFiltro #sis_filtro_'+campo+'B').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
				
                    $('#FormFiltro #'+campo+'A').livequery(function(){
                        $(this).validation({
                            type: "int"
                        });
                    });				
                    $('#FormFiltro #'+campo+'B').livequery(function(){
                        $(this).validation({
                            type: "int"
                        });
                    });				
                }
                else if(tipoCampo == "Data")
                {
                    $('#FormFiltro #sis_filtro_'+campo+'A').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
                    $('#FormFiltro #sis_filtro_'+campo+'B').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
				
                    $('#FormFiltro #'+campo+'A').livequery(function(){
                        $(this).calendar().mask('99/99/9999');
                    });				
                    $('#FormFiltro #'+campo+'B').livequery(function(){
                        $(this).calendar().mask('99/99/9999');
                    });				
                }
                else if(tipoCampo == "Texto")
                {
                    $('#FormFiltro #FormFiltro #sis_filtro_'+campo+'A').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
                    $('#FormFiltro #sis_filtro_'+campo+'B').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
                }
                else if(tipoCampo == "CPF")
                {
                    $('#FormFiltro #sis_filtro_'+campo+'A').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
                    $('#FormFiltro #sis_filtro_'+campo+'B').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
				
                    $('#FormFiltro #'+campo+'A').livequery(function(){
                        $(this).mask('999.999.999-99');
                    });	
                    $('#FormFiltro #'+campo+'B').livequery(function(){
                        $(this).mask('999.999.999-99');
                    });	
                }
                else if(tipoCampo == "CNPJ")
                {
                    $('#FormFiltro #sis_filtro_'+campo+'A').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
                    $('#FormFiltro #'+campo+'A').livequery(function(){
                        $(this).mask('99.999.999/9999-99');
                    });
                }
                else if(tipoCampo == "CEP")
                {
                    $('#FormFiltro #sis_filtro_'+campo+'A').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
                    $('#FormFiltro #'+campo+'A').livequery(function(){
                        $(this).mask('99999-999');
                    });
                }
                else
                {
                    $('#FormFiltro #sis_filtro_'+campo+'A').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
                    $('#FormFiltro #sis_filtro_'+campo+'B').livequery(function(){
                        $(this).sisFiltro(tipoCampo);
                    });	
                }
            }
        } 
    }

//Remove 
function removeSisFiltro(obj, nomeCampo)
{
    //UNBINDAR CAMPOS CONTAMINADOS COM MASCARAS
    $(obj).parent().parent().find(':input').each(function()
    { 
        $("#FormFiltro #"+$(this).attr('id')).expire();
    });
	
    //Deleta
    $(obj).parent().parent().remove();
	
    //Recupera conteudo
    var conteudoCloneCampos = $("#FormFiltro #cloneCampos").html();
	
    //Desabilita Campos
    $("#FormFiltro #sis_filtro_"+nomeCampo).show();
    $("#FormFiltro #sis_filtro_"+nomeCampo+" #fil_ini li").text("=");
    $("#FormFiltro #hidden_sis_filtro_"+nomeCampo).val("=");
    $("#FormFiltro #"+nomeCampo).attr('disabled',false);
	
    if(conteudoCloneCampos == "")
    {
        $("#FormFiltro #cloneCampos").remove();		
    }
}
