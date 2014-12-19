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
        if (\is_object($objForm)) {
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

    public function getStringSql($nomeCampo, $campoBanco, $queryBuilder)
    {
        $origem = \strtolower(\filter_input(\INPUT_GET, 'sisOrigem'));

        switch ($origem) {
            case 'n':
                $this->normalSql($nomeCampo, $campoBanco, $queryBuilder);
                break;
            case 'e':
                $this->eOrSql($campoBanco, $nomeCampo, $origem, $queryBuilder);
                break;
            case 'o':
                $this->eOrSql($campoBanco, $nomeCampo, $origem, $queryBuilder);
                break;
        }
    }

    private function normalSql($nomeCampo, $campoBanco, $queryBuilder)
    {
        $operador = \filter_input(\INPUT_GET, 'sho' . 'n' . $nomeCampo);
        $acao = \strtolower(\filter_input(\INPUT_GET, 'sha' . 'n' . $nomeCampo));
        $valor = \filter_input(\INPUT_GET, 'n' . $nomeCampo);

        $rand = \mt_rand(1, 9999); //Como o objeto pode ser repetido inumeras vezes, adota-se uma nome randomico para não haver conflito
        //Valida Informações        
        if ($operador == '' or $acao == '') {
            if ($valor <> '') {

                $queryBuilder->andWhere($queryBuilder->expr()->eq($campoBanco, ':camp01' . $rand))
                        ->setParameter('camp01' . $rand, $queryBuilder->expr()->literal($valor), \PDO::PARAM_STR);
                return;
            }

            return;
        }

        //Retorna Sql	
        if ("$valor" <> "") {

            if (\in_array($operador, $this->operadores)) {

                switch ($operador) {

                    case '=': case '>': case '<': case '>=': case '<=': case '≠':

                        $tipoParametro = \PDO::PARAM_STR;

                        if ($acao == 'number') {
                            $tipoParametro = \PDO::PARAM_INT;
                        }

                        switch ($operador) {
                            case '=':

                                $queryBuilder->andWhere($queryBuilder->expr()->eq($campoBanco, ':camp02' . $rand))
                                        ->setParameter('camp02' . $rand, $valor, $tipoParametro);

                                break;

                            case '>':

                                $queryBuilder->andWhere($queryBuilder->expr()->gt($campoBanco, ':camp02' . $rand))
                                        ->setParameter('camp02' . $rand, $valor, $tipoParametro);

                                break;

                            case '<':

                                $queryBuilder->andWhere($queryBuilder->expr()->lt($campoBanco, ':camp02' . $rand))
                                        ->setParameter('camp02' . $rand, $valor, $tipoParametro);

                                break;

                            case '>=':

                                $queryBuilder->andWhere($queryBuilder->expr()->gte($campoBanco, ':camp02' . $rand))
                                        ->setParameter('camp02' . $rand, $valor, $tipoParametro);

                                break;

                            case '<=':

                                $queryBuilder->andWhere($queryBuilder->expr()->lte($campoBanco, ':camp02' . $rand))
                                        ->setParameter('camp02' . $rand, $valor, $tipoParametro);

                                break;

                            case '≠':

                                $queryBuilder->andWhere($queryBuilder->expr()->neq($campoBanco, ':camp02' . $rand))
                                        ->setParameter('camp02' . $rand, $valor, $tipoParametro);

                                break;
                        }

                        break;

                    case '*A':

                        $queryBuilder->andWhere($queryBuilder->expr()->like($campoBanco, $queryBuilder->expr()->literal('%' . $valor)));
                        //->setParameter('camp03' . $rand, $valor, \PDO::PARAM_STR);

                        break;

                    case 'A*':

                        $queryBuilder->andWhere($queryBuilder->expr()->like($campoBanco, $queryBuilder->expr()->literal($valor . '%')));
                        //->setParameter('camp03' . $rand, $valor, \PDO::PARAM_STR);

                        break;

                    case '*':

                        $queryBuilder->andWhere($queryBuilder->expr()->like($campoBanco, $queryBuilder->expr()->literal('%' . $valor . '%')));
                        //->setParameter('camp03' . $rand, $valor, \PDO::PARAM_STR);

                        break;
                }
            }
        }
    }

    //Para clausulas E e OR
    private function eOrSql($campoBanco, $nomeCampo, $origem, $queryBuilder)
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
            $valorA = $this->objForm->getFiltroSql($nomeCampo . 'A', $acaoA);
        }

        if ($valorB <> '') {
            //Valida Opreador B
            if (!in_array($operadorB, $this->operadores)) {
                return '';
            }

            //Seta Valores
            $this->objForm->set($nomeCampo . 'B', $valorB);
            $valorB = $this->objForm->getFiltroSql($nomeCampo . 'B', $acaoB);
        }

        //Se valor a vazio e b não inverte
        if ($valorB <> '' and $valorA == '') {
            $valorA = $valorB;
            $operadorA = $operadorB;

            $valorB = '';
            $operadorB = '';
        }

        $clausula = $origem;

        //Se os dois operadores são iguais mude para 'or' a não ser que seja <> ou =
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
