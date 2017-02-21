<?php

namespace Zion\Tratamento;

use Zion\Tratamento\Tratamento;

class Texto
{

    /**
     * @var array $keyWords Keywords do MySql a serem encontradas
     */
    public $keyWords = array("/SELECT/", "/INSERT/", "/UPDATE/", "/DELETE/", "/DROP/", "/ALTER/", "/ADD/", "/TABLE/", "/IF/", "/AND/", "/WHERE/", "/GROUP/", "/LIMIT/",
        "/JOIN/", "/IN/", "/INTO/", "/PROCEDURE/", "/WHILE/", "/WHEN/", "/THEN/", "/CASE/", "/LIKE/", "/KILL/", "/INSTR/");

    /**
     * @var array $safekeyWords Keywords do MySql a serem utilizadas
     */
    public $safekeyWords = array("\SELECT", "\INSERT", "\UPDATE", "\DELETE", "\DROP", "\ALTER", "\ADD", "\TABLE", "\IF", "\AND", "\WHERE", "\GROUP", "\LIMIT",
        "\JOIN", "\IN", "\INTO", "\PROCEDURE", "\WHILE", "\WHEN", "\THEN", "\CASE", "\LIKE", "\KILL", "\INSTR");
    private static $instancia;
    private $trata;

    private function __construct()
    {
        
    }

    /**
     * Texto::instancia()
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
     * Texto::converterTextoHtml()
     * Converte um texto com caracteres especias em caracteres HTML.
     * 
     * @param String $texto Texto a ser convertido. 
     * @return String Texto convertido para HTML.
     */
    public function converterTextoHtml($texto)
    {

        return \htmlspecialchars($texto, \ENT_QUOTES);
    }

    /**
     * Texto::converterHtmlTexto()
     * Converte um texto com caracteres HTML em caracteres especias. Inverso de Texto::converterTextoHtml(var).
     * 
     * @param String $texto Texto a ser decodificado.
     * @return String Texto decodificado.
     */
    public function converterHtmlTexto($texto)
    {
        return $this->converterTextoHtml($texto);
    }

    /**
     * Texto::limitaTexto()
     * Trunca um texto de acordo com os parâmetros de inicio e comprimento.
     * 
     * @param mixed $texto Texto a ser truncado.
     * @param bool $start Posição do texto para início da truncagem.
     * @param bool $length Comprimento do texto a partir do início da truncagem.
     * @return String Texto resultante após a truncagem.
     */
    public function limitaTexto($texto, $start = false, $length = false)
    {
        return \substr($texto, $start, $length);
    }

    /**
     * Texto::removerAcentos()
     * Substitui os caracteres com acento e cedilha de um texto, pelos seus equivalentes sem caracteres especiais.
     * 
     * @param String $texto Texto a ser tratado.
     * @return Texto com caracteres sem acentos e cedilha.
     */
    public function removerAcentos($texto)
    {

        $especiais = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
        $normais = "aaaaeeiooouucAAAAEEIOOOUUC";

        $keys = array();
        $values = array();

        \preg_match_all('/./u', $especiais, $keys);
        \preg_match_all('/./u', $normais, $values);

        $mapping = \array_combine($keys[0], $values[0]);

        return \strtr($texto, $mapping);
    }

    /**
     * Texto::converterIsoUtf8()
     * Converte um texto em codificação ISO-8859-1 para UTF-8.
     * 
     * @param String $texto Texto em codificação ISO-8859-1.
     * @return String Texto em codificação UTF-8.
     */
    public function converterIsoUtf8($texto)
    {
        return \utf8_encode($texto);
    }

    /**
     * Texto::converterUtf8Iso()
     * Converte um texto em codificação UTF-8 para ISO-8859-1. Inverso de Texto::converterIsoUtf8(var);
     * 
     * @param String $texto Texto em codificação UTF-8.
     * @return Texto em codificação ISO-8859-1.
     */
    public function converterUtf8Iso($texto)
    {
        return \utf8_decode($texto);
    }

    /**
     * Texto::removeFormatacao()
     * Remove a formatação de um texto, como quebras de linha(\n) e tabs(\t).
     * 
     * @param String $texto Texto com formatação.
     * @return String Texto sem formatação.
     */
    public function removeFormatacao($texto)
    {
        return \preg_replace('/[\t\n\r\f\v]/', '', $texto);
    }

    /**
     * Texto::removerEspacos()
     * Remove todos os espaços(\s\t) de um texto.
     * 
     * @param String $texto Texto a ser tratado.
     * @return String Texto sem espaços.
     */
    public function removerEspacos($texto)
    {
        return \preg_replace('/[\s\t]/', '', $texto);
    }

    /**
     * Texto::trata()
     * Trata um texto para prevenção de Injections, escapando aspas e keywords do Mysql.
     * 
     * @param String $texto Texto a ser tratado.
     * @return String Texto tratado e seguro para inserção no Banco de Dados.
     */
    public function trata($texto)
    {

        $textoTratado = $texto;

        if (\preg_match('/["\']/', $texto)) {
            $textoTratado = $this->escapaAspas($texto);
        }

        return \preg_replace($this->keyWords, $this->safekeyWords, $textoTratado);
    }

    /**
     * Texto::desTrata()
     * Remove os caracteres de escape de um texto tratado. Inverso de Texto::trata(var).
     * 
     * @param String $texto Texto escapado a ser tratado.
     * @return String Texto sem caracteres de escape.
     */
    public function desTrata($texto)
    {
        return \stripslashes($texto);
    }

    /**
     * Texto::removeAspas()
     * Remove as aspas('' e "") de um texto.
     * 
     * @param String $texto Texto a ser tratado.
     * @return String texto sem aspas.
     */
    public function removeAspas($texto)
    {
        return \preg_replace('/[\'"]/', '', $texto);
    }

    /**
     * Texto::escapaAspas()
     * Escapa as aspas('' e "") de um texto, adcionando um caractere de escape(\).
     * 
     * @param String $texto Texto a ser tratado.
     * @return String Texto com aspas escapadas(\' e \");
     */
    public function escapaAspas($texto)
    {
        return \addslashes($texto);
    }

    /**
     * Texto::parteString()
     * Retorna um array com as posições inicial e final de uma string.
     * 
     * @param String $texto Texto a ser tratado.
     * @return Array com as posiçoes start e end;
     */
    public function parteString($texto, $explode = ' ')
    {

        $str = \explode($explode, $texto);
        $t = \count($str) - 1;
        return ['start' => $str[0], 'end' => $str[$t]];
    }

    public function substituaPor($subs, $dados)
    {
        $substituidos = $dados;

        foreach ($dados as $key => $value) {

            if (\is_array($subs) and \array_key_exists($key, $subs)) {

                if (\array_key_exists($value, $subs[$key])) {
                    $substituidos[$key] = $subs[$key][$value];
                } else {
                    if ($value == '') {
                        $substituidos[$key] = \current($subs[$value]);
                    }
                }
            }
        }
        return $substituidos;
    }

    public function tratarComo($formatos, $dados)
    {
        $formatados = $dados;
        $this->trata = Tratamento::instancia();

        if (\is_array($formatos)) {
            foreach ($dados as $key => $value) {
                if (\array_key_exists($key, $formatos)) {

                    $como = \strtoupper($formatos[$key]);

                    switch ($como) {
                        case "DATA" : $formatados[$key] = \substr($this->trata->data()->converteData($value), 0, 10);
                            break;
                        case "DATAHORA": $formatados[$key] = \substr($this->trata->data()->converteDataHora($value), false, -3);
                            break;
                        case "NUMERO" : $formatados[$key] = $this->trata->numero()->floatCliente($value);
                            break;
                        case "MOEDA" : $formatados[$key] = $this->trata->numero()->moedaCliente($value);
                            break;
                        case "TIMEAGO" : $formatados[$key] = $this->trata->data()->getTimeAgo($value);
                            break;
                    }
                }
            }
        }

        return $formatados;
    }

    public function passaTratamento($dados, $tratamentos)
    {

        $tratados = $dados;
        $this->trata = Tratamento::instancia();

        foreach ($dados as $k => $linha) {

            foreach ($linha as $key => $val) {

                if (isset($tratamentos['SUBSTITUA']) and \array_key_exists($key, $tratamentos['SUBSTITUA'])) {

                    $tratados[$k] = $this->substituaPor($tratamentos['SUBSTITUA'], $tratados[$k]);
                } elseif (isset($tratamentos['TRATA']['DATA']) and \in_array($key, $tratamentos['TRATA']['DATA'])) {

                    $tratados[$k] = $this->tratarComo([$key => 'DATA'], $tratados[$k]);
                } elseif (isset($tratamentos['TRATA']['DATAHORA']) and \in_array($key, $tratamentos['TRATA']['DATAHORA'])) {

                    $tratados[$k] = $this->tratarComo([$key => 'DATAHORA'], $tratados[$k]);
                } elseif (isset($tratamentos['TRATA']['DATAEXT']) and \in_array($key, $tratamentos['TRATA']['DATAEXT'])) {

                    $tratados[$k][$key] = $this->trata->data()->getIntervaloExtenso($val);
                } elseif (isset($tratamentos['TRATA']['TIMEAGO']) and \in_array($key, $tratamentos['TRATA']['TIMEAGO'])) {

                    $tratados[$k][$key] = $this->trata->data()->getTimeAgo($val);
                }
            }
        }

        return $tratados;
    }

}
