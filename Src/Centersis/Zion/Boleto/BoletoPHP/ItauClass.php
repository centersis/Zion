<?php

namespace Centersis\Zion\Boleto\BoletoPHP;

use Centersis\Zion\Tratamento\Tratamento;

class ItauClass
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
        $codigoBancoComDv = '341-7';
        $nummoeda = '9';

        $fatorVencimento = $this->fatorVencimento($this->tratamento->data()->converteData($dadosParcela["fnc_parcela_vencimento"]));

        //valor tem 10 digitos, sem virgula
        $valorParcela = str_replace('.', ',', $dadosParcela["fnc_parcela_valor"]);
        $valor = $this->formataNumero($valorParcela, 10, 0, "valor");

        //carteira
        $carteira = $parametrizacao['fnc_convenio_carteira'];

        //nosso número
        $nnum = $this->formataNumero($dadosParcela["fnc_parcela_nosso_numero"], 8, 0);

        //agencia é sempre 4 digitos
        $agencia = $this->formataNumero($parametrizacao["fnc_convenio_agencia"], 4, 0);

        //conta
        $conta = $this->formataNumero($parametrizacao["fnc_convenio_conta"], 6, 0);

        $codigoBarras = $codigobanco . $nummoeda . $fatorVencimento . $valor . $carteira . $nnum . $this->modulo10($agencia . $conta . $carteira . $nnum) . $agencia . $conta . $this->modulo10($agencia . $conta) . '000';

        $dv = $this->digitoVerificadorBarra($codigoBarras);

        $linha = substr($codigoBarras, 0, 4) . $dv . substr($codigoBarras, 4, 43);

        $nossonumero = $carteira . '/' . $nnum . '-' . $this->modulo10($agencia . $conta . $carteira . $nnum);
        $agenciaCodigo = $agencia . " / " . $conta . "-" . $this->modulo10($agencia . $conta);

        $dadosboleto["codigo_barras"] = $this->fBarcode($linha);
        $dadosboleto["linha_digitavel"] = $this->montaLinhaDigitavel($linha);
        $dadosboleto["agencia_codigo"] = $agenciaCodigo;
        $dadosboleto["nosso_numero"] = $nossonumero;
        $dadosboleto["codigo_banco_com_dv"] = $codigoBancoComDv;

        return $dadosboleto;
    }

    private function digitoVerificadorBarra($barra)
    {
        $resto2 = $this->modulo11($barra, 9, 1);
        if ($resto2 == 0 || $resto2 == 1 || $resto2 == 10) {
            $dv = 1;
        } else {
            $dv = 11 - $resto2;
        }
        return $dv;
    }

    public function formataNumero($numero, $loop, $insert, $tipo = "geral")
    {
        if ($tipo == "geral") {
            $numero = str_replace(",", "", $numero);
            while (strlen($numero) < $loop) {
                $numero = $insert . $numero;
            }
        }
        if ($tipo == "valor") {
            /*
              retira as virgulas
              formata o numero
              preenche com zeros
             */
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
        $buffer.='<img src=' . $this->urlImg . 'p.png width=' . $fino . ' height=' . $altura . ' border=0>';
        $buffer.='<img src=' . $this->urlImg . 'b.png width=' . $fino . ' height=' . $altura . ' border=0>';
        $buffer.='<img src=' . $this->urlImg . 'p.png width=' . $fino . ' height=' . $altura . ' border=0>';
        $buffer.='<img src=' . $this->urlImg . 'b.png width=' . $fino . ' height=' . $altura . ' border=0>';
        $buffer.='<img ';

        $texto = $valor;
        if ((strlen($texto) % 2) <> 0) {
            $texto = "0" . $texto;
        }

        // Draw dos dados
        while (strlen($texto) > 0) {
            $i = round($this->esquerda($texto, 2));
            $texto = $this->direita($texto, strlen($texto) - 2);
            $f = $barcodes[$i];
            for ($i = 1; $i < 11; $i+=2) {
                if (substr($f, ($i - 1), 1) == "0") {
                    $f1 = $fino;
                } else {
                    $f1 = $largo;
                }
                $buffer.=' src=' . $this->urlImg . 'p.png width=' . $f1 . ' height=' . $altura . ' border=0>';
                $buffer.='<img ';
                if (substr($f, $i, 1) == "0") {
                    $f2 = $fino;
                } else {
                    $f2 = $largo;
                }
                $buffer.=' src=' . $this->urlImg . 'b.png width=' . $f2 . ' height=' . $altura . ' border=0>';
                $buffer.='<img ';
            }
        }

        // Draw guarda final
        $buffer.=' src=' . $this->urlImg . 'p.png width=' . $largo . ' height=' . $altura . ' border=0>';
        $buffer.='<img src=' . $this->urlImg . 'b.png width=' . $fino . ' height=' . $altura . ' border=0>';
        $buffer.='<img src=' . $this->urlImg . 'p.png width=1 height=' . $altura . ' border=0>';

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

    private function fatorVencimento($dataBR)
    {
        $data = preg_split('/\/|-/', $dataBR);
        $ano = $data[2];
        $mes = $data[1];
        $dia = $data[0];
        return(abs(($this->dataParaDias("1997", "10", "07")) - ($this->dataParaDias($ano, $mes, $dia))));
    }

    private function dataParaDias($ano, $mes, $dia)
    {
        $century = substr($ano, 0, 2);
        $year = substr($ano, 2, 2);
        if ($mes > 2) {
            $mes -= 3;
        } else {
            $mes += 9;
            if ($year) {
                $year--;
            } else {
                $year = 99;
                $century --;
            }
        }

        return ( floor(( 146097 * $century) / 4) +
            floor(( 1461 * $year) / 4) +
            floor(( 153 * $mes + 2) / 5) +
            $dia + 1721119);
    }

    private function modulo10($num)
    {
        $numtotal10 = 0;
        $fator = 2;

        // Separacao dos numeros
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num, $i - 1, 1);
            // Efetua multiplicacao do numero pelo (falor 10)
            // 2002-07-07 01:33:34 Macete para adequar ao Mod10 do Itaú
            $temp = $numeros[$i] * $fator;
            $temp0 = 0;
            foreach (preg_split('//', $temp, -1, PREG_SPLIT_NO_EMPTY) as $k => $v) {
                $temp0+=$v;
            }
            $parcial10[$i] = $temp0; //$numeros[$i] * $fator;
            // monta sequencia para soma dos digitos no (modulo 10)
            $numtotal10 += $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            } else {
                $fator = 2; // intercala fator de multiplicacao (modulo 10)
            }
        }

        // várias linhas removidas, vide função original
        // Calculo do modulo 10
        $resto = $numtotal10 % 10;
        $digito = 10 - $resto;
        if ($resto == 0) {
            $digito = 0;
        }

        return $digito;
    }

    public function modulo11Invertido($num)  // Calculo de Modulo 11 "Invertido" (com pesos de 9 a 2  e não de 2 a 9)
    {
        $ftini = 2;
        $fator = $ftfim = 9;
        $soma = 0;

        for ($i = strlen($num); $i > 0; $i--) {
            $soma += substr($num, $i - 1, 1) * $fator;
            if (--$fator < $ftini) {
                $fator = $ftfim;
            }
        }

        $digito = $soma % 11;

        if ($digito > 9) {
            $digito = 0;
        }

        return $digito;
    }

    public function modulo11($num, $base = 9, $r = 0)
    {
        $soma = 0;
        $fator = 2;

        /* Separacao dos numeros */
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num, $i - 1, 1);
            // Efetua multiplicacao do numero pelo falor
            $parcial[$i] = $numeros[$i] * $fator;
            // Soma dos digitos
            $soma += $parcial[$i];
            if ($fator == $base) {
                // restaura fator de multiplicacao para 2 
                $fator = 1;
            }
            $fator++;
        }

        /* Calculo do modulo 11 */
        if ($r == 0) {
            $soma *= 10;
            $digito = $soma % 11;
            if ($digito == 10) {
                $digito = 0;
            }
            return $digito;
        } elseif ($r == 1) {
            $resto = $soma % 11;
            return $resto;
        }
    }

    /*
      Montagem da linha digitável - Função tirada do PHPBoleto
      Não mudei nada
     */

    private function montaLinhaDigitavel($linha)
    {
        // Posição 	Conteúdo
        // 1 a 3    Número do banco
        // 4        Código da Moeda - 9 para Real ou 8 - outras moedas
        // 5        Fixo "9'
        // 6 a 9    PSK - codigo cliente (4 primeiros digitos)
        // 10 a 12  Restante do PSK (3 digitos)
        // 13 a 19  7 primeiros digitos do Nosso Numero
        // 20 a 25  Restante do Nosso numero (8 digitos) - total 13 (incluindo digito verificador)
        // 26 a 26  IOS
        // 27 a 29  Tipo Modalidade Carteira
        // 30 a 30  Dígito verificador do código de barras
        // 31 a 34  Fator de vencimento (qtdade de dias desde 07/10/1997 até a data de vencimento)
        // 35 a 44  Valor do título
        // 1. Primeiro Grupo - composto pelo código do banco, código da moéda, Valor Fixo "9"
        // e 4 primeiros digitos do PSK (codigo do cliente) e DV (modulo10) deste campo
        $campo1 = substr($linha, 0, 3) . substr($linha, 3, 1) . substr($linha, 19, 1) . substr($linha, 20, 4);
        $campo1 = $campo1 . $this->modulo10($campo1);
        $campo1 = substr($campo1, 0, 5) . '.' . substr($campo1, 5);

        // 2. Segundo Grupo - composto pelas 3 últimas posiçoes do PSK e 7 primeiros dígitos do Nosso Número
        // e DV (modulo10) deste campo
        $campo2 = substr($linha, 24, 10);
        $campo2 = $campo2 . $this->modulo10($campo2);
        $campo2 = substr($campo2, 0, 5) . '.' . substr($campo2, 5);

        // 3. Terceiro Grupo - Composto por : Restante do Nosso Numero (6 digitos), IOS, Modalidade da Carteira
        // e DV (modulo10) deste campo
        $campo3 = substr($linha, 34, 10);
        $campo3 = $campo3 . $this->modulo10($campo3);
        $campo3 = substr($campo3, 0, 5) . '.' . substr($campo3, 5);

        // 4. Campo - digito verificador do codigo de barras
        $campo4 = substr($linha, 4, 1);

        // 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
        // indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
        // tratar de valor zerado, a representacao deve ser 0000000000 (dez zeros).
        $campo5 = substr($linha, 5, 4) . substr($linha, 9, 10);

        return "$campo1 $campo2 $campo3 $campo4 $campo5";
    }

}
