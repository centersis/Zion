<?php

namespace Zion\Validacao;

use Zion\Tratamento\Data as TratamentoData;

class Data extends TratamentoData
{

    private static $instancia;

    public function __construct()
    {
        
    }

    /**
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     * @return self
     */
    public static function instancia()
    {
        if (!isset(self::$instancia)) {
            self::$instancia = new self;
        }

        return self::$instancia;
    }

    /**
     * Data::validaData()
     * Valida uma data.
     * 
     * @param string $data Data a ser validada nos formatos d/m/Y ou Y-m-d.
     * @return bool
     * @example TRUE se a data for válida, FALSE otherwise.
     */
    public function validaData($data)
    {
        if (empty($data)) {
            return false;
        }

        list($d, $m, $y) = preg_split("/[-\.\/ ]/", $data);

        if (strlen($d) == 4) {
            $dAux = $d;
            $yAux = $y;

            $d = $yAux;
            $y = $dAux;
        }

        return checkdate((int) $m, (int) $d, (int) $y);
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
        $doisPontos = substr_count($hora, ':');

        if ($doisPontos == 1) {
            
            list($h, $m) = explode(':', $hora);
            
            if(strlen($h) <> 2 or strlen($m) <> 2){
                return false;
            }
            
            if ($h > 23 or $h < 0) {
                return false;
            }

            if ($m > 59 or $m < 0) {
                return false;
            }
        } else if ($doisPontos == 2) {

            list($h, $m, $s) = explode(':', $hora);

            if(strlen($h) <> 2 or strlen($m) <> 2 or strlen($s) <> 2){
                return false;
            }
            
            if ($h > 23 or $h < 0) {
                return false;
            }

            if ($m > 59 or $m < 0) {
                return false;
            }
            
            if ($s > 59 or $s < 0) {
                return false;
            }
        } else {
            return false;
        }

        return true;
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
        if ($this->validaDataHora($dataI) === false or $this->validaDataHora($dataF) === false) {
            return false;
        }

        $df1 = $this->getFormatoDataHora($dataI);
        $df2 = $this->getFormatoDataHora($dataF);
        
        if ($df1 === false or $df2 === false) {
            return false;
        }
        
        $dI = \DateTime::createFromFormat($df1, $dataI);
        $dF = \DateTime::createFromFormat($df2, $dataF);

        $diff = $dI->diff($dF);

        $padrao = array('y' => NULL, 'm' => NULL, 'd' => NULL, 'h' => NULL, 'i' => NULL, 's' => NULL);
        $diferenca = \array_sum(\array_intersect_key((array) $diff, $padrao));

        if ($diferenca == 0) {
            return 0;
        }

        if ($diff->invert == 1) {
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
     * @param String $dataFinal Data Final do do intervalo.
     * @return bool
     * @example TRUE se a data estiver no intervalo, FALSE otherwise.
     */
    public function verificaDataIntervalo($data, $dataInicial, $dataFinal)
    {

        if (\preg_match('[/]', $data)) {
            $data = $this->converteData($data);
        }
        if (\preg_match('[/]', $dataInicial)) {
            $dataInicial = $this->converteData($dataInicial);
        }
        if (\preg_match('[/]', $dataFinal)) {
            $dataFinal = $this->converteData($dataFinal);
        }

        if ($this->validaData($data) and $this->validaData($dataInicial) and $this->validaData($dataFinal)) {

            if ($data >= $dataInicial and $data <= $dataFinal) {
                return true;
            }
        }

        return false;
    }

}
