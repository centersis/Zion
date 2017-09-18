<?php

namespace Zion\Tratamento;

use Zion\Exception\ErrorException;
use Zion\Validacao\Data as ValidacaoData;

class Data
{

    /**
     * @var object $instancia
     */
    private static $instancia;

    private function __construct()
    {
        
    }

    /**
     * Data::instancia()
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     *
     * @return self
     */
    public static function instancia()
    {

        if (!isset(self::$instancia)) {
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
        return \date("d/m/Y H:i:s");
    }

    /** Data::dataAtual()
     * 	Retorna a Data Atual do Server no padrão Brasileiro.
     *
     * 	@return String
     */
    public function dataAtual()
    {
        return \date("d/m/Y");
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
        if (\preg_match('/^[0-9]{2}[\/|-][0-9]{2}[\/|-][0-9]{4}$|^[0-9]{2}[\/|-][0-9]{2}[\/|-][0-9]{4}\s[0-9]{2}:[0-9]{2}:[0-9]{2}$/', $dataHora)) {
            $f = "d/m/Y";
        } elseif (\preg_match('/^[0-9]{4}[-|\/][0-9]{2}[-|\/][0-9]{2}$|^[0-9]{4}[-|\/][0-9]{2}[-|\/][0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}$/', $dataHora)) {
            $f = "Y-m-d";
        } elseif (\preg_match('/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/', $dataHora)) {
            $f = "H:i:s";
        } else {
            return false;
        }

        if (\preg_match('/\s[0-9]{2}:[0-9]{2}:[0-9]{2}$/', $dataHora)) {
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

        if (empty($data)) {
            return '';
        }

        $validaData = ValidacaoData::instancia();

        //Se for data e Hora recupera apenas a data
        if (\substr_count($data, ' ') > 0) {
            list($data) = \explode(' ', $data);
        }

        if (!$validaData->validaData($data)) {
            return '';
        }

        if (\preg_match('[-]', $data)) {

            $dExt = \explode('-', $data);

            if (\strlen($dExt[0]) == 4 and \strlen($dExt[2]) > 2) {

                $dTimeExt = \explode(' ', $dExt[2]);

                return $dTimeExt[0] . '/' . $dExt[1] . '/' . $dExt[0] . ' ' . $dTimeExt[1];
            } elseif (\strlen($dExt[0]) == 4 and \strlen($dExt[2]) <= 2) {

                return $dExt[2] . '/' . $dExt[1] . '/' . $dExt[0];
            } else {
                \trigger_error(\var_export($dExt, true), \E_USER_NOTICE);
                return $dExt[2] . '-' . $dExt[1] . '-' . $dExt[0];
            }
        } else {

            $dExt = \explode('/', $data);

            if (\strlen($dExt[0]) == 4 and \strlen($dExt[2]) > 2) {

                $dTimeExt = \explode(' ', $dExt[2]);

                return $dTimeExt[0] . '/' . $dExt[1] . '/' . $dExt[0] . ' ' . $dTimeExt[1];
            } elseif (\strlen($dExt[0]) == 4 and \strlen($dExt[2]) <= 2) {

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
        $pattern = \preg_split('/\s| /', $dataHora);

        if (\is_array($pattern) and \count($pattern) >= 2) {
            list($data, $hora) = $pattern;
        } else {
            $data = $pattern[0];
            $hora = "";
        }
        return $this->converteData($data) . (!empty($hora) ? ' ' . $hora : NULL);
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
            return \preg_replace('[/]', '-', $data);
        } else {
            return \preg_replace('[-]', '/', $data);
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

        if ($this->getSeparador($dataA) == "/") {
            $dataA = $this->converteData($dataA);
        }

        $delim = $this->getSeparador($dataA);
        $delimB = $this->getSeparador($dataB);

        if ($delimB != $delim) {
            $dataB = $this->converteData($dataB);
        }

        list($anoA, $mesA, $diaA) = \explode($delim, $dataA);
        list($anoB, $mesB, $diaB) = \explode($delim, $dataB);

        return \date('d/m/Y', \mktime(0, 0, 0, ($mesA + $mesB), ($diaA + $diaB), ($anoA + ($anoB <= 15 ? $anoB : 0))));
    }

    public function atribuiData($data, $dias, $meses, $anos, $separador, $operacao)
    {
        list($dia, $mes, $ano) = \explode($separador, $data);

        return($operacao == "-") ? \date('d' . $separador . 'm' . $separador . 'Y', \mktime(0, 0, 0, $mes - $meses, $dia - $dias, $ano - $anos)) : \date('d' . $separador . 'm' . $separador . 'Y', \mktime(0, 0, 0, $mes + $meses, $dia + $dias, $ano + $anos));
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

        if ($this->getSeparador($dataA) == "/") {
            $dataA = $this->converteData($dataA);
        }

        $delim = $this->getSeparador($dataA);
        $delimB = $this->getSeparador($dataB);

        if ($delimB != $delim) {
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

        list($anoA, $mesA, $diaA) = \explode($delim, $dataY);
        list($anoB, $mesB, $diaB) = \explode($delim, $dataX);

        return date('d/m/Y', \mktime(0, 0, 0, ($mesA - $mesB), ($diaA - $diaB), ($anoA - ($anoB <= 60 ? $anoB : 0))));
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

        list($hora, $min, $sec) = \explode(":", $horaA);
        list($horaB, $minB, $secB) = \explode(":", $horaB);

        return \date('H:i:s', \mktime(($hora + $horaB), ($min + $minB), ($sec + $secB)));
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

        list($hora, $min, $sec) = \explode(":", $horaY);
        list($horaB, $minB, $secB) = \explode(":", $horaX);

        return \date('H:i:s', \mktime(($hora - $horaB), ($min - $minB), ($sec - $secB)));
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
        return(\preg_match('[/]', $data) ? '/' : '-');
    }

    /**
     * Data::getTimeAgo()
     * Retorna o tempo passado deste $dataHora até $dataHoraAtual.
     *
     * @param String $dataHora data/hora inicial para contagem.
     * @param String $completo NULL para retornar o intervalo de tempo maior encontrado, NOT NULL para retornar todos os intervalos de anos a segundos.
     * @param String $dataHoraAtual data/hora final para contagem, se for igual a NULL ou omitido, será substituído pela data/hora atual.
     * @return String
     * @example 10 meses ou 0 Anos, 10 meses, 5 dias, 2 horas, 40 minutos e 20 segundos.
     */
    public function getTimeAgo($dataHora, $completo = NULL, $dataHoraAtual = NULL)
    {
        $formato = $this->getFormatoDataHora($dataHora);
        $dataHoraAtual = (\is_null($dataHoraAtual) ? \date($formato) : $dataHoraAtual);

        $dI = \DateTime::createFromFormat($formato, $dataHora);
        $dF = \DateTime::createFromFormat($formato, $dataHoraAtual);

        $diff = (array) $dI->diff($dF);

        $textAgo = '';

        if ($completo === NULL) {

            if ($diff['y'] > 0) {
                $textAgo = $diff['y'] . ($diff['y'] > 1 ? ' anos' : ' ano');
            } elseif ($diff['y'] == 0 and $diff['m'] > 0) {
                $textAgo = $diff['m'] . ($diff['m'] > 1 ? ' meses' : ' mês');
            } elseif ($diff['m'] == 0 and $diff['d'] > 0) {
                $textAgo = $diff['d'] . ($diff['d'] > 1 ? ' dias' : ' dia');
            } elseif ($diff['d'] == 0 and $diff['h'] > 0) {
                $textAgo = $diff['h'] . ($diff['h'] > 1 ? ' horas' : ' hora');
            } elseif ($diff['h'] == 0 and $diff['i'] > 0) {
                $textAgo = $diff['i'] . ($diff['i'] > 1 ? ' minutos' : ' minuto');
            } elseif ($diff['i'] == 0 and $diff['s'] > 0) {
                $textAgo = $diff['s'] . ($diff['s'] > 1 ? ' segundos' : ' segundo');
            } elseif ($diff['h'] == 0 and $diff['m'] == 0 and $diff['s'] == 0) {
                $textAgo = 'Agora mesmo';
            } else {
                $textAgo = 'Desconhecido';
            }
        } else {
            $textAgo = $diff['y'] . " Anos, " . $diff['m'] . " meses, " . $diff['d'] . " dias, " . $diff['h'] . " horas, " . $diff['m'] . " minutos e " . $diff['s'] . " segundos.";
        }

        return $textAgo;
    }

    public function diferenca($dataHora, $dataHoraAtual = NULL)
    {
        $formato = $this->getFormatoDataHora($dataHora);
        $dataAtual = (\is_null($dataHoraAtual) ? \date($formato) : $dataHoraAtual);

        $dI = \DateTime::createFromFormat($formato, $dataHora);
        $dF = \DateTime::createFromFormat($formato, $dataAtual);

        $diff = (array) $dI->diff($dF);

        return $diff;
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
        switch ($mes) {
            case 1:
                $m = 'Janeiro';
                break;
            case 2:
                $m = 'Fevereiro';
                break;
            case 3:
                $m = 'Março';
                break;
            case 4:
                $m = 'Abril';
                break;
            case 5:
                $m = 'Maio';
                break;
            case 6:
                $m = 'Junho';
                break;
            case 7:
                $m = 'Julho';
                break;
            case 8:
                $m = 'Agosto';
                break;
            case 9:
                $m = 'Setembro';
                break;
            case 10:
                $m = 'Outubro';
                break;
            case 11:
                $m = 'Novembro';
                break;
            case 12:
                $m = 'Dezembro';
                break;
            default:
                $m = 'Mês não informado';
                break;
        }

        return $m;
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
        throw new ErrorException("Método ainda não implementado.");
    }

    public function getAnosBissextosIntervalo($a1, $a2)
    {

        $anoBissexto = 0;
        for ($i = $a1; $i <= $a2; $i++) {
            if (\checkdate(2, 29, $i)) {
                $anoBissexto++;
            }
        }

        return $anoBissexto;
    }

    public function getDataPrevisaoDias($dias, $dataInicial = '')
    {

        if (empty($dataInicial)) {
            $dataInicial = \date('Y-m-d');
        }

        $arrayData = $this->getDataParse($dataInicial, 'Y-m-d');
        $preData = new \DateTime($arrayData['year'] . '-' . $arrayData['month'] . '-' . $arrayData['day']);
        if ($dias < 0) {
            $preData->sub(new \DateInterval('P' . ($dias * -1) . 'D'));
        } else {
            $preData->add(new \DateInterval('P' . $dias . 'D'));
        }

        return $preData->format('d/m/Y');
    }

    public function getDataParse($data, $format = "d/m/Y")
    {

        return \date_parse_from_format($format, $data);
    }

    public function getDataAddDays($dias, $data = '')
    {

        $arrayData = $this->getDataParse($data, 'd-m-Y');
        $preData = new \DateTime($arrayData['year'] . '-' . $arrayData['month'] . '-' . $arrayData['day']);
        $preData->add(new \DateInterval('P' . $dias . 'D'));
        return $preData->format('d/m/Y');
    }

    public function getIntervaloExtenso($dataInicial, $dataFinal = '', $showAnos = true, $showMeses = true, $showDias = true, $showComplementos = true)
    {

        return 'dia-mes-ano';
    }

    public function getDatasMinMax($arrayDatas, $acao = 'max')
    {

        foreach ($arrayDatas as $data) {
            $d = $this->getDataParse($data, 'd/m/Y');
            $v[] = $d['year'] . '-' . \str_pad($d['month'], 2, '0', \STR_PAD_LEFT) . '-' . \str_pad($d['day'], 2, '0', \STR_PAD_LEFT);
        }
        $r = ($acao === 'max') ? \max($v) : \min($v);
        return $this->converteData($r);
    }

    public function getDataComparacao($data1, $data2 = '', $oper = 'MaiorOuIgual')
    {

        $data1 = $this->getDataParse($data1, 'Y-m-d');
        $data2 = empty($data2) ? \date('Y-m-d') : $data2;
        $data2 = $this->getDataParse($data2, 'Y-m-d');

        if ($oper === 'MaiorOuIgual') {
            return(\mktime(0, 0, 0, $data1['month'], $data1['day'], $data1['year']) >= \mktime(0, 0, 0, $data2['month'], $data2['day'], $data2['year'])) ? true : false;
        } elseif ($oper === 'MenorOuIgual') {
            return(\mktime(0, 0, 0, $data1['month'], $data1['day'], $data1['year']) <= \mktime(0, 0, 0, $data2['month'], $data2['day'], $data2['year'])) ? true : false;
        } elseif ($oper === 'Maior') {
            return(\mktime(0, 0, 0, $data1['month'], $data1['day'], $data1['year']) > \mktime(0, 0, 0, $data2['month'], $data2['day'], $data2['year'])) ? true : false;
        } elseif ($oper === 'Menor') {
            return(\mktime(0, 0, 0, $data1['month'], $data1['day'], $data1['year']) < \mktime(0, 0, 0, $data2['month'], $data2['day'], $data2['year'])) ? true : false;
        } elseif ($oper === 'Igual') {
            return(\mktime(0, 0, 0, $data1['month'], $data1['day'], $data1['year']) == \mktime(0, 0, 0, $data2['month'], $data2['day'], $data2['year'])) ? true : false;
        }
    }

    public function getIntervaloDatasParaDias($dataInicio, $dataFim = '')
    {
        list($anoI, $mesI, $diaI) = preg_split('/[\/.-]/', $dataInicio);
        list($anoF, $mesF, $diaF) = preg_split('/[\/.-]/', ($dataFim ? $dataFim : \date('Y/m/d')));

        $mkTimeInicio = \mktime(0, 0, 0, $mesI, $diaI, $anoI);
        $mkTimeFim = \mktime(0, 0, 0, $mesF, $diaF, $anoF);

        if (($mkTimeFim - $mkTimeInicio) >= 0) {
            return \round(($mkTimeFim - $mkTimeInicio) / 86400);
        } else {
            return false;
        }
    }

    public function getDataParseManual($data, $format = 'dmY')
    {

        if ($format == 'dmY') {
            list($dia, $mes, $ano) = \split('[/.-]', $data);
        } else {
            list($ano, $mes, $dia) = \split('[/.-]', $data);
        }

        return ['dia' => $dia, 'mes' => $mes, 'ano' => $ano];
    }

    /*
     * $data = a data recebida sem separadores
     * $retorno = o formato esperado para o retorno podendo ser:
     * dmY, d/m/Y, d-m-Y, Ymd, Y/m/d, Y-m-d ou vazio para retornar ['dia' => 01, 'mes' => 12, 'ano' => 2015]
     */

    public function getDataSemSeparador($data, $retorno = '')
    {

        if (\strlen($data) === 8) {

            $d = \substr($data, 0, 2);
            $m = \substr($data, 2, 2);
            $a = \substr($data, 4, 4);

            if (\checkdate($m, $d, $a) and $a >= 1800) {

                $d = ['dia' => $d, 'mes' => $m, 'ano' => $a];
            } else {

                $a = \substr($data, 0, 4);
                $m = \substr($data, 4, 2);
                $d = \substr($data, 6, 2);

                if (\checkdate($m, $d, $a)) {

                    $d = ['dia' => $d, 'mes' => $m, 'ano' => $a];
                } else {

                    return false;
                }
            }
        } else {

            return false;
        }

        switch ($retorno) {

            case 'dmY':
                $d = $d['dia'] . $d['mes'] . $d['ano'];
                break;

            case 'd-m-Y':
                $d = $d['dia'] . '-' . $d['mes'] . '-' . $d['ano'];
                break;

            case 'd/m/Y':
                $d = $d['dia'] . '/' . $d['mes'] . '/' . $d['ano'];
                break;

            case 'Ymd':
                $d = $d['ano'] . $d['mes'] . $d['dia'];
                break;

            case 'Y/m/d':
                $d = $d['ano'] . '/' . $d['mes'] . '/' . $d['dia'];
                break;

            case 'Y-m-d':
                $d = $d['ano'] . '-' . $d['mes'] . '-' . $d['dia'];
                break;

            default:

                $d = ['dia' => $d['dia'], 'mes' => $m, 'ano' => $a];
                break;
        }

        return $d;
    }

}
