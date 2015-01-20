<?php
/*

    Sappiens Framework
    Copyright (C) 2014, BRA Consultoria

    Website do autor: www.braconsultoria.com.br/sappiens
    Email do autor: sappiens@braconsultoria.com.br

    Website do projeto, equipe e documentação: www.sappiens.com.br
   
    Este programa é software livre; você pode redistribuí-lo e/ou
    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
    publicada pela Free Software Foundation, versão 2.

    Este programa é distribuído na expectativa de ser útil, mas SEM
    QUALQUER GARANTIA; sem mesmo a garantia implícita de
    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
    detalhes.
 
    Você deve ter recebido uma cópia da Licença Pública Geral GNU
    junto com este programa; se não, escreva para a Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
    02111-1307, USA.

    Cópias da licença disponíveis em /Sappiens/_doc/licenca

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

class XLS extends ExportacaoVO 
{
    
    private $html;
    
    public function getXLS()
    {
        $this->html = new \Zion\Layout\Html();
        return self::geraRelatorio();
    }
    
    private function geraRelatorio()
    {

        $dadosRelatorio = parent::getDadosRelatorio();
        $colunas = parent::getColunas();
        $larguras = parent::getColsWidth();

        $numColunas = count($colunas);

        if(!is_array($dadosRelatorio)) return false;
        if($numColunas < 1) return false;

        include_once(SIS_FM_BASE . 'Lib\PHPExcel\Classes\PHPExcel.php'); $objPHPExcel = new \PHPExcel();
        
        $objPHPExcel->setActiveSheetIndex(0);
        
        $columns = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'G', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        
        $curWidths = array();

        $i = 0;
        $l = 2;
        $c = 0;
        while($i <= count($dadosRelatorio)){

            foreach($colunas as $col=>$name){
                    $valor = @$dadosRelatorio[$i][$col];
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($c, $l, $valor);
                    
                    @$curWidths[$c] = ((strlen($valor) * 2) > (int) @$curWidths[$c] ? (strlen($valor) * 1.5) : $curWidths[$c]);
                    $c++;
                }
            $c = 0;
            $i++;
            $l++;
        }

        $i = 0;
        foreach($colunas as $col=>$name){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, 1, $name);
            $objPHPExcel->getActiveSheet()->getColumnDimension($columns[$i])->setWidth($curWidths[$i]);
            $objPHPExcel->getActiveSheet()->getStyle($columns[$i] . 1)->getFont()->setBold(true);
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Exportação de dados do Sappiens');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. uniqid() .'.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

    }

}