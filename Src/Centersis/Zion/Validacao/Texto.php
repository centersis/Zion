<?php

namespace Centersis\Zion\Validacao;

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
