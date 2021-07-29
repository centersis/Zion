<?php

namespace Centersis\Zion\Boleto\BoletoPHP;

use Centersis\Zion\Tratamento\Tratamento;

class SicrediClass
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
        $codigoBancoComDv = '748-X';
        $nummoeda = '9';

        $fatorVencimento = $this->fatorVencimento($this->tratamento->data()->converteData($dadosParcela["fnc_parcela_vencimento"]));

        $valorParcela = str_replace('.', ',', $dadosParcela["fnc_parcela_valor"]);
        $valor = $this->formataNumero($valorParcela, 10, 0, "valor");

        $agencia = $this->formataNumero($parametrizacao["fnc_convenio_agencia"], 4, 0);

        $posto = $this->formataNumero($parametrizacao["fnc_convenio_posto"], 2, 0);

        $conta = $this->formataNumero($parametrizacao["fnc_convenio_conta"], 5, 0);

        //fillers - zeros Obs: filler1 contera 1 quando houver valor expresso no campo valor
        $filler1 = 1;
        $filler2 = 0;

        $tipoCobranca = 1;

        $tipoCarteira = $parametrizacao["fnc_convenio_carteira"];

        $nnumComDv = $dadosParcela["fnc_parcela_nosso_numero"];

        $campoLivre = "$tipoCobranca$tipoCarteira$nnumComDv$agencia$posto$conta$filler1$filler2";
        $campoLivreDv = $campoLivre . $this->digitoVerificadorCampoLivre($campoLivre);

        $dv = $this->digitoVerificadorBarra("$codigobanco$nummoeda$fatorVencimento$valor$campoLivreDv");

        $linha = "$codigobanco$nummoeda$dv$fatorVencimento$valor$campoLivreDv";

        $nossonumero = substr($nnumComDv, 0, 2) . '/' . substr($nnumComDv, 2, 6) . '-' . substr($nnumComDv, 8, 1);
        $agenciaCodigo = $agencia . "." . $posto . "." . $conta;

        $dadosboleto["codigo_barras"] = $this->fBarcode($linha);
        $dadosboleto["linha_digitavel"] = $this->montaLinhaDigitavel($linha);
        $dadosboleto["agencia_codigo"] = $agenciaCodigo;
        $dadosboleto["nosso_numero"] = $nossonumero;
        $dadosboleto["codigo_banco_com_dv"] = $codigoBancoComDv;
        $dadosboleto["campo_livre"] = $campoLivreDv;

        return $dadosboleto;
    }

    public function digitoVerificadorNossoNumero($numero)
    {
        $resto2 = $this->modulo11($numero, 9, 1);

        $digito = 11 - $resto2;
        if ($digito > 9) {
            return 0;
        } else {
            return $digito;
        }
    }

    private function digitoVerificadorCampoLivre($numero)
    {
        $resto2 = $this->modulo11($numero, 9, 1);

        if ($resto2 <= 1) {
            return 0;
        } else {
            return (11 - $resto2);
        }
    }

    private function digitoVerificadorBarra($numero)
    {
        $resto2 = $this->modulo11($numero, 9, 1);
        $digito = 11 - $resto2;

        if ($digito <= 1 || $digito >= 10) {
            return 1;
        } else {
            return $digito;
        }
    }

    private function modulo10($num)
    {
        $numtotal10 = 0;
        $fator = 2;

        for ($i = strlen($num); $i > 0; $i--) {
            $numeros[$i] = substr($num, $i - 1, 1);
            $temp = $numeros[$i] * $fator;
            $temp0 = 0;
            foreach (preg_split('//', $temp, -1, PREG_SPLIT_NO_EMPTY) as $k => $v) {
                $temp0 += $v;
            }
            $parcial10[$i] = $temp0; //$numeros[$i] * $fator;
            $numtotal10 += $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            } else {
                $fator = 2; // intercala fator de multiplicacao (modulo 10)
            }
        }

        $resto = $numtotal10 % 10;
        $digito = 10 - $resto;
        if ($resto == 0) {
            $digito = 0;
        }

        return $digito;
    }

    private function modulo11($num, $base = 9, $r = 0)
    {
        $soma = 0;
        $fator = 2;

        for ($i = strlen($num); $i > 0; $i--) {

            $numeros[$i] = substr($num, $i - 1, 1);
            $parcial[$i] = $numeros[$i] * $fator;
            $soma += $parcial[$i];

            if ($fator == $base) {
                $fator = 1;
            }

            $fator++;
        }

        if ($r == 0) {
            $soma *= 10;
            $digito = $soma % 11;
            return $digito;
        } elseif ($r == 1) {
            $rDiv = (int) ($soma / 11);
            $digito = ($soma - ($rDiv * 11));
            return $digito;
        }
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

    private function montaLinhaDigitavel($codigo)
    {

        $p1 = substr($codigo, 0, 4);
        $p2 = substr($codigo, 19, 5);
        $p3 = $this->modulo10("$p1$p2");
        $p4 = "$p1$p2$p3";
        $p5 = substr($p4, 0, 5);
        $p6 = substr($p4, 5);
        $campo1 = "$p5.$p6";

        $p1 = substr($codigo, 24, 10);
        $p2 = $this->modulo10($p1);
        $p3 = "$p1$p2";
        $p4 = substr($p3, 0, 5);
        $p5 = substr($p3, 5);
        $campo2 = "$p4.$p5";

        $p1 = substr($codigo, 34, 10);
        $p2 = $this->modulo10($p1);
        $p3 = "$p1$p2";
        $p4 = substr($p3, 0, 5);
        $p5 = substr($p3, 5);
        $campo3 = "$p4.$p5";

        $campo4 = substr($codigo, 4, 1);

        $p1 = substr($codigo, 5, 4);
        $p2 = substr($codigo, 9, 10);
        $campo5 = "$p1$p2";

        return "$campo1 $campo2 $campo3 $campo4 $campo5";
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

}
