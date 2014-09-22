<?php

/**
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 12/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Tratamento de números (float) para manipulação e inserção no Banco de Dados.
 */

namespace Zion\Validacao;

class Numero
{

    public $decimals = array("/\./", "/,/");
    public $rDecimals = array("", ".");

    /**
     * Numero::floatCliente()
     * Formata um numero float para o padrão de visualização do cliente.
     * 
     * @param float $numero Numero float no padrão bancário.
     * @return float no padrão de visualização do cliente.
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
    public function floatBoleto($numero)
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

    /**
     * Numero::intervalo()
     * Verifica se um determinado valor está dentro de um intervalo.
     *  
     * @param float $numero Valor a ser verificado. 
     * @param float $min Valor minimo desejado. 
     * @param float $max Valor máximo desejado.
     * @return
     */
    public function intervalo($numero, $min, $max)
    {
        if (preg_match('/[\.|,]/', $numero)){
            $numero = $this->floatBoleto($numero);
        }
        if (preg_match('/[\.|,]/', $min)){
            $min = $this->floatBoleto($min);
        }
        if (preg_match('/[\.|,]/', $max)){
            $max = $this->floatBoleto($max);
        }
        if ($numero >= $min and $numero <= $max) {
            return true;
        } else {
            return false;
        }
    }

    public function isFloat($numero)
    {
        if (preg_match('/[0-9]{1,3}[\.|,][0-9]{1,2}$/', $numero) and is_numeric($numero)) {
            return (is_float($numero));
        } else {
            return false;
        }
    }

}