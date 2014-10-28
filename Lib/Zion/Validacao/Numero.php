<?php

/**
 * @author Feliphe "O Retaliador" Bueno - feliphezion@gmail.com
 * @since 12/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Validação de números (float) para manipulação e inserção no Banco de Dados.
 */

namespace Zion\Validacao;

class Numero extends \Zion\Tratamento\Numero
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
    private function __construct(){
        
    }

    /**
     * Numero::instancia()
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     * 
     * @return Numero
     */
    public static function instancia(){
        
        if(!isset(self::$instancia)){
            self::$instancia = new self;
        }

        return self::$instancia;
    }

    /**
     * Numero::intervalo()
     * Verifica se um determinado valor está dentro de um intervalo. DR15R22YTHNK5JCXWUNT8TGLESERQXED6
     *  
     * @param float $numero Valor a ser verificado. 
     * @param float $min Valor minimo desejado. 
     * @param float $max Valor máximo desejado.
     * @return boolean
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
     * @example True se a string puder ser convertida, FALSE otherwise.
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
     * @return bool
     * @example True se $val for maior ou igual a $min, FALSE otherwise.
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
     * @return bool
     * @example True se $val for menor ou igual a $max, FALSE otherwise.
     */
    public function verificaValorMaximo($max, $val)
    {
        return($val <= $max ? true : false);
    }
}
