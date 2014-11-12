<?php

namespace Pixel\Filtro;

/**
 * sho foi usado para substituir hidden_sis_filtro
 * sha foi usado para substituir hiddent_sis_filtro
 */
class Filtrar
{

    private $objForm;
    private $situacao; //Situação do Filtro;
    private $operadores = array();

    public function __construct($objForm = null)
    {
        $this->situacao = false;

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

        //Recupera Valores do Formulário
        $operador = \filter_input(\INPUT_GET, 'sho' . 'n'.$nomeCampo);
        $acao = \strtolower(\filter_input(\INPUT_GET, 'sha' . 'n'.$nomeCampo));
        $valor = \filter_input(\INPUT_GET, 'n'.$nomeCampo);  
        
        //echo $operador.' - '.$acao.' - '.$valor."\n";

        //Valida Informações
        if ($operador == '' or $acao == '') {
            if ($valor <> '') {
                $this->situacao = true;

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

                        $this->situacao = true;

                        break;

                    case '*A':

                        $sql = " AND $campoBanco LIKE '%$valor' ";

                        $this->situacao = true;

                        break;

                    case 'A*':

                        $sql = " AND $campoBanco LIKE '$valor%' ";

                        $this->situacao = true;

                        break;

                    case '*':

                        $sql = " AND $campoBanco LIKE '%$valor%' ";

                        $this->situacao = true;

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
    private function eOrSql($campoBanco, $nomeCampo, $tipoFiltro, $clausula)
    {
        //Recupera Operadores
        $operadorA = filter_input(INPUT_GET, 'sho' . $nomeCampo . 'A');
        $operadorB = filter_input(INPUT_GET, 'sho' . $nomeCampo . 'B');

        //Recupera Valores
        $valorA = trim(filter_input(INPUT_GET, $nomeCampo . 'A'));
        $valorB = trim(filter_input(INPUT_GET, $nomeCampo . 'B'));

        //Recupera Tipo de Campo
        $acao = filter_input(INPUT_GET, 'sha' . $nomeCampo);

        //Validação de Operadores
        if (($operadorA == '' and $operadorB == '') or $acao == '') {
            return '';
        }

        //Valida��o de Valores
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
            $this->objForm->setCampoRetorna($nomeCampo . 'A', $valorA);
            $valorA = $this->objForm->getCampoFiltro($nomeCampo . 'A', $tipoFiltro);
        }

        if ($valorB <> '') {
            //Valida Opreador B
            if (!in_array($operadorB, $this->operadores)) {
                return '';
            }

            //Seta Valores
            $this->objForm->setCampoRetorna($nomeCampo . 'B', $valorB);
            $valorB = $this->objForm->getCampoFiltro($nomeCampo . 'B', $tipoFiltro);
        }

        if ($acao <> 'float' and $acao <> 'float') {
            if ($valorA <> '') {
                $valorA = "'$valorA'";
            }

            if ($valorB <> '') {
                $valorB = "'$valorB'";
            }
        }

        //Se valor a vazio e b não inverte
        if ($valorB <> '' and $valorA == '') {
            $valorA = $valorB;
            $operadorA = $operadorB;

            $valorB = '';
            $operadorB = '';
        }

        //Se os dois operadores são iguais mude para ow a não ser que seja <> ou =
        if ($valorB <> '') {
            if (($operadorA == $operadorB and $operadorA <> "<>") or $operadorB == "=") {
                $clausula = 'OR';
            }
        }

        //Define o Tipo de Clausula
        if ($clausula == 'E') {
            if ($valorB <> '') {
                $sql = " AND $campoBanco $operadorA $valorA AND $campoBanco $operadorB $valorB ";
            } else {
                $sql = " AND $campoBanco $operadorA $valorA ";
            }

            $this->situacao = true;
        } elseif ($clausula == 'OR') {
            if ($valorB <> '') {
                $sql = " AND (($campoBanco $operadorA $valorA) OR ($campoBanco $operadorB $valorB)) ";
            } else {
                $sql = " AND $campoBanco $operadorA $valorA ";
            }

            $this->situacao = true;
        }

        return $sql;
    }

    function getHiddenParametros($arrayParametros)
    {
        return [];
        $retorno = array();

        if (is_array($arrayParametros) and ! empty($arrayParametros)) {

            foreach ($arrayParametros as $campo) {
                //Intecepta E ou OU
                $campoEOU = filter_input(INPUT_GET, 'sho' . $campo);

                if ($campoEOU == 'E' or $campoEOU == 'OU') {
                    $valorA = filter_input(INPUT_GET, $campo . 'A');
                    $valorB = filter_input(INPUT_GET, $campo . 'B');

                    $opcaoA = filter_input(INPUT_GET, 'sho' . $campo . 'A');
                    $opcaoB = filter_input(INPUT_GET, 'sho' . $campo . 'B');

                    $tipo = filter_input(INPUT_GET, 'sha' . $campo);

                    if ($valorA <> '') {
                        if ($opcaoA <> '' and $tipo <> '') {
                            $retorno[] = $campo . 'A';
                            $retorno[] = 'sho' . $campo . 'A';
                        }
                    }

                    if ($valorB <> '') {
                        if ($opcaoB <> '' and $tipo <> '') {
                            $retorno[] = $campo . 'B';
                            $retorno[] = 'sho' . $campo . 'B';
                        }
                    }

                    //Tipo E e Ou
                    if ($valorA <> '' or $valorB <> '') {
                        $retorno[] = 'sho' . $campo;
                        $retorno[] = 'sha' . $campo;
                    }
                } else {
                    $valor = filter_input(INPUT_GET, $campo);
                    $opcao = filter_input(INPUT_GET, 'sho' . $campo);
                    $tipo = filter_input(INPUT_GET, 'sha' . $campo);

                    if ($valor <> '') {
                        if ($opcao <> '' and $tipo <> '') {
                            $retorno[] = 'sho' . $campo;
                            $retorno[] = 'sha' . $campo;
                        }
                    }
                }
            }

            return $retorno;
        } else {
            return $retorno;
        }
    }

    //Metodo para interceptar registros selecionados da grid
    public function printSql($cheveCompara, $array)
    {
        $sql = ' AND ' . $cheveCompara . ' IN (';

        if (is_array($array) and count($array) > 0) {
            $sql .= implode(',', $array);

            $this->situacao = true;
        } else {
            return '';
        }

        return $sql . ') ';
    }

    public function setSituacao($valor)
    {
        $this->situacao = $valor === true ? true : false;
    }

    public function getSituacao()
    {
        return $this->situacao;
    }

}
