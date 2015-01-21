<?php
/**
*
*    Sappiens Framework
*    Copyright (C) 2014, BRA Consultoria
*
*    Website do autor: www.braconsultoria.com.br/sappiens
*    Email do autor: sappiens@braconsultoria.com.br
*
*    Website do projeto, equipe e documentação: www.sappiens.com.br
*   
*    Este programa é software livre; você pode redistribuí-lo e/ou
*    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
*    publicada pela Free Software Foundation, versão 2.
*
*    Este programa é distribuído na expectativa de ser útil, mas SEM
*    QUALQUER GARANTIA; sem mesmo a garantia implícita de
*    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
*    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
*    detalhes.
* 
*    Você deve ter recebido uma cópia da Licença Pública Geral GNU
*    junto com este programa; se não, escreva para a Free Software
*    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
*    02111-1307, USA.
*
*    Cópias da licença disponíveis em /Sappiens/_doc/licenca
*
*/

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