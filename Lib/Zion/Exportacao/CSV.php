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

class CSV extends ExportacaoVO 
{
    /**
     * CSV::getCSV()
     * 
     * @return
     */
    public function getCSV()
    {
        return self::geraRelatorio();
    }
    
    /**
     * CSV::geraRelatorio()
     * 
     * @return
     */
    private function geraRelatorio(){

        $dadosRelatorio = parent::getDadosRelatorio();
        $colunas = parent::getColunas();
        
        $numColunas = count($colunas);

        if(!is_array($dadosRelatorio)) return false;
        if($numColunas < 1) return false;

        $dadosCSV = NULL;
        foreach($colunas as $col=>$name){
            $dadosCSV .= ($name) .';';
        }

        $dadosCSV =  substr($dadosCSV, false, -1) . "\n";

        $i = 0;

        while($i <= count($dadosRelatorio)){

            if(!@is_array($dadosRelatorio[$i])) {
                $i++;
                continue;
            }
            
            $linha = NULL;

            foreach($colunas as $col=>$name){
                $linha .= ($dadosRelatorio[$i][$col]) .';';
            }
            
            $dadosCSV .= substr($linha, false, -1) . "\n";

            $i++;
        }

        $nome       =  uniqid() .'.csv';
        $caminho    = SIS_DIR_BASE . 'arquivos/'. $nome;

		$arquivo = fopen($caminho, "a");

		@fwrite($arquivo, $dadosCSV);
        //print $dadosCSV;
		if (!file_exists($caminho)) return false;
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='. $nome .';');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '. @filesize($caminho));
		set_time_limit(0);
		if(!@readfile($caminho)) return false;
		return(@fclose($arquivo)) ? true : false;

    }
  
}