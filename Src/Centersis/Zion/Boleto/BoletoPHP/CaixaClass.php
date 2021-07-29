<?php

namespace Centersis\Zion\Boleto\BoletoPHP;

use Zion\Tratamento\Tratamento;

class CaixaClass
{

    private $urlImg;
    private $tratamento;

    public function __construct()
    {
        $this->tratamento = Tratamento::instancia();
        $this->urlImg = SIS_URL_BASE . 'Financeiro/Movimentacao/Parcela/Boleto/Tema/Vendor/Pixel/1.3.0/views/img/';
    }

    public function getDadosBoleto($dadosParcela, $parametrizacao)
    {
        $dadosboleto = [];
        $codigobanco = $parametrizacao['fnc_convenio_banco'];
        $codigo_banco_com_dv = '104-0'; //$this->geraCodigoBanco($codigobanco);
        $nummoeda = '9';

        $fator_vencimento = $this->fatorVencimento($this->tratamento->data()->converteData($dadosParcela["fnc_parcela_vencimento"]));

        //valor tem 10 digitos, sem virgula
        $valorParcela = \str_replace('.', ',', $dadosParcela["fnc_parcela_valor"]);
        $valor = $this->formataNumero($valorParcela, 10, 0, "valor");

        //agencia é sempre 4 digitos
        $agencia = $this->formataNumero($parametrizacao["fnc_convenio_agencia"], 4, 0);

        //conta cedente (sem dv) com 6 digitos
        $conta_cedente = $this->formataNumero($parametrizacao["fnc_convenio_numero"], 6, 0);

        //dv da conta cedente
        $conta_cedente_dv = $this->digitoVerificadorCedente($conta_cedente);

        //campo livre (sem dv) é 24 digitos
        $campo_livre = $conta_cedente;
        $campo_livre .= $conta_cedente_dv;
        $campo_livre .= $this->formataNumero('000', 3, 0);
        $campo_livre .= $this->formataNumero('1', 1, 0);
        $campo_livre .= $this->formataNumero('000', 3, 0);
        $campo_livre .= $this->formataNumero('4', 1, 0);
        $campo_livre .= \substr($dadosParcela["fnc_parcela_nosso_numero"], -9);

        //dv do campo livre
        $dv_campo_livre = $this->digitoVerificadorNossoNumero($campo_livre);
        $campo_livre_com_dv = "$campo_livre$dv_campo_livre";

        //nosso número (sem dv) é 17 digitos
        $nnum = $dadosParcela["fnc_parcela_nosso_numero"];

        //nosso número completo (com dv) com 18 digitos
        $nossonumero = $nnum.'-'.$this->digitoVerificadorNossoNumero($nnum);

        // 43 numeros para o calculo do digito verificador do codigo de barras
        $dv = $this->digitoVerificadorBarra("$codigobanco$nummoeda$fator_vencimento$valor$campo_livre_com_dv", 9, 0);

        // Numero para o codigo de barras com 44 digitos
        $linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$campo_livre_com_dv";

        $agencia_codigo = $agencia . " / " . $conta_cedente . "-" . $conta_cedente_dv;


        $dadosboleto["codigo_barras"] = $this->fBarcode($linha);
        $dadosboleto["linha_digitavel"] = $this->montaLinhaDigitavel($linha);
        $dadosboleto["agencia_codigo"] = $agencia_codigo;
        $dadosboleto["nosso_numero"] = $nossonumero;
        $dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;

        return $dadosboleto;
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
                }
                else {
                    $f1 = $largo;
                }
                $buffer.=' src=' . $this->urlImg . 'p.png width=' . $f1 . ' height=' . $altura . ' border=0>';
                $buffer.='<img ';
                if (substr($f, $i, 1) == "0") {
                    $f2 = $fino;
                }
                else {
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
        return \substr($entra, 0, $comp);
    }

    private function direita($entra, $comp)
    {
        return \substr($entra, strlen($entra) - $comp, $comp);
    }

    private function fatorVencimento($dataBR)
    {
        $data = \preg_split('/\/|-/', $dataBR);
        $ano = $data[2];
        $mes = $data[1];
        $dia = $data[0];
        return(abs(($this->dataParaDias("1997", "10", "07")) - ($this->dataParaDias($ano, $mes, $dia))));
    }

    private function dataParaDias($ano, $mes, $dia)
    {
        $century = \substr($ano, 0, 2);
        $year = \substr($ano, 2, 2);
        if ($mes > 2) {
            $mes -= 3;
        }
        else {
            $mes += 9;
            if ($year) {
                $year--;
            }
            else {
                $year = 99;
                $century --;
            }
        }

        return ( \floor(( 146097 * $century) / 4) +
            \floor(( 1461 * $year) / 4) +
            \floor(( 153 * $mes + 2) / 5) +
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
            }
            else {
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

    private function modulo11($num, $base = 9, $r = 0)
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
        }
        elseif ($r == 1) {
            $resto = $soma % 11;
            return $resto;
        }
    }

    private function montaLinhaDigitavel($codigo)
    {

        // Posição 	Conteúdo
        // 1 a 3    Número do banco
        // 4        Código da Moeda - 9 para Real
        // 5        Digito verificador do Código de Barras
        // 6 a 9   Fator de Vencimento
        // 10 a 19 Valor (8 inteiros e 2 decimais)
        // 20 a 44 Campo Livre definido por cada banco (25 caracteres)
        // 1. Campo - composto pelo código do banco, código da moéda, as cinco primeiras posições
        // do campo livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 0, 4);
        $p2 = substr($codigo, 19, 5);
        $p3 = $this->modulo10("$p1$p2");
        $p4 = "$p1$p2$p3";
        $p5 = substr($p4, 0, 5);
        $p6 = substr($p4, 5);
        $campo1 = "$p5.$p6";

        // 2. Campo - composto pelas posiçoes 6 a 15 do campo livre
        // e livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 24, 10);
        $p2 = $this->modulo10($p1);
        $p3 = "$p1$p2";
        $p4 = substr($p3, 0, 5);
        $p5 = substr($p3, 5);
        $campo2 = "$p4.$p5";

        // 3. Campo composto pelas posicoes 16 a 25 do campo livre
        // e livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 34, 10);
        $p2 = $this->modulo10($p1);
        $p3 = "$p1$p2";
        $p4 = substr($p3, 0, 5);
        $p5 = substr($p3, 5);
        $campo3 = "$p4.$p5";

        // 4. Campo - digito verificador do codigo de barras
        $campo4 = substr($codigo, 4, 1);

        // 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
        // indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
        // tratar de valor zerado, a representacao deve ser 000 (tres zeros).
        $p1 = substr($codigo, 5, 4);
        $p2 = substr($codigo, 9, 10);
        $campo5 = "$p1$p2";

        return "$campo1 $campo2 $campo3 $campo4 $campo5";
    }

    private function digitoVerificadorBarra($numero)
    {
        $resto2 = $this->modulo11($numero, 9, 1);

        if ($resto2 == 0 || $resto2 == 1 || $resto2 == 10) {
            $dv = 1;
        }
        else {
            $dv = 11 - $resto2;
        }

        return $dv;
    }

    private function digitoVerificadorNossoNumero($numero)
    {
        $resto2 = $this->modulo11($numero, 9, 1);
        $digito = 11 - $resto2;

        if ($digito == 10 || $digito == 11) {
            $dv = 0;
        }
        else {
            $dv = $digito;
        }
        return $dv;
    }

    private function digitoVerificadorCedente($numero)
    {
        $resto2 = $this->modulo11($numero, 9, 1);
        $digito = 11 - $resto2;

        if ($digito == 10 || $digito == 11) {
            $digito = 0;
        }

        $dv = $digito;

        return $dv;
    }

}
