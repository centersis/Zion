<?php

/**
 * Retirada do PHPClasses
 * Author: Rubén Crespo Álvarez - rumailster@gmail.com
 * 
 * Atualizada por Pablo Vanni
 */

namespace Zion\Banco\SqlImport;

use Zion\Banco\Conexao;

class SqlImport
{

    private $con;

    public function __construct($con = NULL)
    {
        $this->con = $con;

        if (!$con) {
            $this->con = Conexao::conectar();
        }
    }

    public function importarDeArquivo($origem)
    {
        $linhas = \file($origem, \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);
        
        return $this->importar($linhas);
    }
    
    public function importarDeTexto($texto)
    {
        $linhas = \preg_split ('/$\R?^/m', $texto);
        
        return $this->importar($linhas);
    }
    
    private function importar($linhas)
    {
        
        $buffer = '';

        foreach ($linhas as $linha) {
            
            if (($linha = \trim($linha)) == ''){
                continue;
            }

            // skipping SQL comments
            if (\substr(\ltrim($linha), 0, 2) == '--'){
                continue;
            }

            // An SQL statement could span over multiple lines ...
            if (\substr($linha, -1) != ';') {
                // Add to buffer
                $buffer .= ' '.$linha;
                // Next line
                continue;
            } else
            if ($buffer) {
                $linha = $buffer .' '. $linha;
                // Ok, reset the buffer
                $buffer = '';
            }

            // strip the trailing ;
            $linha = \substr($linha, 0, -1);

            $this->con->executar($linha);
        }
    }

}
