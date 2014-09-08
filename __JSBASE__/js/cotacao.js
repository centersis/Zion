(function($) 
{
	var URLFMBASE = (URLFMBASE == undefined) ? "" : URLFMBASE;
	
	$.fn.cotacao = function(settings) 
	{
		$this = $(this);
		settings = $.extend(
		{
			url: URLFMBASE,
			dolar_comercial: "#dolar_comercial",
			dolar_turismo: "#dolar_turismo",
			euro: "#euro",
			libra: "#libra",
			pesos_arg: "#pesos_arg",
			venda: ".venda",
			compra: ".compra",
			titulo: ".titulo",
			variacao: ".variacao"
		}, settings);

		$(this).find('#content span').addClass("loader");
		
		$.ajax({
			url: settings.url+"cotacao.class.php?Tipo=JS",
			dataType: 'script',
			success: function(i){
				eval(i);
				$.each(Cotacao, function(MoedaIndex, MoedaValue) { 
					$.each(MoedaValue, function(ValorIndex, ValorValue) 
					{ 
						$this.find( eval("settings."+MoedaIndex)+" "+eval("settings."+ValorIndex) ).html(Cotacao[MoedaIndex][ValorIndex]);
					});
				});
				
				$this.find('#content span').removeClass("loader");
			}
		});
		
		return $this;
	}
})(jQuery);