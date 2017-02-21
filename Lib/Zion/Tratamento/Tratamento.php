<?php

namespace Zion\Tratamento;

use Zion\Tratamento\Texto;
use Zion\Tratamento\Data;
use Zion\Tratamento\Numero;
use Zion\Tratamento\Geral;

class Tratamento
{

    /**
     * @var object $instancia Instância da classe singleton
     */
    private static $instancia;

    public function __construct()
    {
        
    }

    /**
     * Tratamento::instancia()
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     * 
     * @return Tratamento
     */
    public static function instancia()
    {

        if (!isset(self::$instancia)) {
            self::$instancia = new self;
        }

        return self::$instancia;
    }

    /**
     * Tratamento::texto()
     * Retorna uma instância da classe de tratamento de Strings. Texto()
     * 
     * @return Texto
     */
    public function texto()
    {
        return Texto::instancia();
    }

    /**
     * Tratamento::data()
     * Retorna uma instância da classe de tratamento de Datas. Data()
     * 
     * @return Data
     */
    public function data()
    {
        return Data::instancia();
    }

    /**
     * Tratamento::numero()
     * Retorna uma instância da classe de tratamento de Float. Numero()
     * 
     * @return Numero
     */
    public function numero()
    {
        return Numero::instancia();
    }

    /**
     * Tratamento::geral()
     * Retorna uma instância da classe de tratamento de inputs especias. Geral()
     * 
     * @return Geral
     */
    public function geral()
    {
        return Geral::instancia();
    }

}
