<?php

/**
 * Valida()
 * @author Feliphe "O Retaliador" Bueno - feliphezion@gmail.com
 * @since 20/10/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Interface para integração com outras classes de tratamento de dados.
 */

namespace Zion\Tratamento;

class Valida
{
    
    /**
     * Tratamento::texto()
     * Retorna uma instância da classe de tratamento de Strings. Texto()
     * 
     * @return object
     */
    public function texto()
    {
        return new \Zion\Tratamento\Texto();
    }
    
    /**
     * Tratamento::data()
     * Retorna uma instância da classe de tratamento de Datas. Data()
     * 
     * @return object
     */
    public function data()
    {
        return new \Zion\Tratamento\Data();
    }
    
    /**
     * Tratamento::numero()
     * Retorna uma instância da classe de tratamento de Float. Numero()
     * 
     * @return object
     */
    public function numero()
    {
        return new \Zion\Tratamento\Numero();
    }
    
    /**
     * Tratamento::geral()
     * Retorna uma instância da classe de tratamento de inputs especias. Geral()
     * 
     * @return object
     */
    public function geral()
    {
        return new \Zion\Tratamento\Geral();
    }

}