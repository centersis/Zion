<?php

namespace Pixel\Filtro;

/**
 * shf foi usado para substituir hidden_sis_filtro
 * shtf foi usado para substituir hiddent_sis_filtro
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
            '<>' => '<>',
            '*A' => '*A',
            'A*' => 'A*',
            '*' => '*',
            'E' => 'E',
            'OU' => 'OU'];
    }

    public function getStringSql($nomeCampo, $campoBanco, $tipoFiltro = null)
    {
        $sql = '';

        //Recupera Valores do Formulário
        $operador = filter_input(INPUT_GET, 'shf' . $nomeCampo);
        $tipoCampo = strtolower(filter_input(INPUT_GET, 'shtf' . $nomeCampo));

        //Intercepta valor
        $valor = $this->objForm->getCampoFiltro($nomeCampo, $tipoFiltro);

        //Valida Informações
        if ($operador == '' or $tipoCampo == '') {
            if ($valor <> '') {
                $this->situacao = true;

                return " AND " . $campoBanco . " = '" . $valor . "' ";
            }

            return $sql;
        }

        //Retorna Sql	
        if ("$valor" <> "" or ( $operador == 'E' or $operador == 'OU')) {
            if (in_array($operador, $this->operadores)) {
                switch ($operador) {
                    case '=': case '>': case '<': case '>=': case '<=': case '<>':

                        if ($tipoCampo <> 'float' and $tipoCampo <> 'float') {
                            $valor = "'$valor'";
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

                    case 'E':

                        $sql = $this->eOrSql($campoBanco, $nomeCampo, $tipoFiltro, 'E');

                        break;

                    case 'OU':

                        $sql = $this->eOrSql($campoBanco, $nomeCampo, $tipoFiltro, 'OR');

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
        $operadorA = filter_input(INPUT_GET, 'shf' . $nomeCampo . 'A');
        $operadorB = filter_input(INPUT_GET, 'shf' . $nomeCampo . 'B');

        //Recupera Valores
        $valorA = trim(filter_input(INPUT_GET, $nomeCampo . 'A'));
        $valorB = trim(filter_input(INPUT_GET, $nomeCampo . 'B'));

        //Recupera Tipo de Campo
        $tipoCampo = filter_input(INPUT_GET, 'shtf' . $nomeCampo);

        //Validação de Operadores
        if (($operadorA == '' and $operadorB == '') or $tipoCampo == '') {
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

        if ($tipoCampo <> 'float' and $tipoCampo <> 'float') {
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
        $Retorno = array();

        if (is_array($arrayParametros) and ! empty($arrayParametros)) {

            foreach ($arrayParametros as $campo) {
                //Intecepta E ou OU
                $campoEOU = filter_input(INPUT_GET, 'shf' . $campo);

                if ($campoEOU == 'E' or $campoEOU == 'OU') {
                    $valorA = filter_input(INPUT_GET, $campo . 'A');
                    $valorB = filter_input(INPUT_GET, $campo . 'B');

                    $opcaoA = filter_input(INPUT_GET, 'shf' . $campo . 'A');
                    $opcaoB = filter_input(INPUT_GET, 'shf' . $campo . 'B');

                    $tipo = filter_input(INPUT_GET, 'shtf' . $campo);

                    if ($valorA <> '') {
                        if ($opcaoA <> '' and $tipo <> '') {
                            $Retorno[] = $campo . 'A';
                            $Retorno[] = 'shf' . $campo . 'A';
                        }
                    }

                    if ($valorB <> '') {
                        if ($opcaoB <> '' and $tipo <> '') {
                            $Retorno[] = $campo . 'B';
                            $Retorno[] = 'shf' . $campo . 'B';
                        }
                    }

                    //Tipo E e Ou
                    if ($valorA <> '' or $valorB <> '') {
                        $Retorno[] = 'shf' . $campo;
                        $Retorno[] = 'shtf' . $campo;
                    }
                } else {
                    $valor = filter_input(INPUT_GET, $campo);
                    $opcao = filter_input(INPUT_GET, 'shf' . $campo);
                    $tipo = filter_input(INPUT_GET, 'shtf' . $campo);

                    if ($valor <> '') {
                        if ($opcao <> '' and $tipo <> '') {
                            $Retorno[] = 'shf' . $campo;
                            $Retorno[] = 'shtf' . $campo;
                        }
                    }
                }
            }

            return $Retorno;
        } else {
            return $Retorno;
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
