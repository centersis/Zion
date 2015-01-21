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
 * \Zion\Exception\Exception()
 * 
 * @author Feliphe "O Retaliador" Bueno - feliphezion@gmail.com
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
        return $e->getMessage();
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