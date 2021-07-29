<?php

namespace Zion\Boleto\BoletoPHP;

use Zion\Tratamento\Tratamento;

class SafraClass
{

    protected $urlImg = SIS_URL_BASE . 'Financeiro/Movimentacao/Parcela/Boleto/Tema/Vendor/Pixel/1.3.0/views/img/';
    protected $tratamento;

    public function __construct()
    {
        $this->tratamento = Tratamento::instancia();
    }

    public function getDadosBoleto($dadosParcela, $parametrizacao)
    {
        $dadosboleto = [];
        $codigobanco = $parametrizacao['fnc_convenio_banco'];
        $dvSafra = '7';
        $codigoBancoComDv = '422-' . $dvSafra;
        $nummoeda = '9';

        $fatorVencimento = $this->fatorVencimento($this->tratamento->data()->converteData($dadosParcela["fnc_parcela_vencimento"]));

        $valorParcela = str_replace('.', ',', $dadosParcela["fnc_parcela_valor"]);
        $valor = $this->formataNumero($valorParcela, 10, 0, "valor");

        $agencia = $this->formataNumero($parametrizacao["fnc_convenio_agencia"], 4, 0);
        $agenciaCodBar = $this->formataNumero($parametrizacao["fnc_convenio_agencia"].$parametrizacao["fnc_convenio_agencia_dv"], 5, 0);

        $conta = $this->formataNumero($parametrizacao["fnc_convenio_conta"].$parametrizacao["fnc_convenio_conta_dv"], 9, 0);

        $nnumComDv = $this->formataNumero($dadosParcela["fnc_parcela_nosso_numero"], 9, 0);

        $tipoCobranca = 2;

        $nossonumero = substr($nnumComDv, 0, 8) . '-' . substr($nnumComDv, 8, 1);
        $agenciaCodigo = $agencia .$parametrizacao["fnc_convenio_agencia_dv"]. " / " . $this->formataNumero($parametrizacao["fnc_convenio_conta"].$parametrizacao["fnc_convenio_conta_dv"],9,0);

        $dadosboleto["codigo_barras"] = $this->fBarcode($this->geraCodigoDeBarras($codigobanco, $dvSafra, $nummoeda, $fatorVencimento, $nnumComDv, $agenciaCodBar, $conta, $tipoCobranca, $valor));
        $dadosboleto["linha_digitavel"] = $this->geraLinhaDigitavel($codigobanco, $dvSafra, $nummoeda, $fatorVencimento, $nnumComDv, $agenciaCodBar, $conta, $tipoCobranca, $valor);
        $dadosboleto["agencia_codigo"] = $agenciaCodigo;
        $dadosboleto["nosso_numero"] = $nossonumero;
        $dadosboleto["codigo_banco_com_dv"] = $codigoBancoComDv;

        return $dadosboleto;
    }

    private function formataNumero($numero, $loop, $insert, $tipo = "geral")
    {
        if ($tipo == "geral") {
            $numero = str_replace(",", "", $numero);
            while (strlen($numero) < $loop) {
                $numero = $insert . $numero;
            }
        }
        if ($tipo == "valor") {
            $numero = str_replace(",", "", $numero);
            while (strlen($numero) < $loop) {
                $numero = $insert . $numero;
            }
        }
        if ($tipo == "convenio") {
            while (strlen($numero) < $loop) {
                $numero = $numero . $insert;
            }
        }
        return $numero;
    }

    private function fatorVencimento($dataBoleto)
    {
        $data = explode("/", $dataBoleto);
        $ano = $data[2];
        $mes = $data[1];
        $dia = $data[0];
        return(abs(($this->dateToDays("1997", "10", "07")) - ($this->dateToDays($ano, $mes, $dia))));
    }

    private function fBarcode($valor)
    {

        $buffer = '';
        $fino = 1;
        $largo = 3;
        $altura = 50;

        $barcodes[0] = "00110";
        $barcodes[1] = "10001";
        $barcodes[2] = "01001";
        $barcodes[3] = "11000";
        $barcodes[4] = "00101";
        $barcodes[5] = "10100";
        $barcodes[6] = "01100";
        $barcodes[7] = "00011";
        $barcodes[8] = "10010";
        $barcodes[9] = "01010";
        for ($f1 = 9; $f1 >= 0; $f1--) {
            for ($f2 = 9; $f2 >= 0; $f2--) {
                $f = ($f1 * 10) + $f2;
                $texto = "";
                for ($i = 1; $i < 6; $i++) {
                    $texto .= substr($barcodes[$f1], ($i - 1), 1) . substr($barcodes[$f2], ($i - 1), 1);
                }
                $barcodes[$f] = $texto;
            }
        }


        //Desenho da barra
        //Guarda inicial
        $buffer .= '<img src=' . $this->urlImg . 'p.png width=' . $fino . ' height=' . $altura . ' border=0>';
        $buffer .= '<img src=' . $this->urlImg . 'b.png width=' . $fino . ' height=' . $altura . ' border=0>';
        $buffer .= '<img src=' . $this->urlImg . 'p.png width=' . $fino . ' height=' . $altura . ' border=0>';
        $buffer .= '<img src=' . $this->urlImg . 'b.png width=' . $fino . ' height=' . $altura . ' border=0>';
        $buffer .= '<img ';

        $texto = $valor;
        if ((strlen($texto) % 2) <> 0) {
            $texto = "0" . $texto;
        }

        // Draw dos dados
        while (strlen($texto) > 0) {
            $i = round($this->esquerda($texto, 2));
            $texto = $this->direita($texto, strlen($texto) - 2);
            $f = $barcodes[$i];
            for ($i = 1; $i < 11; $i += 2) {
                if (substr($f, ($i - 1), 1) == "0") {
                    $f1 = $fino;
                } else {
                    $f1 = $largo;
                }
                $buffer .= ' src=' . $this->urlImg . 'p.png width=' . $f1 . ' height=' . $altura . ' border=0>';
                $buffer .= '<img ';
                if (substr($f, $i, 1) == "0") {
                    $f2 = $fino;
                } else {
                    $f2 = $largo;
                }
                $buffer .= ' src=' . $this->urlImg . 'b.png width=' . $f2 . ' height=' . $altura . ' border=0>';
                $buffer .= '<img ';
            }
        }

        // Draw guarda final
        $buffer .= ' src=' . $this->urlImg . 'p.png width=' . $largo . ' height=' . $altura . ' border=0>';
        $buffer .= '<img src=' . $this->urlImg . 'b.png width=' . $fino . ' height=' . $altura . ' border=0>';
        $buffer .= '<img src=' . $this->urlImg . 'p.png width=1 height=' . $altura . ' border=0>';

        return $buffer;
    }

    private function esquerda($entra, $comp)
    {
        return substr($entra, 0, $comp);
    }

    private function direita($entra, $comp)
    {
        return substr($entra, strlen($entra) - $comp, $comp);
    }

    private function dateToDays($year, $month, $day)
    {
        $century = substr($year, 0, 2);
        $year = substr($year, 2, 2);
        if ($month > 2) {
            $month -= 3;
        } else {
            $month += 9;
            if ($year) {
                $year--;
            } else {
                $year = 99;
                $century --;
            }
        }
        return ( floor(( 146097 * $century) / 4) +
            floor(( 1461 * $year) / 4) +
            floor(( 153 * $month + 2) / 5) +
            $day + 1721119);
    }

    function modulo10($cod, $p = 0)
    {
        $mod10 = 0;
        $mod = "";
        $i = 0;
        do {
            if (($i + $p) % 2 == 0) {
                $val = intval($cod[$i]);
                $val = $val * 2;
                $mod = $mod . (string) $val;
            } else {
                $mod = $mod . $cod[$i];
            }
            $i++;
        } while ($i < strlen($cod));
        $i = 0;
        do {
            $mod10 = $mod10 + intval($mod[$i]);
            $i++;
        } while ($i < strlen($mod));
        $mod10 = $mod10 % 10;
        $mod10 = 10 - $mod10;
        if ($mod10 == 10) {
            $mod10 = 0;
        }
        return $mod10;
    }

    /* Este método calcula o modulo 11 de um código ($cod). */

    private function modulo11($cod)
    {
        $i = 2;
        $c = strlen($cod) - 1;
        $mod11 = 0;
        do {
            if ($i == 10) {
                $i = 2;
            }
            $mod11 += intval($cod[$c]) * $i;
            $i++;
            $c--;
        } while ($c >= 0);
        $mod11 %= 11;
        if (11 - $mod11 == 0 || 11 - $mod11 == 11 | 11 - $mod11 == 10) {
            return 1;
        }
        return 11 - $mod11;
    }

    private function geraLinhaDigitavel($banco, $dvBanco, $moeda, $vencimento, $nossoNumero, $agencia, $conta, $tipoCobranca, $valor)
    {
        $ldSemDv = $banco . $moeda . $dvBanco . $agencia . $conta . $nossoNumero . $tipoCobranca . $vencimento . $valor;
        $codBarrasSemDac = $banco . $moeda . $vencimento . $valor . $dvBanco . $agencia . $conta . $nossoNumero . $tipoCobranca;

        $g1 = substr($ldSemDv, 0, 9);
        $g2 = substr($ldSemDv, 9, 10);
        $g3 = substr($ldSemDv, 19, 10);
        $g4 = $vencimento . $valor;
        $dvG1 = $this->modulo10($g1, 0);
        $dvG2 = $this->modulo10($g2, 1);
        $dvG3 = $this->modulo10($g3, 1);
        $dac = $this->modulo11($codBarrasSemDac);

        $linhaDigitavel = $g1 . $dvG1 . $g2 . $dvG2 . $g3 . $dvG3 . $dac . $g4;

        return substr($linhaDigitavel, 0, 5)
            . '.' . substr($linhaDigitavel, 5, 5)
            . ' ' . substr($linhaDigitavel, 10, 5)
            . '.' . substr($linhaDigitavel, 15, 6)
            . ' ' . substr($linhaDigitavel, 21, 5)
            . '.' . substr($linhaDigitavel, 26, 6)
            . ' ' . substr($linhaDigitavel, 32, 1)
            . ' ' . substr($linhaDigitavel, 33, 14);
    }

    private function geraCodigoDeBarras($banco, $dvBanco, $moeda, $vencimento, $nossoNumero, $agencia, $conta, $tipoCobranca, $valor)
    {
        $codBarrasSemDac = $banco . $moeda . $vencimento . $valor . $dvBanco . $agencia . $conta . $nossoNumero . $tipoCobranca;

        $dac = $this->modulo11($codBarrasSemDac);

        return $banco . $moeda . $dac . $vencimento . $valor . $dvBanco . $agencia . $conta . $nossoNumero . $tipoCobranca;
    }

}
