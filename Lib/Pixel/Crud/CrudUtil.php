<?php

/*

  Sappiens Framework
  Copyright (C) 2014, BRA Consultoria

  Website do autor: www.braconsultoria.com.br/sappiens
  Email do autor: sappiens@braconsultoria.com.br

  Website do projeto, equipe e documentação: www.sappiens.com.br

  Este programa é software livre; você pode redistribuí-lo e/ou
  modificá-lo sob os termos da Licença Pública Geral GNU, conforme
  publicada pela Free Software Foundation, versão 2.

  Este programa é distribuído na expectativa de ser útil, mas SEM
  QUALQUER GARANTIA; sem mesmo a garantia implícita de
  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
  detalhes.

  Você deve ter recebido uma cópia da Licença Pública Geral GNU
  junto com este programa; se não, escreva para a Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
  02111-1307, USA.

  Cópias da licença disponíveis em /Sappiens/_doc/licenca

 */

/**
 *  @author Pablo Vanni - pablovanni@gmail.com
 *  Classe de utilidades - Usanda na manutenção básica
 *  Atualizada em 14-10-2014 por Pablo Vanni
 */

namespace Pixel\Crud;

class CrudUtil
{

    public function getParametrosGrid($objForm)
    {
        $fil = new \Pixel\Filtro\Filtrar();

        $meusParametros = $this->getParametrosForm($objForm);

        $hiddenParametros = $fil->getHiddenParametros($meusParametros);

        return \array_merge($this->getParametrosPadroes(), $meusParametros, $hiddenParametros);
    }

    public function getParametrosPadroes()
    {
        return ["pa", "qo", "to"];
    }

    public function setParametrosForm($objForm, $parametrosSql, $cod = 0)
    {
        $arrayObjetos = $objForm->getObjetos();

        if ($cod) {
            $arrayObjetos['cod']->setValor($cod);
        }

        if (\is_array($arrayObjetos)) {
            foreach ($arrayObjetos as $nome => $objeto) {

                $chave = \strtolower($nome);

                if (\array_key_exists($chave, $parametrosSql)) {
                    $objeto->setValor($parametrosSql[$chave]);
                }
            }
        }
    }

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
        if (\is_array($arrayForm)) {
            foreach ($arrayForm as $cfg) {
                $arrayCampos[] = 'n' . $cfg->getNome();
            }
        }

        return $arrayCampos;
    }

    /**
     * Metodo que processa e retorna partes de uma clausula SQL de acordo com os filtros
     * returna String
     */
    public function getSqlFiltro($fil, $objForm, array $filtroDinamico, $queryBuilder)
    {
        //Recuperando Array de Campos
        $arrayForm = $objForm->getObjetos();

        //Intercepta busca geral
        //Monta Sql de Retotno
        if (\is_array($arrayForm)) {
            foreach ($arrayForm as $cFG) {
                if (\method_exists($cFG, 'getAliasSql')) {
                    $alias = ($cFG->getAliasSql() == '') ? '' : $cFG->getAliasSql() . '.';
                } else {
                    $alias = '';
                }
                $fil->getStringSql($cFG->getNome(), $alias . $cFG->getNome(), $queryBuilder);
            }
        }

        $this->sqlBuscaGeral($filtroDinamico, $queryBuilder);
    }

    private function sqlBuscaGeral($filtroDinamico, $queryBuilder)
    {
        $buscaGral = \filter_input(\INPUT_GET, 'sisBuscaGeral');

        if ($buscaGral) {
            $sql = ' (';

            $campos = \str_replace(',', '|', $buscaGral);

            $total = \count($filtroDinamico);
            $cont = 0;
            foreach ($filtroDinamico as $coluna => $aliasSql) {
                $cont++;

                $alias = $aliasSql ? $aliasSql . '.' : '';

                $sql.= $alias . $coluna . " REGEXP '" . $campos . "'";

                $sql.= $total == $cont ? '' : ' OR ';
            }

            $sql.= ') ';

            $queryBuilder->where($sql);
        }
    }

    /**
     * Receber uma string de parametros e o objetoform e processa-os retornando um vetor com os paremtros prontos para a inserção
     * retorna Array
     */
    public function insert($tabela, array $campos, $objForm)
    {
        $con = \Zion\Banco\Conexao::conectar();

        $arrayValores = [];
        $arrayTipos = [];

        $objeto = true;

        if (\is_object($objForm)) {
            $arrayForm = $objForm->getObjetos();
        } else {

            $objeto = false;

            if (\is_array($objForm)) {
                $arrayForm = $arrayForm;
            } else {
                throw new \Exception('Parâmetro inválido, $objForm deve ser um Objeto ou um Array de valores!');
            }
        }

        $arrayParametros = \array_map("trim", $campos);

        if ($objeto) {
            foreach ($arrayParametros as $nomeParametro) {
                if (\array_key_exists($nomeParametro, $arrayForm)) {

                    $arrayValores[] = $objForm->getSql($nomeParametro);
                    $arrayTipos[] = $objForm->getTipoPDO($nomeParametro);
                } else {
                    $arrayValores[] = \NULL;
                    $arrayTipos[] = \PDO::PARAM_NULL;
                }
            }
        } else {

            $form = new \Zion\Form\Form();

            foreach ($arrayParametros as $nomeParametro) {
                if (\array_key_exists($nomeParametro, $arrayForm)) {

                    $form->set($nomeParametro, \current($arrayForm[$nomeParametro]), \key($arrayForm[$nomeParametro]));
                    $arrayValores[] = $objForm->getSql($nomeParametro);
                    $arrayTipos[] = $objForm->getTipoPDO($nomeParametro);
                } else {
                    $arrayValores[] = \NULL;
                    $arrayTipos[] = \PDO::PARAM_NULL;
                }
            }
        }

        $camposVistoriados = $this->removeColchetes($campos);

        $con->startTransaction();

        $qb = $con->link()->createQueryBuilder();

        $qb->insert($tabela);

        foreach ($camposVistoriados as $coluna) {
            $qb->setValue($coluna, '?');
        }

        foreach ($arrayValores as $chave => $valor) {

            $qb->setParameter($chave, $valor, $arrayTipos[$chave]);
        }

        $con->executar($qb);

        $uid = $con->ultimoId();

        /**
         * Tipos Especiais
         */
        foreach ($arrayForm as $objeto) {

            $tipoBase = $objeto->getTipoBase();

            switch ($tipoBase) {

                case 'upload':

                    $upload = new \Pixel\Arquivo\ArquivoUpload();
                    $objeto->setCodigoReferencia($uid);
                    $upload->sisUpload($objeto);
                    break;

                case 'masterDetail':

                    $masterDetail = new \Pixel\Form\MasterDetail\MasterDetail();
                    $objeto->setCodigoReferencia($uid);
                    $masterDetail->gravar($objeto);
                    break;
            }
        }

        $con->stopTransaction();

        return $uid;
    }

    public function update($tabela, array $campos, $objForm, $chavePrimaria, $valorChavePrimaria = 0)
    {
        $con = \Zion\Banco\Conexao::conectar();
        $upload = new \Pixel\Arquivo\ArquivoUpload();

        $arrayValores = [];
        $arrayTipos = [];

        $objeto = true;

        if (\is_object($objForm)) {
            $arrayForm = $objForm->getObjetos();
        } else {

            $objeto = false;

            if (\is_array($objForm)) {
                $arrayForm = $objForm;
            } else {
                throw new \Exception('Parâmetro inválido, $objForm deve ser um Objeto ou um Array de valores!');
            }
        }

        $arrayParametros = \array_map("trim", $campos);

        if ($objeto) {
            foreach ($arrayParametros as $nomeParametro) {
                if (\array_key_exists($nomeParametro, $arrayForm)) {

                    $arrayValores[] = $objForm->getSql($nomeParametro);
                    $arrayTipos[] = $objForm->getTipoPDO($nomeParametro);
                } else {
                    $arrayValores[] = \NULL;
                    $arrayTipos[] = \PDO::PARAM_NULL;
                }
            }

            $codigo = $objForm->get('cod');
        } else {

            $form = new \Zion\Form\Form();

            foreach ($arrayParametros as $nomeParametro) {
                if (\array_key_exists($nomeParametro, $arrayForm)) {

                    $form->set($nomeParametro, \current($arrayForm[$nomeParametro]), \key($arrayForm[$nomeParametro]));
                    $arrayValores[] = $form->getSql($nomeParametro);
                    $arrayTipos[] = $form->getTipoPDO($nomeParametro);
                } else {
                    $arrayValores[] = \NULL;
                    $arrayTipos[] = \PDO::PARAM_NULL;
                }
            }

            $codigo = $valorChavePrimaria;
        }

        $camposVistoriados = $this->removeColchetes($campos);

        $con->startTransaction();

        $qb = $con->link()->createQueryBuilder();

        $qb->update($tabela);

        foreach ($camposVistoriados as $coluna) {
            $qb->set($coluna, '?');
        }

        foreach ($arrayValores as $chave => $valor) {

            $qb->setParameter($chave, $valor, $arrayTipos[$chave]);
        }

        $qb->where($qb->expr()->eq($chavePrimaria, '?'));

        $qb->setParameter($chave + 1, $codigo, \PDO::PARAM_INT);

        $linhasAfetadas = $con->executar($qb);


        /**
         * Tipos Especiais
         */
        if ($objeto) {
            foreach ($arrayForm as $objeto) {

                $tipoBase = $objeto->getTipoBase();

                switch ($tipoBase) {

                    case 'upload':

                        $upload = new \Pixel\Arquivo\ArquivoUpload();
                        $objeto->setCodigoReferencia($codigo);
                        $upload->sisUpload($objeto);
                        break;

                    case 'masterDetail':

                        $masterDetail = new \Pixel\Form\MasterDetail\MasterDetail();
                        $objeto->setCodigoReferencia($codigo);
                        $masterDetail->gravar($objeto);
                        break;
                }
            }
        }        

        $con->stopTransaction();

        return $linhasAfetadas;
    }

    public function delete($tabela, $codigo, $chavePrimaria)
    {
        $con = \Zion\Banco\Conexao::conectar();

        $qb = $con->link()->createQueryBuilder();

        $qb->delete($tabela, '')
                ->where($qb->expr()->eq($chavePrimaria, ':cod'))
                ->setParameter(':cod', $codigo);

        return $con->executar($qb);
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
        $tipoSql = \strtoupper(\substr(\trim($sql), 0, 6));

        if ($tipoSql == "INSERT" or $tipoSql == "REPLAC") {
            $arrayParametros = $parseSql->getAtributosInsert($sql);
        } elseif ($tipoSql == "UPDATE") {
            $arrayParametros = $parseSql->getAtributosUpdate($sql);
        }

        //Incia Variavel que receberá o Sql
        $arrayValores = array();

        //Recuperando Array de Campos
        $arrayForm = $objForm->getObjetos();

        if (\is_array($arrayParametros)) {
            $arrayParametros = array_map("trim", $arrayParametros);

            foreach ($arrayParametros as $nomeParametro) {
                if (\array_key_exists($nomeParametro, $arrayForm)) {
                    $objeto = $arrayForm[$nomeParametro];

                    $arrayValores[] = $objForm->get($nomeParametro, $objeto->getObrigatorio(), $objeto->getProcessarComo());
                } else {
                    $valor = $objForm->get($nomeParametro, false);

                    if ($valor . '' != '') {
                        $arrayValores[] = $valor;
                    } else {
                        $arrayValores[] = 'NULL';
                    }
                }
            }
        }

        return $arrayValores;
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
                if (\key_exists($valor, $arrayProcessamento)) {
                    $arrayProcessamento[$valor] = $parametrosSql[$chave];
                }
            } else {

                if (\key_exists($valor, $arrayProcessamento)) {
                    $arrayProcessamento[$valor] = $parametrosSql[$valor];
                }
            }
        }

        //Extrai Variaveis para o metodo desejado
        $this->extractVar($arrayProcessamento, $metodo);
    }

    public function extractVar($array, $metodo)
    {
        if (\is_array($array)) {
            $metodo = \strtoupper($metodo);

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

    private function removeColchetes($campos)
    {
        if (\is_array($campos)) {
            foreach ($campos as $chave => $campo) {
                $campos[$chave] = \str_replace('[]', '', $campo);
            }
        } else {
            $campos = \str_replace('[]', '', $campos);
        }
        return $campos;
    }

}
