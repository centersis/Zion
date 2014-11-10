<?php
/**
 * 
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 5/11/2014
 * @version 1.0
 * @copyright 2014
 * 
 * 
 * 
 */

namespace Zion\Exportacao;

class PDF extends ExportacaoVO 
{
    
    private $html;
    
    public function getPDF()
    {
        $this->html = new \Zion\Layout\Html();
        return self::geraRelatorio();
    }
    
    private function geraRelatorio()
    {

        $dadosRelatorio = parent::getDadosRelatorio();
        $colunas = parent::getColunas();
        
        $numColunas = count($colunas);

        if(!is_array($dadosRelatorio)) return false;
        if($numColunas < 1) return false;

        $urlLogo = parent::getUrlLogo();
        $orientacao = ($numColunas > 1 ? 'L' : parent::getOrientacaoRelatorio());
        $colsWidth = parent::getColsWidth();

        $html  = $this->html->abreTagAberta('html', ['xmlns' => 'http://www.w3.org/1999/xhtml']);
        $html .= $this->html->abreTagAberta('head');
        $html .= $this->html->abreTagFechada('meta', ['http-equiv' => 'Content-Type', 'content' => 'text/html; charset=utf-8']);
        $html .= $this->html->fechaTag('head');
        
        $html .= $this->html->abreTagAberta('body');
        $html .= $this->html->abreTagAberta('div', ['align' => "right", 'style' => 'font:Arial, Helvetica, sans-serif;font-size:6px;']);
        $html .= SIS_DESCRICAO .". Assinatura do arquivo: relatorio gerado as ". date('d-m-Y-H:i:s'). ", por Feliphe, \"O Retaliador\".\n";
        $html .= $this->html->fechaTag('div');
        $html .= $this->html->abreTagAberta('table', ['width' => "100%", 'cellspacing' => "0", 'cellpadding' => "0", 'style' => parent::getMainTableStyle()]);
        $html .= $this->html->abreTagAberta('thead');

        $html .= $this->html->abreTagAberta('tr');
        $html .= $this->html->abreTagAberta('td', ['colspan' => $numColunas, 'align' =>  parent::getLogoAlignment()]);
        $html .= $this->html->abreTagAberta('div');
        $html .= $this->html->abreTagFechada('img', ['src' => $urlLogo, 'style="align: left;"', 'class' => 'logo']);
        $html .= $this->html->fechaTag('div');
        $html .= $this->html->fechaTag('td');
        $html .= $this->html->fechaTag('tr');

        $html .= $this->html->abreTagAberta('tr');
        $html .= $this->html->abreTagAberta('td', ['colspan' => $numColunas, 'height' => 50, 'class' => "t12preto tituloRelatorio", 'align' => "center"]);
        $html .= $this->html->entreTags('strong', parent::getTituloRelatorio());
        $html .= $this->html->fechaTag('td');
        $html .= $this->html->fechaTag('tr');


        $html .= $this->html->abreTagAberta('tr');
        $html .= $this->html->abreTagAberta('td', ['colspan' => $numColunas, 'height' => 50, 'class' => "mTextoRelatorio descricaoRelatorio", 'align' => "left"]);
        $html .= $this->html->entreTags('strong',  parent::getDescricaoRelatorio());
        $html .= $this->html->fechaTag('td');
        $html .= $this->html->fechaTag('tr');
       

        $html .= $this->html->abreTagAberta('tr');

        //Cabeçalho
        $i=0;
        foreach($colunas as $col=>$name) {

            $html .= $this->html->abreTagAberta('td', ['class' => 'linhaConteudo header', 'width' => (empty($colsWidth[$i]) ? round(100 / $numColunas) : $colsWidth[$col]), 'height' => parent::getColsHeight(), 'align' => "center"]);
            $html .= $name;
            $html .= $this->html->fechaTag('td');
            $i++;
        }
            
        $html .= $this->html->fechaTag('tr');
        $html .= $this->html->fechaTag('thead');

        //Grid
        foreach($dadosRelatorio as $key => $value){

            $html .= $this->html->abreTagAberta('tr');

            foreach($colunas as $col=>$name){

                $html .= $this->html->abreTagAberta('td', ['class' => 'mTextoRelatorio cell', 'width' => (empty($colsWidth[$i]) ? round(100 / $numColunas) : $colsWidth[$col]), 'height' => parent::getColsHeight(), 'align' => "center"]);
                $html .= $value[$col];
                $html .= $this->html->fechaTag('td');

            }

            $html .= $this->html->fechaTag('tr');

            $html .= $this->html->abreTagAberta('tr');
            $html .= $this->html->abreTagAberta('td', ['class' => 'separador', 'colspan' => $numColunas, 'height' => '1px']);
            $html .= '<hr />';
            $html .= $this->html->fechaTag('td');
            $html .= $this->html->fechaTag('tr');

        }

        $html .= $this->html->fechaTag('table');
        $html .= $this->html->fechaTag('body');
        $html .= $this->html->fechaTag('html');

		try {

			$html = $html;
            include_once(SIS_FM_BASE . 'Lib\mPDF\mpdf.php');
			$mpdf = new \mPDF();
			$mpdf->allow_charset_conversion=true;
			$mpdf->charset_in='UTF-8';
			$stylesheet = parent::getStylesheet();

			$mpdf->setFooter('{PAGENO}/{nbpg}');
			$mpdf->CurOrientation = $orientacao;
			$mpdf->WriteHTML($stylesheet, 1);
			$mpdf->WriteHTML($html, 2);
			$mpdf->Output(uniqid() .'_relatorio_'. date('d-m-Y-H:i:s').'.pdf','D');
//print '<style type="text/css">'. $stylesheet .'</style>'. $html;
		 } catch(Exception $e){
			return false;
		 }

    }
}