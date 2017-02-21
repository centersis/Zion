<?php

namespace Zion\Tratamento;

class Numero
{

    private static $instancia;

    private function __construct()
    {
        
    }

    /**
     * Numero::instancia()
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     * 
     * @return object
     */
    public static function instancia()
    {

        if (!isset(self::$instancia)) {
            self::$instancia = new self;
        }

        return self::$instancia;
    }

    /**
     * Numero::floatCliente()
     * Formata um numero float para o padrão de visualização do cliente.
     * 
     * @param float $numero Numero float no padrão bancário.
     * @return float
     * @example float no padrão de visualização do cliente.
     */
    public function floatCliente($numero, $decimal = 2)
    {
        $float = $this->floatBanco($numero);
        return \number_format($float, $decimal, ',', '.');
    }

    /**
     * Numero::floatBanco()
     * Recebe uma string e retorna em formato entendivel para o banco de dados
     * 
     * @param float $numero Numero em qualquer formato.
     * @return float Numero no padrão bancário.
     */
    public function floatBanco($numero)
    {
        if (!empty($numero)) {
            //Verifica de o número ja esta formatado
            if (is_numeric($numero)) {
                return (float) $numero;
            }

            $valorA = \str_replace('.', '', $numero);
            $valorB = \str_replace(',', '.', $valorA);
            return (float) $valorB;
        }

        return 0;
    }

    public function moedaCliente($valor)
    {
        //Valor da Saida em Moeda
        if (!empty($valor) and is_numeric($valor)) {
            return "R$ " . $this->floatCliente($valor);
        } else {
            return "R$ 0,00";
        }
    }

}
