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

class Data
{

    /** Data::dataHora()
     * 	Retorna a Data e Hora Atual do Server no padrão Brasileiro.
     * 
     * 	@return String
     */
    public function dataHora()
    {
        return date("d/m/Y H:i:s");
    }

    /** Data::dataAtual()
     * 	Retorna a Data Atual do Server no padrão Brasileiro.
     * 
     * 	@return String
     */
    public function dataAtual()
    {
        return date("d/m/Y");
    }

    /**
     * Data::validaData()
     * Valida uma data/hora.
     * 
     * @param String $data Data/Hora a ser validada nos formatos d/m/Y ou Y-m-d, ambos suportam H:i:s.
     * @return bool TRUE se a data/hora for válida, False otherwise.
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
     * @return bool TRUE se a hora for válida, False otherwise.
     */
    public function validaHora($hora)
    {

        $time = \DateTime::createFromFormat('H:i:s', $hora);
        return ($time ? true : false);
    }

    /**
     * Data::getFormatoDataHora()
     * Detecta o formato de uma data/hora, independente do formato.
     * 
     * @param mixed $dataHora Data/Hora a ser verificada.
     * @return String formato encontrado, FALSE otherwise.
     */
    public function getFormatoDataHora($dataHora){

        if(preg_match('/^[0-9]{2}[\/|-][0-9]{2}[\/|-][0-9]{4}$|^[0-9]{2}[\/|-][0-9]{2}[\/|-][0-9]{4}\s[0-9]{2}:[0-9]{2}:[0-9]{2}$/', $dataHora)){

           $f = "d/m/Y";

        } elseif(preg_match('/^[0-9]{4}[-|\/][0-9]{2}[-|\/][0-9]{2}$|^[0-9]{4}[-|\/][0-9]{2}[-|\/][0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}$/', $dataHora)) {
            $f = "Y-m-d";
        } elseif(preg_match('/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/', $dataHora)){
            $f = "H:i:s";
        } else {
            return false;
        }

        if(preg_match('/\s[0-9]{2}:[0-9]{2}:[0-9]{2}$/', $dataHora)){
            $f .= " H:i:s";
        }

        return $f;

    }

    /**
     * Data::verificaDiferenca()
     * Compara a diferença entre duas datas informadas, independente do formato, considerando dias, meses, anos, horas, minutos e segundos.
     * 
     * @param mixed $dataI Data Inicial
     * @param mixed $dataF Data Final
     * @return Integer 1 Se $dataI < $dataF, -1 se $dataI > $dataF e 0 se forem iguais. 
     */
    public function verificaDiferencaDataHora($dataI, $dataF){
        
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
     * Data::convertData()
     * Converte data/hora do formato Brasileiro para o Americano e vice-versa, se está for válida. Detecta o formato atual e converte para o outro. Não altera o formato da hora.
     * 
     * @param $data Data/Hora a ser convertida
     * @return String Data convertida, bool FALSE se a data não for válida.
     */
    public function converteData($data)
    {

        if (preg_match('[-]', $data)) {

            $dExt = explode('-', $data);

            if (strlen($dExt[0]) == 4 and strlen($dExt[2]) > 2) {

                $dTimeExt = explode(' ', $dExt[2]);

                return $dTimeExt[0] . '/' . $dExt[1] . '/' . $dExt[0] . ' ' . $dTimeExt[1];
            } elseif (strlen($dExt[0]) == 4 and strlen($dExt[2]) <= 2) {

                return $dExt[2] . '/' . $dExt[1] . '/' . $dExt[0];
            } else {

                return $dExt[2] . '-' . $dExt[1] . '-' . $dExt[0];
            }
        } else {

            $dExt = explode('/', $data);

            if (strlen($dExt[0]) == 4 and strlen($dExt[2]) > 2) {

                $dTimeExt = explode(' ', $dExt[2]);

                return $dTimeExt[0] . '/' . $dExt[1] . '/' . $dExt[0] . ' ' . $dTimeExt[1];
            } elseif (strlen($dExt[0]) == 4 and strlen($dExt[2]) <= 2) {

                return $dExt[2] . '/' . $dExt[1] . '/' . $dExt[0];
            } else {

                return $dExt[2] . '-' . $dExt[1] . '-' . $dExt[0];
            }
        }
    }

    /**
     * Data::trocaSeparador()
     * Detecta o separador automaticamente o substitui pelo inverso, seja [/] ou [-]. Idependente do formato.
     * 
     * @param String $data Data que terá os separadores trocados. Qualquer formato.
     * @return String Data com os sepradores trocados.
     */
    public function trocaSeparador($data)
    {

        if ($this->getSeparador($data) == "/") {
            return preg_replace('[/]', '-', $data);
        } else {
            return preg_replace('[-]', '/', $data);
        }
    }

    /**
     * Data::verificaDataIntervalo()
     * Verifica se uma determinada data está dentro de um intervalo informado. 
     * 
     * @param String $data Data a ser verificada.
     * @param String $dataInicial Data Inicial do intervalo.
     * @param String $DataFinal Data Final do do intervalo.
     * @return bool TRUE se a data estiver no intervalo, FALSE otherwise.
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

    /**
     * Data::somaData()
     * Soma duas datas, considerando dias, meses e anos. Independente do formato.
     * 
     * @param String $dataA Uma das datas a serem somadas. Em qualquer formato.
     * @param String $dataB Outra das datas a serem somadas. Em qualquer formato.
     * @return String Resultado da soma das datas. No formato d/m/Y.
     */
    public function somaData($dataA, $dataB)
    {

        if ($this->getSeparador($dataA) == "/"){
            $dataA = $this->converteData($dataA);
        }

        $delim = $this->getSeparador($dataA);
        $delimB = $this->getSeparador($dataB);

        if ($delimB != $delim){
            $dataB = $this->converteData($dataB);
        }
        
        list($anoA, $mesA, $diaA) = explode($delim, $dataA);
        list($anoB, $mesB, $diaB) = explode($delim, $dataB);

        return date('d/m/Y', mktime(0, 0, 0, ($mesA + $mesB), ($diaA + $diaB), ($anoA + ($anoB <= 15 ? $anoB : 0))));
    }

    /**
     * Data::subtraiData()
     * Subtrai duas datas, considerando dias, meses e anos. Independente do formato.
     * 
     * @param String $dataA Uma das datas a serem subtraídas. Em qualquer formato.
     * @param String $dataB Outra das datas a serem subtraídas. Em qualquer formato.
     * @return String Resultado da subtração das datas. No formato d/m/Y.
     */
    public function subtraiData($dataA, $dataB)
    {

        if ($this->getSeparador($dataA) == "/"){
            $dataA = $this->converteData($dataA);
        }

        $delim = $this->getSeparador($dataA);
        $delimB = $this->getSeparador($dataB);

        if ($delimB != $delim){
            $dataB = $this->converteData($dataB);
        }
        
        //Detecta qual valor é o mais alto para subtrair deste, evitando resultudos negativos.
        if ($dataA > $dataB) {
            $dataY = $dataA;
            $dataX = $dataB;
        } else {
            $dataY = $dataB;
            $dataX = $dataA;
        }

        list($anoA, $mesA, $diaA) = explode($delim, $dataY);
        list($anoB, $mesB, $diaB) = explode($delim, $dataX);

        return date('d/m/Y', mktime(0, 0, 0, ($mesA - $mesB), ($diaA - $diaB), ($anoA - ($anoB <= 60 ? $anoB : 0))));
    }

    /**
     * Data::somaHora()
     * Soma horas distintas, considerando Horas, Minutos e Segundos.
     * 
     * @param mixed $horaA Uma das horas a serem somadas. No formato H:i:s
     * @param mixed $horaB Outra das horas a serem somadas. No formato H:i:s
     * @return String Resultado da soma das horas. No Formato H:i:s
     */
    public function somaHora($horaA, $horaB)
    {

        list($hora, $min, $sec) = explode(":", $horaA);
        list($horaB, $minB, $secB) = explode(":", $horaB);

        return date('H:i:s', mktime(($hora + $horaB), ($min + $minB), ($sec + $secB)));
    }

    /**
     * Data::subtraiHora()
     * Subtrai horas distintas, considerando Horas, Minutos e Segundos.
     * 
     * @param mixed $horaA Uma das horas a serem subtraídas. No formato H:i:s
     * @param mixed $horaB Outra das horas a serem subtraídas. No formato H:i:s
     * @return String Resultado da subtração das horas. No Formato H:i:s
     */
    public function subtraiHora($horaA, $horaB)
    {

        //Detecta qual valor é o mais alto para subtrair deste, evitando resultudos negativos.
        if ($horaA > $horaB) {
            $horaY = $horaA;
            $horaX = $horaB;
        } else {
            $horaY = $horaB;
            $horaX = $horaA;
        }

        list($hora, $min, $sec) = explode(":", $horaY);
        list($horaB, $minB, $secB) = explode(":", $horaX);

        return date('H:i:s', mktime(($hora - $horaB), ($min - $minB), ($sec - $secB)));
    }

    /**
     * Data::getSeparador()
     * Detecta automaticamente o separador de uma data. Independente do formato.
     * 
     * @param mixed $data Data a ser verificada. Em qualquer formato.
     * @return String Separador encontrado.
     */
    public function getSeparador($data)
    {

        return(preg_match('[/]', $data) ? '/' : '-');
    }

    /**
     * Data::getMesExt()
     * Retorna o equivalente por extenso de um mês númerico.
     * 
     * @param String $mes Mês a ser convertido.
     * @return String Mês por extenso.
     */
    public function getMesExt($mes)
    {
        throw new RuntimeException("Método ainda não implementado.");
    }

    /**
     * Data::getDataExt()
     * Retorna o equivalente por extenso de uma data númerica.
     * 
     * @param String $data Data a ser convertido.
     * @return String Mês por extenso.
     */
    public function getDataExt($data)
    {
        throw new RuntimeException("Método ainda não implementado.");
    }

}
