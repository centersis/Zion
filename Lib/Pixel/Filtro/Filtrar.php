<?php

namespace Pixel\Filtro;

use Zion\Tratamento\Tratamento;
use Zion\Exception\ErrorException;

class Filtrar
{

    private $objForm;
    private $operadores = [];
    private $interpretarComo = [];

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
            '<>' => '<>',
            'E' => 'E',
            '*A' => '*A',
            'A*' => 'A*',
            '*' => '*',
            '**' => '**'];
    }

    public function getStringSql($nomeCampo, $campoBanco, $queryBuilder)
    {
        $this->normalSql($nomeCampo, $campoBanco, $queryBuilder);
    }

    private function getOperador($queryObject, $nomeCampo)
    {
        $existeNaQuery = isset($queryObject->{'sho' . 'n' . $nomeCampo});
        if ($queryObject && $existeNaQuery) {
            return $queryObject->{'sho' . 'n' . $nomeCampo};
        } elseif ($queryObject && !$existeNaQuery) {
            return null;
        }

        return filter_input(INPUT_GET, 'sho' . 'n' . $nomeCampo);
    }

    private function getValor($queryObject, $nomeCampo)
    {
        $existeNaQuery = isset($queryObject->{'n' . $nomeCampo});
        if ($queryObject && $existeNaQuery) {
            return $queryObject->{'n' . $nomeCampo};
        } elseif ($queryObject && !$existeNaQuery) {
            return null;
        }

        return filter_input(INPUT_GET, 'n' . $nomeCampo);
    }

    private function getAcao($queryObject, $nomeCampo)
    {
        $existeNaQuery = isset($queryObject->{'sha' . 'n' . $nomeCampo});
        if ($queryObject && $existeNaQuery) {
            return $queryObject->{'sha' . 'n' . $nomeCampo};
        } elseif ($queryObject && !$existeNaQuery) {
            return null;
        }

        return strtolower(filter_input(INPUT_GET, 'sha' . 'n' . $nomeCampo));
    }

    private function normalSql($nomeCampo, $campoBanco, $queryBuilder, $queryObject = null)
    {
        $operador = $this->getOperador($queryObject, $nomeCampo);
        $acao = $this->getAcao($queryObject, $nomeCampo);
        $valor = $this->getValor($queryObject, $nomeCampo);

        if (array_key_exists($campoBanco, $this->interpretarComo)) {
            $campoBanco = $this->interpretarComo[$campoBanco];
        }

        if (strtoupper($valor) === 'SISNOTNULL') {

            if ($operador === '<>') {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($campoBanco));
            } else {
                $queryBuilder->andWhere($queryBuilder->expr()->isNotNull($campoBanco));
            }
        } else if (strtoupper($valor) === 'SISNULL') {
            if ($operador === '<>') {
                $queryBuilder->andWhere($queryBuilder->expr()->isNotNull($campoBanco));
            } else {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($campoBanco));
            }
        } else {

            $rand = mt_rand(1, 9999); //Como o objeto pode ser repetido inumeras vezes, "adota-se" uma nome randomico para não haver conflito
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

                $this->condicoes($campoBanco, $operador, $valor, $acao, $queryBuilder);
            }
        }
    }

    private function condicoes($campoBanco, $operador, $valor, $acao, $queryBuilder)
    {
        if (array_key_exists($campoBanco, $this->interpretarComo)) {
            $campoBanco = $this->interpretarComo[$campoBanco];
        }

        $tratar = Tratamento::instancia();

        if (in_array($operador, $this->operadores)) {

            $rand = mt_rand(1, 9999);

            switch ($operador) {

                case '=': case '>': case '<': case '>=': case '<=': case '<>':

                    $tipoParametro = \PDO::PARAM_STR;

                    if ($acao == 'number') {
                        $tipoParametro = \PDO::PARAM_INT;
                    }

                    if ($acao == 'date') {
                        $valor = $tratar->data()->converteData($valor);
                    }

                    if ($acao == 'float') {
                        $valor = $tratar->numero()->floatBanco($valor);
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

                        case '<>':

                            $queryBuilder->andWhere($queryBuilder->expr()->neq($campoBanco, ':camp02' . $rand))
                                ->setParameter('camp02' . $rand, $valor, $tipoParametro);

                            break;
                    }

                    break;

                case '*A':

                    $queryBuilder->andWhere($queryBuilder->expr()->like($campoBanco, $queryBuilder->expr()->literal('%' . $valor)));

                    break;

                case 'A*':

                    $queryBuilder->andWhere($queryBuilder->expr()->like($campoBanco, $queryBuilder->expr()->literal($valor . '%')));

                    break;

                case '*':

                    $queryBuilder->andWhere($queryBuilder->expr()->like($campoBanco, $queryBuilder->expr()->literal('%' . $valor . '%')));

                    break;

                case '**':

                    $pedacos = explode(' ', $valor);
                    $sql = [];

                    foreach ($pedacos as $valorPedaco) {

                        $sql[] = $queryBuilder->expr()->like($campoBanco, $queryBuilder->expr()->literal('%' . $valorPedaco . '%'));
                    }

                    $queryBuilder->andWhere(implode(' AND ', $sql));

                    break;

                case 'E':

                    $pedacos = explode(',', $valor);

                    $queryBuilder->andWhere($queryBuilder->expr()->gte($campoBanco, ':campe' . $rand))
                        ->setParameter('campe' . $rand, $pedacos[0], $tipoParametro)
                        ->andWhere($queryBuilder->expr()->lte($campoBanco, ':campe' . $rand))
                        ->setParameter('campe' . $rand, $pedacos[1], $tipoParametro);


                    break;
            }
        }
    }

    function getHiddenParametros($arrayParametros)
    {
        $retorno = [];

        if (is_array($arrayParametros) and ! empty($arrayParametros)) {

            foreach ($arrayParametros as $campo) {

                $valor = filter_input(INPUT_GET, $campo);
                $opcao = filter_input(INPUT_GET, 'sho' . $campo);
                $acao = filter_input(INPUT_GET, 'sha' . $campo);

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

    public function interpretarComo($campo, $traducao)
    {
        $this->interpretarComo[$campo] = $traducao;
    }

}
