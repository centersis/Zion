<?php
/**
*
*    Sappiens Framework
*    Copyright (C) 2014, BRA Consultoria
*
*    Website do autor: www.braconsultoria.com.br/sappiens
*    Email do autor: sappiens@braconsultoria.com.br
*
*    Website do projeto, equipe e documentação: www.sappiens.com.br
*   
*    Este programa é software livre; você pode redistribuí-lo e/ou
*    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
*    publicada pela Free Software Foundation, versão 2.
*
*    Este programa é distribuído na expectativa de ser útil, mas SEM
*    QUALQUER GARANTIA; sem mesmo a garantia implícita de
*    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
*    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
*    detalhes.
* 
*    Você deve ter recebido uma cópia da Licença Pública Geral GNU
*    junto com este programa; se não, escreva para a Free Software
*    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
*    02111-1307, USA.
*
*    Cópias da licença disponíveis em /Sappiens/_doc/licenca
*
*/

/**
 * Data
 * @author Feliphe "O Retaliador" Bueno - feliphezion@gmail.com
 * @since 10/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Tratamento de Data e Hora.
 * 
 */

namespace Zion\Tratamento;

class Data
{

    /** 
     * @var object $instancia Instância da classe singleton
     */
    private static $instancia;

    /**
     * Data::__construct()
     * Construtor, tão tosco quanto necessário para a implementação singleton.
     * 
     * @return void
     */
    private function __construct(){
        
    }

    /**
     * Data::instancia()
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     * 
     * @return Data
     */
    public static function instancia(){
        
        if(!isset(self::$instancia)){
            self::$instancia = new self;
        }

        return self::$instancia;
    }

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
     * Data::getFormatoDataHora()
     * Detecta o formato de uma data/hora, independente do formato.
     * 
     * @param mixed $dataHora Data/Hora a ser verificada.
     * @return String
     * @example formato encontrado, FALSE otherwise.
     */
    public function getFormatoDataHora($dataHora)
    {
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
     * Data::convertData()
     * Converte data/hora do formato Brasileiro para o Americano e vice-versa, se está for válida. Detecta o formato atual e converte para o outro. Não altera o formato da hora.
     * 
     * @param $data Data/Hora a ser convertida
     * @return String
     * @example Data convertida, bool FALSE se a data não for válida.
     */
    public function converteData($data)
    {

        if(empty($data)){
            return false;
        }
        
        $validaData = \Zion\Validacao\Data::instancia();
        
        if(!$validaData->validaData($data))
        {
            return false;
        }
        
        if (preg_match('[-]', $data)) {

            $dExt = explode('-', $data);

            if (strlen($dExt[0]) == 4 and strlen($dExt[2]) > 2) {

                $dTimeExt = explode(' ', $dExt[2]);

                return $dTimeExt[0] . '/' . $dExt[1] . '/' . $dExt[0] . ' ' . $dTimeExt[1];
            } elseif (strlen($dExt[0]) == 4 and strlen($dExt[2]) <= 2) {

                return $dExt[2] . '/' . $dExt[1] . '/' . $dExt[0];
            } else {
                trigger_error(var_export($dExt,true), E_USER_NOTICE);
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
     * Revisar este metodo
     * @param type $dataHora
     * @return type
     */
    public function converteDataHora($dataHora)
    {
        $pattern = preg_split('/\s| /', $dataHora);

        if(is_array($pattern) and count($pattern) >= 2){
            list($data, $hora) = $pattern;
        } else {
            $data = $pattern[0];
            $hora = "";
        }
        return $this->converteData($data).(!empty($hora) ? ' '. $hora : NULL);
    }

    /**
     * Data::trocaSeparador()
     * Detecta o separador automaticamente o substitui pelo inverso, seja [/] ou [-]. Idependente do formato.
     * 
     * @param String $data Data que terá os separadores trocados. Qualquer formato.
     * @return String
     * @example Data com os sepradores trocados.
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
     * Data::somaData()
     * Soma duas datas, considerando dias, meses e anos. Independente do formato.
     * 
     * @param String $dataA Uma das datas a serem somadas. Em qualquer formato.
     * @param String $dataB Outra das datas a serem somadas. Em qualquer formato.
     * @return String
     * @example Resultado da soma das datas. No formato d/m/Y.
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
     * @return String
     * @example Resultado da subtração das datas. No formato d/m/Y.
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
     * @return String
     * @example Resultado da soma das horas. No Formato H:i:s
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
     * @return String 
     * @example Resultado da subtração das horas. No Formato H:i:s
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
     * @return String
     * @example Separador encontrado.
     */
    private function getSeparador($data)
    {
        return(preg_match('[/]', $data) ? '/' : '-');
    }

    /**
     * Data::getMesExt()
     * Retorna o equivalente por extenso de um mês númerico.
     * 
     * @param String $mes Mês a ser convertido.
     * @return String
     * @example Mês por extenso.
     */
    public function getMesExt($mes)
    {
        throw new \RuntimeException("Método ainda não implementado.");
    }

    /**
     * Data::getDataExt()
     * Retorna o equivalente por extenso de uma data númerica.
     * 
     * @param String $data Data a ser convertido.
     * @return String Mês por extenso.
     * @example Ano por extenso.
     */
    public function getDataExt($data)
    {
        throw new \RuntimeException("Método ainda não implementado.");
    }

}
