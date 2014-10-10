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
        return "Exception ". self::getMessage($e) . " No arquivo: <strong>". $e->getFile() . "</strong>. Linha: <strong>". $e->getLine() ."</strong><br /><br />\n" . self::getTrace($e);
    }
    
    /**
     * Exception::getMessage()
     * 
     * @param Exception $e Objeto da exceção lançada.
     * @return String mensagem da exceção lançada.
     */
    public function getMessage(\Exception $e){
        return $e->getMessage();// .". No arquivo: <strong>". $e->getFile() . "</strong>. Linha: <strong>". $e->getLine() ."</strong>";
    }
    
    /**
     * Exception::getTrace()
     * 
     * @param Exception $e Objeto da exceção lançada.
     * @return String stack trace da exceção lançada.
     */
    public function getTrace(\Exception $e){
        return "Stack Trace: <br />". self::trataTrace($e->getTraceAsString());
    }
    
    /**
     * Exception::trataTrace()
     * 
     * @param mixed $trace
     * @return
     */
    private function trataTrace($trace){
        return preg_replace_callback('/#[1-9]{1,}/', function($match){ return "<br />". $match[0];}, $trace);
    }
    
}