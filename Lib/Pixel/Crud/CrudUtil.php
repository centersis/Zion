<?php

/**
 *  @author Pablo Vanni - pablovanni@gmail.com
 *  Classe de utilidades - Usanda na manutenção básica
 *  Atualizada em 14-10-2014 por Pablo Vanni
 */

namespace Pixel\Crud;

class CrudUtil
{
    /*
     * Metodo que retorna um array com o nome dos campos de formulários
     * retorna Array
     */

    public function getParametrosForm($objForm)
    {
        //Incia Variavel que receberá os campos
        $arrayCampos = [];

        //Recuperando Array de Campos
        $arrayForm = $objForm->getObjetos();

        //Monta Array de Retotno
        if (is_array($arrayForm)) {
            foreach ($arrayForm as $cfg) {
                $arrayCampos[] = $cfg->getNome();
            }
        }

        return $arrayCampos;
    }

    /**
     * Metodo que processa e retorna partes de uma clausula SQL de acordo com os filtros
     * returna String
     */
    public function getSqlFiltro($fil, $objForm, array $colunas)
    {
        //Incia Variavel que receberá as instruções Sql
        $sql = '';

        //Recuperando Array de Campos
        $arrayForm = $objForm->getObjetos();

        //Intercepta busca geral
        //Monta Sql de Retotno
        if (is_array($arrayForm)) {
            foreach ($arrayForm as $cFG) {
                //$alias = ($cFG->getAliasSql() == '') ? '' : $cFG->getAliasSql() . '.';

               //$sql .= $fil->getStringSql($cFG->getNome(), $alias . $cFG->getNome(), $cFG->getProcesarComo());
            }
        }
        
        $sql.= $this->sqlBuscaGeral($colunas);

        return $sql;
    }

    private function sqlBuscaGeral($colunas)
    {
        $buscaGral = filter_input(INPUT_GET, 'sisBuscaGeral');

        $sql = '';
        
        if ($buscaGral) {
            $sql = ' AND (';

            $campos = str_replace(',', '|', $buscaGral);

            $total = count($colunas);
            $cont = 0;
            foreach (array_keys($colunas) as $coluna) {
                $cont++;
                $sql.= $coluna . " REGEXP '".$campos."'";
                
                $sql.= $total == $cont ? '' : ' OR ';
            }
            
            $sql.= ') ';
        }        

        return $sql;
    }

    /**
     * Receber uma string de parametros e o objetoform e processa-os retornando um vetor com os paremtros prontos para a inserção
     * retorna Array
     */
    public function getSqlInsertUpdate(Form $objForm, $sql)
    {
        //Instancia Classe de Parse SQL
        $parseSql = new \ParseSql();

        //Tipo de Interpretação
        $tipoSql = strtoupper(substr(trim($sql), 0, 6));

        if ($tipoSql == "INSERT" or $tipoSql == "REPLAC") {
            $arrayParametros = $parseSql->getAtributosInsert($sql);
        } elseif ($tipoSql == "UPDATE") {
            $arrayParametros = $parseSql->getAtributosUpdate($sql);
        }

        //Incia Variavel que receberá o Sql
        $arraySql = array();

        //Recuperando Array de Campos
        $arrayForm = $objForm->getObjetos();

        if (is_array($arrayParametros)) {
            $arrayParametros = array_map("trim", $arrayParametros);

            foreach ($arrayParametros as $nomeParametro) {
                if (array_key_exists($nomeParametro, $arrayForm)) {
                    $Objeto = $arrayForm[$nomeParametro];

                    $arraySql[] = $objForm->get($nomeParametro, $Objeto->getObrigatorio(), $Objeto->getProcessarComo());
                } else {
                    $valor = $objForm->get($nomeParametro, false);

                    if ($valor . '' != '') {
                        $arraySql[] = $valor;
                    } else {
                        $arraySql[] = 'NULL';
                    }
                }
            }
        }

        return $arraySql;
    }

    /**
     * Converte parametros de arrays para uma super global
     * $parametrosForm = Array de Nomes de Campos
     * $parametrosSql  = Array de Valores de Campos
     * $chave          = Chave Identificadora
     * $metodo         = POST, GET
     * return void
     */
    public function getParametrosMetodo($parametrosForm, $parametrosSql, $chave, $metodo)
    {
        //Cria Array de Processamento
        $arrayProcessamento = array();

        //Cria Array Para Converssão em Super Global
        foreach ($parametrosForm as $valor) {
            if ($valor == "cod") {
                $arrayProcessamento[$valor] = $parametrosSql[$chave];
            } else {
                $arrayProcessamento[$valor] = $parametrosSql[$valor];
            }
        }

        //Extrai Variaveis para o metodo desejado
        $this->extractVar($arrayProcessamento, $metodo);
    }

    public function extractVar($array, $metodo)
    {
        if (is_array($array)) {
            $metodo = strtoupper($metodo);

            switch ($metodo) {
                case "GET":
                    foreach ($array as $chave => $valor) {
                        $_GET[$chave] = $valor;
                    }
                    break;

                case "POST":
                    foreach ($array as $chave => $valor) {
                        $_POST[$chave] = $valor;
                    }
                    break;

                case "REQUEST":
                    foreach ($array as $chave => $valor) {
                        $_REQUEST[$chave] = $valor;
                    }
                    break;
            }
        }
    }

}
