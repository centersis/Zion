<?php

/**
 *  @author Pablo Vanni - pablovanni@gmail.com
 *  Classe de utilidades - Usanda na manutenção básica
 *  Atualizada em 14-10-2014 por Pablo Vanni
 */

namespace Zion\Crud;

class CrudUtil
{

    /*
     * Metodo que retorna um array com o nome dos campos de formulários
     * retorna Array
     */
    public function getParametrosForm(Form $objForm)
    {
        //Incia Variavel que receberá os campos
        $arrayCampos = [];

        //Recuperando Array de Campos
        $arrayForm = $objForm->getBufferCFG();

        //Monta Array de Retotno
        if(is_array($arrayForm))
        {
            foreach($arrayForm as $cfg)
            {
                $arrayCampos[] = $cfg['Nome'];
            }
        }

        return $arrayCampos;
    }
    
    /**
     * Metodo que processa e retorna partes de uma clausula SQL de acordo com os filtros
     * returna String
     */
    public function getSqlFiltro(Filtrar $fil, Form $objForm)
    {
        //Incia Variavel que receberá as instruções Sql
        $sql = "";

        //Recuperando Array de Campos
        $arrayForm = $objForm->getBufferCFG();

        //Monta Sql de Retotno
        if (is_array($arrayForm)) {
            foreach ($arrayForm as $cFG) {
                $alias = ($cFG['AliasSql'] == '') ? "" : $cFG['AliasSql'] . ".";

                $sql .= $fil->getStringSql($cFG['Nome'], $alias . $cFG['Nome'], $cFG['ProcessarComo']);
            }
        }

        return $sql;
    }

    /**
     * Receber uma string de parametros e o objetoform e processa-os retornando um vetor com os paremtros prontos para a inserção
     * retorna Array
     */
    public function getSqlInsertUpdate(FormCampos $objForm, $sql)
    {
        //Instancia Classe de Parse SQL
        $parseSql = new \Zion\Crud\ParseSql();

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
        $arrayForm = $objForm->getBufferCFG();

        if (is_array($arrayParametros)) {
            $arrayParametros = array_map("trim", $arrayParametros);

            foreach ($arrayParametros as $nomeParametro) {
                if (array_key_exists($nomeParametro, $arrayForm)) {
                    $DadosCampo = $arrayForm[$nomeParametro];

                    $podeSerVazio = $DadosCampo['Obrigatorio'] === true ? false : true;
                    $arraySql[] = $objForm->get($nomeParametro, $podeSerVazio, $DadosCampo['ProcessarComo']);
                } else {
                    $valor = $objForm->get($nomeParametro, true);

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
