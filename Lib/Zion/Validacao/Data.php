<?php

/**
 * Data
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 10/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Tratamento de Data e Hora.
 * 
 */

namespace Zion\Validacao;

class Data extends \Zion\Tratamento\Data
{

    /**
     * Data::validaData()
     * Valida uma data.
     * 
     * @param String $data Data a ser validada nos formatos d/m/Y ou Y-m-d.
     * @return bool
     * @example TRUE se a data for válida, FALSE otherwise.
     */
    public function validaData($data)
    {
        $f      = $this->getFormatoDataHora($data);
        $date   = \DateTime::createFromFormat($f, $data);

        return (@$date ? true : false);
    }

    /**
     * Data::validaHora()
     * Valida uma hora.
     * 
     * @param String $hora Hora a ser validada no formato H:i:s.
     * @return bool
     * @example TRUE se a hora for válida, FALSE otherwise.
     */
    public function validaHora($hora)
    {
        $time = \DateTime::createFromFormat('H:i:s', $hora);
        return ($time ? true : false);
    }

    /**
     * Data::validaDataHora()
     * 
     * @param String $dataHora Data ou Hora a ser validada, independente do formato.
     * @return bool
     * @example TRUE se a data ou hora for válida, FALSE otherwise.
     */
    private function validaDataHora($dataHora)
    {
        return(($this->validaData($dataHora) === true or $this->validaHora($dataHora) === true) ? true : false);
    }

    /**
     * Data::verificaDiferenca()
     * Compara a diferença entre duas datas informadas, independente do formato, considerando dias, meses, anos, horas, minutos e segundos.
     * 
     * @param mixed $dataI Data Inicial
     * @param mixed $dataF Data Final
     * @return Integer
     * @example 1 Se $dataI < $dataF, -1 se $dataI > $dataF e 0 se forem iguais.
     */
    public function verificaDiferencaDataHora($dataI, $dataF)
    {
        if($this->validaDataHora($dataI) === false or $this->validaDataHora($dataF) === false) return false;

        $dI  = \DateTime::createFromFormat($this->getFormatoDataHora($dataI), $dataI);
        $dF  = \DateTime::createFromFormat($this->getFormatoDataHora($dataF), $dataF);
        
        $diff   = $dI->diff($dF);

        $padrao     = array('y' => NULL, 'm' => NULL, 'd' => NULL, 'h' => NULL, 'i' => NULL, 's' => NULL);
        $diferenca  = array_sum(array_intersect_key((array) $diff, $padrao));

        if($diferenca == 0) return 0;

        if($diff->invert == 1){
            return -1;
        } else {
            return 1;
        }
        
    }

    /**
     * Data::verificaDataIntervalo()
     * Verifica se uma determinada data está dentro de um intervalo informado. 
     * 
     * @param String $data Data a ser verificada.
     * @param String $dataInicial Data Inicial do intervalo.
     * @param String $DataFinal Data Final do do intervalo.
     * @return bool
     * @example TRUE se a data estiver no intervalo, FALSE otherwise.
     */
    public function verificaDataIntervalo($data, $dataInicial, $dataFinal)
    {

        if (preg_match('[/]', $data)) {
            $data = $this->converteData($data);
        }
        if (preg_match('[/]', $dataInicial)) {
            $dataInicial = $this->converteData($dataInicial);
        }
        if (preg_match('[/]', $dataFinal)) {
            $dataFinal = $this->converteData($dataFinal);
        }

        if ($this->validaData($data) and $this->validaData($dataInicial) and $this->validaData($dataFinal)) {

            if ($data >= $dataInicial and $data <= $dataFinal) {
                return true;
            } else {
                return false;
            }
        } else {

            return false;
        }
    }
}
