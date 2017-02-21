<?php

namespace Zion\Exportacao;

class CSV
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