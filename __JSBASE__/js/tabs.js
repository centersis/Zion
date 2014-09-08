function alteraTab(id)
{
	//Atribui o status inativo a todas as abas.
	$("#tabsGeral li").attr('class','tabs_normal tabs_inativa');
	//Esconde todos os conteudos.
	$("#tabsGeral [id^='tabsContent']").hide();

	//Atribuiu o status Ativo a aba selecionada
	$("#tabsGeral #tabsAba"+id).attr('class','tabs_normal tabs_inativa tabs_ativa');
	//Exibe o conteudo da Aba Selecionada.
	$("#tabsGeral #tabsContent"+id).show();
}