<?php

/**
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 15/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Interface para integração com outras classes de tratamento de dados.
 */

namespace Zion\Validacao;

class Tratamento{
    
    /**
     * Tratamento::texto()
     * Retorna uma instância da classe de tratamento de Strings. Texto()
     * 
     * @return object()
     */
    public function texto(){
        return new \Zion\Validacao\Texto();
    }
    
    /**
     * Tratamento::data()
     * Retorna uma instância da classe de tratamento de Datas. Data()
     * 
     * @return object()
     */
    public function data(){
        return new \Zion\Validacao\Data();
    }
    
    /**
     * Tratamento::numero()
     * Retorna uma instância da classe de tratamento de Float. Numero()
     * 
     * @return object()
     */
    public function numero(){
        return new \Zion\Validacao\Numero;
    }

}

?>