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

class PDF 
{
   
    public function imprimePDF($html, $tituloArquivo = NULL, $orientacao = NULL)
    {

        $titulo     = (is_null($tituloArquivo) ? uniqid() .'_relatorio_'. date('d-m-Y-H:i:s') : $tituloArquivo) .'.pdf';
        $orientacao = (is_null($orientacao) ? 'P' : $orientacao);

		try {

            include_once(SIS_FM_BASE . 'Lib\mPDF\mpdf.php');
			$mpdf = new \mPDF();

			$mpdf->CurOrientation = $orientacao;

			$mpdf->allow_charset_conversion = true;
			$mpdf->charset_in    = 'UTF-8';
			$stylesheet          = $this->getEstiloRelatorio();

			$mpdf->setFooter('{PAGENO}/{nbpg}');
			$mpdf->WriteHTML($stylesheet, 1);
			$mpdf->WriteHTML($html, 2);
			$mpdf->Output($titulo, 'D');
//print('<style type="text/css">'. $stylesheet .'</style>'. $html);
		 } catch(Exception $e) {
			return false;
		 }

    }
    
    private function getEstiloRelatorio()
    {

        $stylesheet = '
            th {
                font-family: Verdana, Arial, Helvetica, sans-serif;
                background-color: #666666;
                color:#FFFFFF;
                font-size: 13px;
                height:30px;
            }
			tbody{
				margin-top:20px;
				border:1px solid #666666;
				border-bottom: none;
			}
			.table-bordered {
				margin-bottom:20px;
                width: 100%;
			 }
            .table-bordered tr{
                border:1px solid #666666;
                
			}
            td {
                border:1px solid #666666;
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 12px;
				text-align:center;
				height:25px;
            }
            .t12preto {
                font-family: Verdana, Arial, Helvetica, sans-serif;            
                font-size: 12px;
                color: #000000;            
                text-decoration: none;
                border:none;
            }
            .table-footer{
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 12px;
                text-decoration: none;
            }';

        return $stylesheet;
    }
}