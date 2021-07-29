<?php

namespace Zion\Validacao;

class Valida
{

    private static $instancia;

    private function __construct()
    {
        
    }

    public static function instancia()
    {

        if (!isset(self::$instancia)) {
            self::$instancia = new self;
        }

        return self::$instancia;
    }

    /**
     * Valida::texto()
     * Retorna uma instância da classe de tratamento de Strings. Texto()
     * 
     * @return \Zion\Validacao\Texto
     */
    public function texto()
    {
        return Texto::instancia();
    }

    /**
     * Valida::data()
     * Retorna uma instância da classe de tratamento de Datas. Data()
     * 
     * @return \Zion\Validacao\Data
     */
    public function data()
    {
        return Data::instancia();
    }

    /**
     * Valida::numero()
     * Retorna uma instância da classe de tratamento de Float. Numero()
     * 
     * @return \Zion\Validacao\Numero
     */
    public function numero()
    {
        return Numero::instancia();
    }

    /**
     * Valida::geral()
     * Retorna uma instância da classe de tratamento de inputs especias. Geral()
     * 
     * @return \Zion\Validacao\Geral
     */
    public function geral()
    {
        return Geral::instancia();
    }

}
