<?php

/**
 * @author Feliphe "O Retaliador" Bueno - feliphezion@gmail.com
 * @since 15/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Interface para integração com outras classes de validação de dados.
 */

namespace Zion\Validacao;

class Valida
{

    /** 
     * @var object $instancia Instância da classe singleton
     */
    private static $instancia;

    /**
     * Valida::__construct()
     * Construtor, tão tosco quanto necessário para a implementação singleton.
     * 
     * @return void
     */
    private function __construct(){
        
    }

    /**
     * Valida::instancia()
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     * 
     * @return object
     */
    public static function instancia(){

        if(!isset(self::$instancia)){
            self::$instancia = new self;
        }

        return self::$instancia;
    }

    /**
     * Valida::texto()
     * Retorna uma instância da classe de tratamento de Strings. Texto()
     * 
     * @return object()
     */
    public function texto()
    {
        return \Zion\Validacao\Texto::instancia();
    }
    
    /**
     * Valida::data()
     * Retorna uma instância da classe de tratamento de Datas. Data()
     * 
     * @return object()
     */
    public function data()
    {
        return \Zion\Validacao\Data::instancia();
    }
    
    /**
     * Valida::numero()
     * Retorna uma instância da classe de tratamento de Float. Numero()
     * 
     * @return object()
     */
    public function numero()
    {
        return \Zion\Validacao\Numero::instancia();
    }
    
    /**
     * Valida::geral()
     * Retorna uma instância da classe de tratamento de inputs especias. Geral()
     * 
     * @return object()
     */
    public function geral()
    {
        return \Zion\Validacao\Geral::instancia();
    }

}