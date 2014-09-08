﻿<?class Tabelas{	public function tabIni($Largura = 0 , $Borda = 0, $Esp1 = 0, $Esp2 = 0, $Id = null)	{		$Largura = empty($Largura) ? " width=\"100%\""    : " width=\"$Largura\"";		$Borda   = empty($Borda)   ? " border=\"0\""      : " border=\"$Borda\""; 		$Esp1    = empty($Esp1)    ? " cellspacing=\"0\"" : " cellspacing=\"$Esp1\"";		$Esp2    = empty($Esp2)    ? " cellpadding=\"0\"" : " cellpadding=\"$Esp2\"";				if(!empty($Id)) $Id = " id=\"$Id\"";				return "\n<table".$Id.$Largura.$Borda.$Esp1.$Esp2.">\n";	}		/**	*	Fecha instrução de marcação de Tabela	*	@return String	*/	public function tabFim()	{		return "\n</table>\n";	}				/**	*	Abre instrução de marcação TR	*	@return String	*/	public function abreTr($Id = "", $Class = "")	{		$HtmlId = (empty($Id))    ? "" : " id=\"$Id\"";        $Class  = (empty($Class)) ? "" : " class=\"$Class\"";		return "<tr ".$HtmlId.$Class.">\n";	}		/**	*	Fecha instrução de marcação TR	*	@return String	*/	public function fechaTr()	{		return "</tr>\n";	}		public function abreTh($Id = null)	{		if(!empty($Id)) $Id = " id=\"$Id\"";		return "<th>\n";	}		public function fechaTh()	{		return "</th>\n";	}			/**	*	Abre instrução de marcação TD	*	@return String	*/	public function abreTd($Id = "", $Classe = "", $Alinha = "", $Largura = "")	{		$Id      = (empty($Id))      ? "" : " id=\"$Id\" ";		$Alinha  = (empty($Alinha))  ? "" : " align=\"$Alinha\" ";        		$Classe  = (empty($Classe))  ? "" : " class=\"$Classe\"";        $Largura = (empty($Largura)) ? "" : " width=\"$Largura\" ";				return "<td".$Id.$Classe.$Alinha.$Largura.">\n";	}		/**	*	Fecha instrução de marcação TD	*	@return String	*/	public function fechaTd()	{		return "</td>\n";	}			/**	*	Abre instrução de marcação TD com Mesclagem	*	@param Valor Inteiro - Numero de celulas a serem mescladas	*	@return String	*/	public function abreTdMescla($Numero)	{		return "<td colspan='".$Numero."'>\n";	}		public function abreDiv($Id = null, $Alinha = null, $Estilo = null, $Classe = null)	{		if(!empty($Id)) $Id = " id=\"$Id\" ";		if(!empty($Alinha)) $Alinha = " align=\"$Alinha\" ";		if(!empty($Estilo)) $Estilo = " style=\"$Estilo\" ";		if(!empty($Classe)) $Classe = " class=\"$Estilo\" ";					return "<div$Id$Alinha$Estilo>\n";	}		public function fechaDiv()	{		return "</div>\n";	}}?>