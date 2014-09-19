<?php

/**
 * Data
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 19/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Tratamento de Exceptions.
 * 
 */

namespace Zion\Exception;

class Exception
{

    /**
     * Exception::getMessageTrace()
     * Retorna uma mensagem legível de uma exception.
     * 
     * @param Exception $e Instância da exception encontrada.
     * @return String
     */
    public function getMessageTrace(\Exception $e)
    {
        return "Exception ". $e->getMessage() .". No arquivo: ". $e->getFile() . ". Linha: ". $e->getLine();
    }

}