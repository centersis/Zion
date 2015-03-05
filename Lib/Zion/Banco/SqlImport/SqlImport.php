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

    public function importar($origem)
    {
        $lines = \file($origem, \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);
        $buffer = '';

        foreach ($lines as $line) {
            
            if (($line = \trim($line)) == ''){
                continue;
            }

            // skipping SQL comments
            if (\substr(\ltrim($line), 0, 2) == '--'){
                continue;
            }

            // An SQL statement could span over multiple lines ...
            if (\substr($line, -1) != ';') {
                // Add to buffer
                $buffer .= ' '.$line;
                // Next line
                continue;
            } else
            if ($buffer) {
                $line = $buffer .' '. $line;
                // Ok, reset the buffer
                $buffer = '';
            }

            // strip the trailing ;
            $line = \substr($line, 0, -1);

            $this->con->executar($line);
        }
    }

}
