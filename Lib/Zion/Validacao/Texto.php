<?php

/**
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 11/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Tratamento de texto para manipulação e inserção no Banco de Dados.
 */

namespace Zion\Validacao;

class Texto extends \Zion\Tratamento\Texto
{

    /**
     * Texto::verificaMinimoCaracteres()
     * Verifica se o comprimento de uma string informada pelo usuário é igual ou maior que minimo solicitado.
     * 
     * @param integer $min Comprimento minimo desejado //pegou mal esse lance de comprimento...rs
     * @param string $str String informada pelo usuário
     * @return bool True se o comprimento de $str for maior ou igual a $min, FALSE otherwise.
     */
    public function verificaMinimoCaracteres($min, $str){
        return(strlen($str) >= $min ? true : false);
    }
    
    /**
     * Texto::verificaMaximoCaracteres()
     * Verifica se um número informado pelo usuário é igual ou menor que máximo aceito.
     * 
     * @param integer $min Comprimento minimo desejado
     * @param string $str String informada pelo usuário
     * @return bool True se o comprimento de $str for menor ou igual a $max, FALSE otherwise.
     */
    public function verificaMaximoCaracteres($max, $str){
        return(strlen($str) <= $max ? true : false);
    }

}