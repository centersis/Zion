function abreModal(div) 
{
	$('iframe').hide();
	$('embed').hide();
	
	$.blockUI({ css: { cursor: 'default', border: 'none', padding: '15px', width: '500px', left: '50%', margin: '-50px 0 0 -250px ',
			backgroundColor: '#fff', '-webkit-border-radius': '10px', '-moz-border-radius': '10px', opacity: '', color: '#000' },
		message: $('#'+div) });
}

function fechaModal() 
{
	$('iframe').show();
	$('embed').show();
	$.unblockUI();
}

function cadastrarModal(div, ajax)
{
	$("#"+div+" .carregando").html("Aguarde enquanto enviamos sua solicitação.").show();
			
	$.ajax({ url: ajax,
		type: 'POST',
		data: $("#"+div).serialize(),
		success: function(i) {
			if(i == "1") {
				alert("Obrigado!\n\nSua solicitação foi cadastrada com Sucesso.\nEntraremos em contato o mais breve possível");
				$("#"+div+" [type^='text']").not("[type^='hidden']").val("");
			} else {
				alert("Falha ao cadastrar solicitação. Tente novamente mais tarde.");
			}
			setTimeout(function() {
				fechaModal();
				$("#"+div+" .carregando").hide();
			},500);
		}
	});
}
