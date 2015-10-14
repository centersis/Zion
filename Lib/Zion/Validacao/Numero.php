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

use Zion\Tratamento\Numero as TratamentoNumero;

class Numero extends TratamentoNumero
{

    private static $instancia;

    private function __construct()
    {
        
    }

    /**
     * Numero::instancia()
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     * 
     * @return \Zion\Validacao\Numero
     */
    public static function instancia()
    {

        if (!isset(self::$instancia)) {
            self::$instancia = new self;
        }

        return self::$instancia;
    }

    /**
     * Numero::intervalo()
     * Verifica se um determinado valor está dentro de um intervalo
     *  
     * @param float $numero Valor a ser verificado. 
     * @param float $min Valor minimo desejado. 
     * @param float $max Valor máximo desejado.
     * @return boolean
     */
    public function intervalo($numero, $min, $max)
    {
        $numeroT = $this->floatBanco($numero);
        $minT = $this->floatBanco($min);
        $maxT = $this->floatBanco($max);

        return ($numeroT >= $minT and $numeroT <= $maxT) ? true : false;
    }

    /**
     * Numero::isFloat()
     * Verifica se uma string pode ser convertida pra float com sucesso.
     * 
     * @param String $numero Numero a ser convertido para float.
     * @return bool True se a string puder ser convertida, FALSE otherwise.
     * @example True se a string puder ser convertida, FALSE otherwise.
     */
    public function isFloat($numero)
    {
        return (\preg_match('/[^0-9\.\,]/', $numero)) ? false : true;
    }

    /**
     * Numero::verificaValorMinimo()
     * Verifica se um número informado pelo usuário é igual ou maior que minimo solicitado.
     * 
     * @param mixed $min Valor minimo desejado
     * @param mixed $val Valor informado pelo usuário
     * @return bool
     * @example True se $val for maior ou igual a $min, FALSE otherwise.
     */
    public function verificaValorMinimo($min, $val)
    {
        return($val >= $min ? true : false);
    }

    /**
     * Numero::verificaValorMaximo()
     * Verifica se um número informado pelo usuário é igual ou menor que máximo aceito.
     * 
     * @param mixed $max Valor máximo aceito
     * @param mixed $val Valor informado pelo usuário
     * @return bool
     * @example True se $val for menor ou igual a $max, FALSE otherwise.
     */
    public function verificaValorMaximo($max, $val)
    {
        return($val <= $max ? true : false);
    }

}
