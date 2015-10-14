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

        $dataLimpa = \str_replace(['/', '-'], '', $data);

        if ($dataLimpa === '00000000') {
            return false;
        }

        $f = $this->getFormatoDataHora($data);
        $date = \DateTime::createFromFormat($f, $data);

        return ($date ? true : false);
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
        if ($this->validaDataHora($dataI) === false or $this->validaDataHora($dataF) === false) {
            return false;
        }

        $dI = \DateTime::createFromFormat($this->getFormatoDataHora($dataI), $dataI);
        $dF = \DateTime::createFromFormat($this->getFormatoDataHora($dataF), $dataF);

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
