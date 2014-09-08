// JavaScript Document
$(document).ready(function()
{	  	
	var Parametro = getUrlVars();
	
	$("#tableGrid tr .textoGrid").click(function()
	 {
		var valor = $("input[type='hidden']",this).val();
		
		window.opener.retornoPop(FORMGLOBAL, CAMPOGLOBAL, valor);
		
		if(Parametro['FecharJanela'] != "false") {
			self.close();
		}
	 }) 
});		 


function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
 
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
		hash[1] = unescape(hash[1]);
		vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
 
    return vars;
}