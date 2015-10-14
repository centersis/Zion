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

namespace Zion\Validacao;

use Zion\Tratamento\Texto as TratamentoTexto;

class Texto extends TratamentoTexto
{

    private static $instancia;

    private function __construct()
    {
        
    }

    /**
     * Texto::instancia()
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     * 
     * @return \Zion\Validacao\Texto
     */
    public static function instancia()
    {

        if (!isset(self::$instancia)) {
            self::$instancia = new self;
        }

        return self::$instancia;
    }

    /**
     * Texto::verificaMinimoCaracteres()
     * Verifica se o comprimento de uma string informada pelo usuário é igual ou maior que minimo solicitado.
     * 
     * @param integer $min Comprimento minimo desejado //pegou mal esse lance de comprimento...rs
     * @param string $str String informada pelo usuário
     * @return bool True se o comprimento de $str for maior ou igual a $min, FALSE otherwise.
     */
    public function verificaMinimoCaracteres($min, $str)
    {
        return(\strlen($str) >= $min ? true : false);
    }

    /**
     * Texto::verificaMaximoCaracteres()
     * Verifica se um número informado pelo usuário é igual ou menor que máximo aceito.
     * 
     * @param integer $max Comprimento minimo desejado
     * @param string $str String informada pelo usuário
     * @return bool True se o comprimento de $str for menor ou igual a $max, FALSE otherwise.
     */
    public function verificaMaximoCaracteres($max, $str)
    {
        return(\strlen($str) <= $max ? true : false);
    }

}
