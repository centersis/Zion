<?php

namespace Pixel\Filtro;

/**
 * sho foi usado para substituir hidden_sis_filtro
 * sha foi usado para substituir hiddent_sis_filtro
 */
class Filtrar
{

    private $objForm;
    private $operadores = array();

    public function __construct($objForm = null)
    {
        if (is_object($objForm)) {
            $this->objForm = $objForm;
        }

        $this->operadores = [
            '=' => '=',
            '>' => '>',
            '<' => '<',
            '>=' => '>=',
            '<=' => '<=',
            '≠' => '≠',
            '*A' => '*A',
            'A*' => 'A*',
            '*' => '*'];
    }

    public function getStringSql($nomeCampo, $campoBanco)
    {
        $sql = '';

        $origem = \strtolower(\filter_input(\INPUT_GET, 'sisOrigem'));

        switch ($origem) {
            case 'n': $sql.= $this->normalSql($nomeCampo, $campoBanco);
                break;
            case 'e':
                $sql.= $this->eOrSql($campoBanco, $nomeCampo, $origem);
                break;
            case 'o':
                $sql.= $this->eOrSql($campoBanco, $nomeCampo, $origem);
                break;
        }

        return $sql;
    }

    private function normalSql($nomeCampo, $campoBanco)
    {
        $sql = '';
        $operador = \filter_input(\INPUT_GET, 'sho' . 'n' . $nomeCampo);
        $acao = \strtolower(\filter_input(\INPUT_GET, 'sha' . 'n' . $nomeCampo));
        $valor = \filter_input(\INPUT_GET, 'n' . $nomeCampo);

        //Valida Informações
        if ($operador == '' or $acao == '') {
            if ($valor <> '') {
                return " AND " . $campoBanco . " = '" . $valor . "' ";
            }

            return $sql;
        }

        //Retorna Sql	
        if ("$valor" <> "") {

            if (\in_array($operador, $this->operadores)) {

                switch ($operador) {

                    case '=': case '>': case '<': case '>=': case '<=': case '≠':

                        if ($acao <> 'float' and $acao <> 'number') {
                            $valor = "'$valor'";
                        }

                        if ($operador === '≠') {
                            $operador = '<>';
                        }

                        $sql = " AND $campoBanco $operador $valor ";

                        break;

                    case '*A':

                        $sql = " AND $campoBanco LIKE '%$valor' ";

                        break;

                    case 'A*':

                        $sql = " AND $campoBanco LIKE '$valor%' ";

                        break;

                    case '*':

                        $sql = " AND $campoBanco LIKE '%$valor%' ";

                        break;

                    default:

                        $sql = '';

                        break;
                }
            }
        }

        return $sql;
    }

    //Para clausulas E e OR
    private function eOrSql($campoBanco, $nomeCampo, $origem)
    {
        //Recupera Operadores
        $operadorA = \filter_input(\INPUT_GET, 'sho' . $origem . $nomeCampo . 'A');
        $operadorB = \filter_input(\INPUT_GET, 'sho' . $origem . $nomeCampo . 'B');

        //Recuper Ação
        $acaoA = \filter_input(\INPUT_GET, 'sha' . $origem . $nomeCampo . 'A');
        $acaoB = \filter_input(\INPUT_GET, 'sha' . $origem . $nomeCampo . 'B');

        //Recupera Valores
        $valorA = \trim(filter_input(\INPUT_GET, $origem . $nomeCampo . 'A'));
        $valorB = \trim(filter_input(\INPUT_GET, $origem . $nomeCampo . 'B'));

        //echo $operadorA.' - '.$operadorB.' | '.$acaoA.' - '.$acaoB.' | '.$valorA.' - '.$valorB.' > '.$origem.' - '.$nomeCampo."\n";
        
        //Converte Opreadores
        if ($operadorA === '≠') {
            $operadorA = '<>';
        }

        if ($operadorB === '≠') {
            $operadorB = '<>';
        }

        //Validação de Operadores
        if ($valorA == '' and $valorB == '') {
            return '';
        }

        //Seta e Recupera Valores
        if ($valorA <> '') {
            //Valida Opreador A
            if (!in_array($operadorA, $this->operadores)) {
                return '';
            }

            //Seta Valores
            $this->objForm->set($nomeCampo . 'A', $valorA);
            $valorA = $this->objForm->get($nomeCampo . 'A', $acaoA);
        }

        if ($valorB <> '') {
            //Valida Opreador B
            if (!in_array($operadorB, $this->operadores)) {
                return '';
            }

            //Seta Valores
            $this->objForm->set($nomeCampo . 'B', $valorB);
            $valorB = $this->objForm->getSql($nomeCampo . 'B', $acaoB);
        }               

        //Se valor a vazio e b não inverte
        if ($valorB <> '' and $valorA == '') {
            $valorA = $valorB;
            $operadorA = $operadorB;

            $valorB = '';
            $operadorB = '';
        }

        $clausula = $origem;
        
        //Se os dois operadores são iguais mude para ow a não ser que seja <> ou =
        if ($valorB <> '') {
            if (($operadorA == $operadorB and $operadorA <> "<>") or $operadorB == "=") {
                $clausula = 'o';
            }
        }

        //Define o Tipo de Clausula
        if ($clausula == 'e') {
            if ($valorB <> '') {
                $sql = " AND $campoBanco $operadorA $valorA AND $campoBanco $operadorB $valorB ";
            } else {
                $sql = " AND $campoBanco $operadorA $valorA ";
            }
        } elseif ($clausula == 'o') {
            if ($valorB <> '') {
                $sql = " AND (($campoBanco $operadorA $valorA) OR ($campoBanco $operadorB $valorB)) ";
            } else {
                $sql = " AND $campoBanco $operadorA $valorA ";
            }
        }

        return $sql;
    }

    function getHiddenParametros($arrayParametros)
    {
        $retorno = [];

        if (\is_array($arrayParametros) and ! empty($arrayParametros)) {

            foreach ($arrayParametros as $campo) {

                $valor = \filter_input(\INPUT_GET, $campo);
                $opcao = \filter_input(\INPUT_GET, 'sho' . $campo);
                $acao = \filter_input(\INPUT_GET, 'sha' . $campo);

                if ($valor <> '') {
                    if ($opcao <> '' and $acao <> '') {
                        $retorno[] = 'sho' . $campo;
                        $retorno[] = 'sha' . $campo;
                    }
                }
            }

            return $retorno;
        } else {

            return $retorno;
        }
    }

}
