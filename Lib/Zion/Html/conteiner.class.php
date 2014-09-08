<?
class conteiner
{
	public function abreConteiner($Id, $Classe = null, $Alinha = null)
	{
		$Classe = (empty($Classe)) ? null : 'class="'.$Classe.'"';
		
		$Alinha = (empty($Alinha)) ? null : 'align="'.$Alinha.'"';
		
		return '<div id="'.$Id.'" '.$Classe.' '.$Alinha.'>';
	}
	
	public function fechaConteiner()
	{
		return '</div>';
	}
	
	public function carregando()
	{
		return $this->abreConteiner("carregando","loading").'<img src="'.$_SESSION['UrlBase'].'figuras/loading.gif"> Carregando...'.self::fechaConteiner();
	}	
}
?>