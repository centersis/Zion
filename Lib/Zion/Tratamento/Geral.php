<?php
/**
 * Geral
 * @author Feliphe "O Retaliador" Bueno - feliphezion@gmail.com
 * @since 20/10/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Tratamento de inputs específicamente Brasileiros.
 * 
 */
namespace Zion\Tratamento;

class Geral
{

    /** 
     * @var object $instancia Instância da classe singleton
     */
    private static $instancia;

    /**
     * Geral::__construct()
     * Construtor, tão tosco quanto necessário para a implementação singleton.
     * 
     * @return void
     */
    private function __construct(){
        
    }

    /**
     * Geral::instancia()
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     * 
     * @return object
     */
    public function instancia(){
        
        if(!isset(self::$instancia)){
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
    public function formataCPF($cpf)
    {

        $cpfFormatado = NULL;
        
        if(preg_match('/^\d{3}.\d{3}.\d{3}-\d{2}$/', $cpf)) return(\Zion\Validacao\Geral::validaCPF($cpf) === true ? $cpf : false);
        
        if(\Zion\Validacao\Geral::validaCPF($cpf)){

            $cpfFormatado = substr($cpf, 0, 3) .'.'. substr($cpf, 3, 3) .'.'. substr($cpf, 6, 3) .'-'. substr($cpf, -2);

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
    public function formataCNPJ($cnpj)
    {

        $cnpjFormatado = NULL;

        if(preg_match('/^\d{2}\.\d{3}.\d{3}\/\d{4}-\d{2}$/', $cnpj)) return(\Zion\Validacao\Geral::validaCNPJ($cnpj) === true ? $cnpj : false);

        if(\Zion\Validacao\Geral::validaCNPJ($cnpj)){

            $cnpjFormatado = substr($cnpj, 0, 2) .'.'. substr($cnpj, 2, 3) .'.'. substr($cnpj, 5, 3) .'/'. substr($cnpj, 8, 4) .'-'. substr($cnpj, -2);

        } else {

            $cnpjFormatado = false;

        }
        
        return $cnpjFormatado;
    }


    /**
     * Geral::formataCEP()
     * 
     * @param mixed $cep
     * @return
     */
    public function formataCEP($cep)
    {
        $cepFormatado = NULL;

        if(preg_match('/^\d{2}\.\d{3}[-|\s]?[0-9]{3}$/', $cep)) return(\Zion\Validacao\Geral::validaCEP($cep) === true ? $cep : false);

        $cep = preg_replace('/[^0-9]/', '', $cep);

        if(\Zion\Validacao\Geral::validaCEP($cep)){

            $cepFormatado = substr($cep, 0, 2) .'.'. substr($cep, 2, 3) .'-'. substr($cep, -3);

        } else {

            $cepFormatado = false;

        }

        return $cepFormatado;
    }

    /**
     * Geral::formataTelefone()
     * 
     * @param mixed $telefone
     * @return
     */
    public function formataTelefone($telefone)
    {
        throw new RuntimeException("Metoto ainda nao implementado.");
    }

}