// JavaScript Document
$(document).ready(function(){
  $("#tableGrid tr:nth-child()").hover(function(){
	$(this).addClass("over");
	},function(){
	$(this).removeClass("over");
	});
/*
	$("#tableGrid tr SisReg").click(function()
	 {
		$("input[@type='checkbox']",this).attr('checked','checked');
	 		 
	 });*/
	
	//$.shiftcheckbox.init('SisReg');

});
