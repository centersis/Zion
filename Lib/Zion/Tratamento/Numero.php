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

/**
 * @author Feliphe "O Retaliador" Bueno - feliphezion@gmail.com
 * @since 20/10/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Tratamento de números (float).
 */

namespace Zion\Tratamento;

class Numero
{

    /**
     * @var object $instancia Instância da classe singleton
     */
    private static $instancia;

    /**
     * Numero::__construct()
     * Construtor, tão tosco quanto necessário para a implementação singleton.
     * 
     * @return void
     */
    private function __construct()
    {
        
    }

    /**
     * Numero::instancia()
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     * 
     * @return object
     */
    public static function instancia()
    {

        if (!isset(self::$instancia)) {
            self::$instancia = new self;
        }

        return self::$instancia;
    }

    /**
     * Numero::floatCliente()
     * Formata um numero float para o padrão de visualização do cliente.
     * 
     * @param float $numero Numero float no padrão bancário.
     * @return float
     * @example float no padrão de visualização do cliente.
     */
    public function floatCliente($numero)
    {
        $float = $this->floatBoleto($numero);
        return number_format($float, 2, ',', '.');
    }

    /**
     * Numero::floatBanco()
     * Recebe uma string e retorna em formato entendivel para o banco de dados
     * 
     * @param float $numero Numero em qualquer formato.
     * @return float Numero no padrão bancário.
     */
    public function floatBanco($numero)
    {
        if (!empty($numero)) {
            //Verifica de o número ja esta formatado
            if (is_numeric($numero)) {
                return (float) $numero;
            }

            $valorA = str_replace(".", "", $numero);
            $valorB = str_replace(",", ".", $valorA);
            return (float) $valorB;
        }

        return 0;
    }

    //Retorna o valor formatado em reais
    public function moedaCliente($valor)
    {
        //Valor da Saida em Moeda
        if (!empty($valor) and is_numeric($valor)) {
            return "R$ " . $this->floatCliente($valor);
        } else {
            return "R$ 0,00";
        }
    }

}
