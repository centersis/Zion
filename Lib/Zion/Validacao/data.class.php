<?php

/**
 * @author TomaHawk / EDGE
 * @copyright 2014
 */

namespace Zion\Validacao;

class Data{
    
    /**
     * Data::__construct()
     * Construtor
     * 
     * @return void
     */
    public function __construct()
    {

    }
    
    /**
     * Data::validaData()
     * Valida uma data/hora.
     * 
     * @param String $data Data/Hora a ser validada nos formatos d/m/Y ou Y-m-d, ambos suportam H:i:s.
     * @return bool Verdadeiro se a data/hora for válida, False otherwise.
     */
    public function validaData($data)
    {

        if(preg_match('[-]', $data)){
            $f = "Y-m-d";
        } else {
             $f = "d/m/Y";
        }
        
        if(preg_match('[:]', $data))
            $f .= " H:i:s";

        $date   = \DateTime::createFromFormat($f, $data);
        return (@$date->format($f) == $data ? true : false);

    }

    /**
     * Data::validaHora()
     * Valida uma hora.
     * 
     * @param String $hora Hora a ser validada no formato H:i:s.
     * @return bool Verdadeiro se a hora for válida, False otherwise.
     */
    public function validaHora($hora)
    {

        $time   = \DateTime::createFromFormat('H:i:s', $hora);
        return (@$time->format('H:i:s') == $hora ? true : false);

    }

    /**
     * Data::convertData()
     * Converte data/hora do formato Brasileiro para o Americano e vice-versa, se está for válida. Detecta o formato atual e converte para o outro. Não altera o formato da hora.
     * 
     * @param $data Data/Hora a ser convertida
     * @return String Data convertida, bool FALSE se a data não for válida.
     */
    public function converteData($data)
    {

        if($this->validaData($data) === false) 
            return false;

        if(preg_match('[-]', $data)){

            $dExt    = explode('-', $data);

            if(strlen($dExt[0]) == 4 and strlen($dExt[2]) > 2){

                $dTimeExt   = explode(' ', $dExt[2]);

                return $dTimeExt[0] .'/'. $dExt[1] . '/'. $dExt[0] . ' '. $dTimeExt[1];

            } elseif(strlen($dExt[0]) == 4 and strlen($dExt[2]) <= 2) {

                return $dExt[2] .'/'. $dExt[1] . '/'. $dExt[0];

            } else {

                 return $dExt[2] .'-'. $dExt[1] . '-'. $dExt[0];
            }

        } else {
            
            $dExt    = explode('/', $data);

            if(strlen($dExt[0]) == 4 and strlen($dExt[2]) > 2){

                $dTimeExt   = explode(' ', $dExt[2]);

                return $dTimeExt[0] .'/'. $dExt[1] . '/'. $dExt[0] . ' '. $dTimeExt[1];

            } elseif(strlen($dExt[0]) == 4 and strlen($dExt[2]) <= 2) {

                return $dExt[2] .'/'. $dExt[1] . '/'. $dExt[0];

            } else {

                 return $dExt[2] .'-'. $dExt[1] . '-'. $dExt[0];
            }

        }

    }
    
    /**
     * Data::verificaDataIntervalo()
     * Verifica se uma determinada data está dentro de um intervalo informado. 
     * 
     * @param String $data Data a ser verificada.
     * @param String $dataInicial Data Inicial do intervalo.
     * @param String $DataFinal Data Final do do intervalo.
     * @return
     */
    public function verificaDataIntervalo($data, $dataInicial, $DataFinal)
    {

        if(preg_match('[/]', $data))          $data           = $this->converteData($data);
        if(preg_match('[/]', $dataInicial))   $dataInicial    = $this->converteData($dataInicial);
        if(preg_match('[/]', $DataFinal))     $DataFinal      = $this->converteData($DataFinal);
        
        if($this->validaData($data) and $this->validaData($dataInicial) and $this->validaData($DataFinal)){
            
            if($data >= $dataInicial and $data <= $DataFinal){
                return true;
            } else {
                return false;
            }
            
        } else {

            return false;
        }
        
        
    }
    
    public function somaData($dataA, $dataB)
    {

        $delim  = $this->getSeparador($dataA);
        $delimB  = $this->getSeparador($dataB);
        
        if($delimB != $delim)
            $dataB = $this->converteData($dataB);
            
            print $dataB;

        list($anoA, $mesA, $diaA)   = explode($delim, $dataA);
        list($anoB, $mesB, $diaB)   = explode($delim, $dataB);

        return date('d-m-Y', mktime(0, 0, 0, ($mesA + $mesB), ($diaA + $diaB), ($anoA + ($anoB <= 15 ? $anoB : 0))));
    }
    
    public function getSeparador($data){

        return(preg_match('[/]', $data) ? '/' : '-');

    }

}