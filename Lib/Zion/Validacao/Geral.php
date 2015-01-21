<?php
/*

    Sappiens Framework
    Copyright (C) 2014, BRA Consultoria

    Website do autor: www.braconsultoria.com.br/sappiens
    Email do autor: sappiens@braconsultoria.com.br

    Website do projeto, equipe e documentação: www.sappiens.com.br
   
    Este programa é software livre; você pode redistribuí-lo e/ou
    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
    publicada pela Free Software Foundation, versão 2.

    Este programa é distribuído na expectativa de ser útil, mas SEM
    QUALQUER GARANTIA; sem mesmo a garantia implícita de
    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
    detalhes.
 
    Você deve ter recebido uma cópia da Licença Pública Geral GNU
    junto com este programa; se não, escreva para a Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
    02111-1307, USA.

    Cópias da licença disponíveis em /Sappiens/_doc/licenca

*/

/**
 * @author Feliphe "O Retaliador" Bueno - feliphezion@gmail.com
 * @since 24/9/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Validação de inputs específicamente Brasileiros.
 * 
 */

namespace Zion\Validacao;

class Geral extends \Zion\Tratamento\Geral
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
    private function __construct()
    {
        
    }

    /**
     * Geral::instancia()
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
     * Geral::validaCPF()
     * 
     * @param mixed $cpf
     * @return
     */
    public function validaCPF($cpf)
    {
        $invalidos = [];

        for ($c = 0; $c < 10; $c++) {
            $invalidos[] = \str_repeat($c, 11);
        }

        $cpfLimpo = \str_replace(['.', '-'], '', $cpf);        

        if (\strlen($cpfLimpo) <> 11 or \in_array($cpfLimpo, $invalidos) or ! \is_numeric($cpfLimpo)) {

            return false;
        }

        $dvInformado = \substr($cpfLimpo, 9, 2);

        $digito = [];
        for ($i = 0; $i <= 8; $i++) {
            $digito[$i] = \substr($cpfLimpo, $i, 1);
        }

        $posicao1 = 10;
        $soma1 = 0;

        for ($i = 0; $i <= 8; $i++) {
            $soma1 += ($digito[$i] * $posicao1);
            $posicao1 = $posicao1 - 1;
        }

        $digito[9] = $soma1 % 11;

        if ($digito[9] < 2) {
            $digito[9] = 0;
        } else {
            $digito[9] = 11 - $digito[9];
        }

        $posicao2 = 11;
        $soma2 = 0;

        for ($i = 0; $i <= 9; $i++) {
            $soma2 += ($digito[$i] * $posicao2);
            $posicao2 = $posicao2 - 1;
        }

        $digito[10] = $soma2 % 11;

        if ($digito[10] < 2) {
            $digito[10] = 0;
        } else {
            $digito[10] = 11 - $digito[10];
        }

        $dv = $digito[9] * 10 + $digito[10];

        return($dv != $dvInformado) ? false : true;
    }

    /**
     * Geral::validaCNPJ()
     * 
     * @param mixed $cnpj
     * @return
     */
    public function validaCNPJ($cnpj)
    {

        $j = 0;
        for ($i = 0; $i < (strlen($cnpj)); $i++) {
            if (is_numeric($cnpj[$i])) {
                $num[$j] = $cnpj[$i];
                $j++;
            }
        }

        if (count($num) != 14) {
            $isCnpjValid = false;
        }

        if (array_sum($num) == 0) {
            $isCnpjValid = false;
        } else {
            $j = 5;
            for ($i = 0; $i < 4; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 4; $i < 12; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $resto = $soma % 11;
            if ($resto < 2) {
                $dg = 0;
            } else {
                $dg = 11 - $resto;
            }

            if ($dg != $num[12]) {
                $isCnpjValid = false;
            }
        }

        if (!isset($isCnpjValid)) {
            $j = 6;
            for ($i = 0; $i < 5; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 5; $i < 13; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $resto = $soma % 11;
            if ($resto < 2) {
                $dg = 0;
            } else {
                $dg = 11 - $resto;
            }
            if ($dg != $num[13]) {
                $isCnpjValid = false;
            } else {
                $isCnpjValid = true;
            }
        }

        return $isCnpjValid;
    }

    /**
     * Geral::validaCEP()
     * 
     * @param mixed $cep
     * @return
     */
    public function validaCEP($cep)
    {
        if (preg_match('/^\d{2}.\d{3}|\d{5}[-|\s]?[0-9]{3}$|^[0-9]{8}$/', $cep, $matches, PREG_OFFSET_CAPTURE)) {

            $cepValido = (int) preg_replace('/[^0-9]/', '', $cep);
            return($cepValido > 0 ? true : false);
        }
    }

    /**
     * Geral::validaTelefone()
     * 
     * @param string $telefone
     * @return void
     * @throws RuntimeException Método ainda não implementado.
     */
    public function validaTelefone($telefone)
    {
        throw new RuntimeException("Metodo ainda nao implementado.");
    }

}
