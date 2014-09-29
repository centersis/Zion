<?php

/**
 * Valida()
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 15/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Interface para integração com outras classes de tratamento de dados.
 */

namespace Zion\Validacao;

class Valida
{
    
    /**
     * Valida::texto()
     * Retorna uma instância da classe de tratamento de Strings. Texto()
     * 
     * @return object()
     */
    public function texto()
    {
        return new \Zion\Validacao\Texto();
    }
    
    /**
     * Valida::data()
     * Retorna uma instância da classe de tratamento de Datas. Data()
     * 
     * @return object()
     */
    public function data()
    {
        return new \Zion\Validacao\Data();
    }
    
    /**
     * Valida::numero()
     * Retorna uma instância da classe de tratamento de Float. Numero()
     * 
     * @return object()
     */
    public function numero()
    {
        return new \Zion\Validacao\Numero();
    }
    
    /**
     * Valida::geral()
     * Retorna uma instância da classe de tratamento de inputs especias. Geral()
     * 
     * @return object()
     */
    public function geral()
    {
        return new \Zion\Validacao\Geral();
    }

}