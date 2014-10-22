<?php

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

    public $decimals = array("/\./", "/,/");
    public $rDecimals = array("", ".");

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
     * Numero::floatBoleto()
     * Detecta o separador decimal e retorna um float no padrão bancário.
     * 
     * @param float $numero Numero em qualquer formato.
     * @return float Numero no padrão bancário.
     */
    public function floatBanco($numero)
    {
        if (preg_match('/[0-9]\.[0-9]{3},[0-9]{2}$/', $numero)) {
            //Padrão monetário Brasileiro {n}.000,00
            return (float) preg_replace($this->decimals, $this->rDecimals, $numero);
        } elseif (preg_match('/[0-9],[0-9]{3}\.[0-9]{2}$/', $numero)) {
            //Padrão monetário Americano {n},000.00
            return (float) preg_replace('/,/', '', $numero);
        } elseif (preg_match('/^[0-9]{1,},[0-9]{1,2}$/', $numero)) {
            //Padrão decimais separados por vírgula {n},00. Obs.: Decimais separados por pontos não precisam ser tratados, pois o fallback(else) os trata.
            return (float) preg_replace("/,/", ".", $numero);
        } elseif (preg_match('/^[0-9]{1,},[0-9]{1,}$/', $numero)) {
            //Padrão decimais inifinitos separados por vírgula. Obs.: Decimais infinitos separados por pontos não precisam ser tratados, pois o fallback(else) os trata.
            $numero = preg_replace('/,/', '.', $numero);
            return (float) sprintf('%01.2f', $numero);
        } else {
            //Padrão desconhecido, variáveis infinitas, a qualidade desta projeção caiu abaixo de 30% e outras projeções não estão abertas para especulações úteis.
            return (float) sprintf('%01.2f', $numero);
        }
    }

    //Retorna o valor formatado em reais
    public function moedaCliente($Valor)
    {
        //Valor da Saida em Moeda
        if (!empty($Valor) and is_numeric($Valor)) {
            return "R$ " . $this->floatCliente($Valor);
        } else {
            return "R$ 0,00";
        }
    }
}
