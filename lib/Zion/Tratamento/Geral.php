<?php

namespace Zion\Tratamento;

use Zion\Exception\ErrorException;
use Zion\Validacao\Geral as ValidacaoGeral;

class Geral {

    private static $instancia;

    /**
     * Geral::__construct()
     * Construtor, tão tosco quanto necessário para a implementação singleton.
     * 
     * @return void
     */
    private function __construct() {
        
    }

    /**
     * Geral::instancia()
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     * 
     * @return Geral
     */
    public static function instancia() {

        if (!isset(self::$instancia)) {
            self::$instancia = new self;
        }

        return self::$instancia;
    }

    /**
     * Geral::formataCPF()
     * 
     * @param mixed $cpf
     * @return
     */
    public function formataCPF($cpf) {

        $cpfFormatado = NULL;

        if (preg_match('/^\d{3}.\d{3}.\d{3}-\d{2}$/', $cpf)) {
            return(ValidacaoGeral::validaCPF($cpf) === true ? $cpf : false);
        }

        if (ValidacaoGeral::validaCPF($cpf)) {

            $cpfFormatado = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, -2);
        } else {

            $cpfFormatado = false;
        }

        return $cpfFormatado;
    }

    /**
     * Geral::formataCNPJ()
     * 
     * @param mixed $cnpj
     * @return
     */
    public function formataCNPJ($cnpj) {

        $cnpjFormatado = NULL;

        if (preg_match('/^\d{2}\.\d{3}.\d{3}\/\d{4}-\d{2}$/', $cnpj)) {
            return(ValidacaoGeral::validaCNPJ($cnpj) === true ? $cnpj : false);
        }

        if (ValidacaoGeral::validaCNPJ($cnpj)) {

            $cnpjFormatado = substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, -2);
        } else {

            $cnpjFormatado = false;
        }

        return $cnpjFormatado;
    }

    /**
     * Geral::limpaCPF_CNPJ()
     * 
     * @param mixed $str
     * @return
     */
    public function limpaCPF_CNPJ($str) {

        return preg_replace('/[^0-9]/', '', $str);
    }

    /**
     * Geral::formataCEP()
     * 
     * @param mixed $cep
     * @return
     */
    public function formataCEP($cep) {
        $cepFormatado = NULL;

        if (preg_match('/^\d{2}\.\d{3}[-|\s]?[0-9]{3}$/', $cep)) {
            return(ValidacaoGeral::validaCEP($cep) === true ? $cep : false);
        }

        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (ValidacaoGeral::validaCEP($cep)) {

            $cepFormatado = substr($cep, 0, 2) . '.' . substr($cep, 2, 3) . '-' . substr($cep, -3);
        } else {

            $cepFormatado = false;
        }

        return $cepFormatado;
    }

    /**
     * Geral::destrataCEP()
     * 
     * @param mixed $cep
     * @return
     */
    public function destrataCEP($cep) {
        return preg_replace('/[^0-9]/', '', $cep);
    }

    /**
     * Geral::formataTelefone()
     * 
     * @param mixed $telefone
     * @return
     */
    public function formataTelefone($telefone) {
        throw new ErrorException("Metoto ainda nao implementado.");
    }

    /**
     * Geral::ordenaArray()
     * 
     * @param array $array
     * @param mixed $on
     * @param mixed $order
     * @return array
     */
    public function ordenaArray($array, $on, $order = \SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case \SORT_ASC:
                    asort($sortable_array);
                    break;
                case \SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    public function ordenaArrayMulti($array, $on1, $on2, $order1 = \SORT_ASC, $order2 = \SORT_ASC) {

        foreach ($array as $key => $value) {
            $a[$key] = $value[$on2];
            $b[$key] = $value[$on1];
        }

        array_multisort($b, $order1, $a, $order2, $array);
        return $array;
    }

    public function montaLoop($start, $end) {

        if ($start >= $end) {

            return false;
        }

        $length = strlen($end);
        for ($a = $start; $a <= $end; $a++) {

            $v[str_pad($a, $length, '0', STR_PAD_LEFT)] = str_pad($a, $length, '0', STR_PAD_LEFT);
        }

        return $v;
    }

}
