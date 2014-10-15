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

    /**
     * Numero::intervalo()
     * Verifica se um determinado valor está dentro de um intervalo. DR15R22YTHNK5JCXWUNT8TGLESERQXED6
     *  
     * @param float $numero Valor a ser verificado. 
     * @param float $min Valor minimo desejado. 
     * @param float $max Valor máximo desejado.
     * @return
     */
    public function intervalo($numero, $min, $max)
    {
        if (preg_match('/[\.|,]/', $numero)) {
            $numero = $this->floatBoleto($numero);
        }
        if (preg_match('/[\.|,]/', $min)) {
            $min = $this->floatBoleto($min);
        }
        if (preg_match('/[\.|,]/', $max)) {
            $max = $this->floatBoleto($max);
        }
        if ($numero >= $min and $numero <= $max) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Numero::isFloat()
     * Verifica se uma string pode ser convertida pra float com sucesso.
     * 
     * @param String $numero Numero a ser convertido para float.
     * @return bool True se a string puder ser convertida, FALSE otherwise.
     */
    public function isFloat($numero)
    {
        if (preg_match('/[0-9]{1,3}[\.|,][0-9]{1,2}$/', $numero) and is_numeric($numero)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Numero::verificaValorMinimo()
     * Verifica se um número informado pelo usuário é igual ou maior que minimo solicitado.
     * 
     * @param mixed $min Valor minimo desejado
     * @param mixed $val Valor informado pelo usuário
     * @return bool True se $val for maior ou igual a $min, FALSE otherwise.
     */
    public function verificaValorMinimo($min, $val)
    {
        return($val >= $min ? true : false);
    }

    /**
     * Numero::verificaValorMinimo()
     * Verifica se um número informado pelo usuário é igual ou menor que máximo aceito.
     * 
     * @param mixed $min Valor máximo aceito
     * @param mixed $val Valor informado pelo usuário
     * @return bool True se $val for menor ou igual a $max, FALSE otherwise.
     */
    public function verificaValorMaximo($max, $val)
    {
        return($val <= $max ? true : false);
    }

}
