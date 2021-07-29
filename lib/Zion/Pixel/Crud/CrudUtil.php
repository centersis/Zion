<?php

namespace Zion\Pixel\Crud;

use Zion\Banco\Conexao;
use Zion\Pixel\Filtro\Filtrar;
use App\Ext\Arquivo\ArquivoUpload;
use Zion\Pixel\Form\MasterDetail\MasterDetail;
use Zion\Form\Form;
use Zion\Exception\ErrorException;

abstract class CrudUtil {

    protected $con;

    public function __construct($banco = '') {
        if (is_object($banco)) {
            $this->con = $banco;
        } else {
            $this->con = Conexao::conectar($banco);
        }
    }

    public function getParametrosGrid($objForm = null) {
        $fil = new Filtrar();

        if ($objForm) {
            $meusParametros = $this->getParametrosForm($objForm);
        } else {
            $meusParametros = [];
        }

        $hiddenParametros = $fil->getHiddenParametros($meusParametros);

        return array_merge($this->getParametrosPadroes(), $meusParametros, $hiddenParametros);
    }

    public function getParametrosPadroes() {
        return ["pa", "qo", "to", "sisBuscaGeral"];
    }

    public function setParametrosForm($objForm, $parametrosSql, $cod = 0, $cod1 = 0) {
        $arrayObjetos = $objForm->getObjetos();

        if ($cod and array_key_exists('cod', $arrayObjetos)) {
            $arrayObjetos['cod']->setValor($cod);
        }

        if ($cod1 and array_key_exists('cod1', $arrayObjetos)) {
            $arrayObjetos['cod1']->setValor($cod1);
        }

        if (is_array($arrayObjetos)) {
            foreach ($arrayObjetos as $nome => $objeto) {

                $chave = strtolower($nome);

                if (array_key_exists($chave, $parametrosSql)) {
                    $objeto->setValor($parametrosSql[$chave]);
                }
            }
        }
    }

    /*
     * Metodo que retorna um array com o nome dos campos de formulários
     * retorna Array
     */

    public function getParametrosForm($objForm) {
        //Incia Variavel que receberá os campos
        $arrayCampos = [];

        //Recuperando Array de Campos
        $arrayForm = $objForm->getObjetos();

        //Monta Array de Retotno
        if (is_array($arrayForm)) {
            foreach ($arrayForm as $cfg) {

                if (filter_input(INPUT_GET, 'sho' . $cfg->getNome()) == 'E') {
                    $arrayCampos[] = $cfg->getNome() . 'A';
                    $arrayCampos[] = $cfg->getNome() . 'B';
                    $arrayCampos[] = 'sho' . $cfg->getNome() . '';
                    $arrayCampos[] = 'sha' . $cfg->getNome() . '';
                } else {
                    $arrayCampos[] = $cfg->getNome();
                }
            }
        }

        return $arrayCampos;
    }

    /**
     * Metodo que processa e retorna partes de uma clausula SQL de acordo com os filtros
     * retorna String
     */
    public function getSqlFiltro($fil, $objForm, array $filtroDinamico, $queryBuilder) {
        if (is_object($objForm)) {
            $arrayForm = $objForm->getObjetos();

            if (is_array($arrayForm)) {
                foreach ($arrayForm as $cFG) {
                    if (method_exists($cFG, 'getAliasSql')) {
                        $alias = ($cFG->getAliasSql() == '') ? '' : $cFG->getAliasSql() . '.';
                    } else {
                        $alias = '';
                    }
                    $fil->getStringSql($cFG->getNome(), $alias . $cFG->getNome(), $queryBuilder);
                }
            }
        }

        $this->sqlBuscaGeral($filtroDinamico, $queryBuilder);
    }

    public function getSqlFiltroPorObjeto($fil, $objForm, $queryBuilder, $queryObject) {
        if (is_object($objForm)) {
            $arrayForm = $objForm->getObjetos();

            if (is_array($arrayForm)) {
                foreach ($arrayForm as $cFG) {
                    if (method_exists($cFG, 'getAliasSql')) {
                        $alias = ($cFG->getAliasSql() == '') ? '' : $cFG->getAliasSql() . '.';
                    } else {
                        $alias = '';
                    }
                    $fil->getStringSql($cFG->getNome(), $alias . $cFG->getNome(), $queryBuilder, $queryObject);
                }
            }
        }
    }

    protected function modoBuscaLIKE($filtroDinamico) {

        $buscaGeral = filter_input(INPUT_GET, 'sisBuscaGeral');

        if ($buscaGeral) {

            $qb = $this->con->qb();

            $sql = ' (';

            $campos = explode(',', $buscaGeral);

            $total = count($filtroDinamico);
            $totalCampos = count($campos);
            $cont = 0;

            foreach ($filtroDinamico as $coluna => $aliasSql) {

                $cont++;
                $alias = $aliasSql ? $aliasSql . '.' : '';

                $cont2 = 0;
                foreach ($campos as $valorCampo) {
                    $cont2++;

                    $sql .= $alias . $coluna . " LIKE " . $qb->expr()->literal('%' . $valorCampo . '%');

                    $sql .= $totalCampos == $cont2 ? '' : ' OR ';
                }

                $sql .= $total == $cont ? '' : ' OR ';
            }

            $sql .= ') ';

            return $sql;
        }
    }

    /**
     * 
     * @param type $filtroDinamico
     * @param Doctrine\DBAL\Query\QueryBuilder $queryBuilder
     * @param type $modoBusca
     */
    protected function sqlBuscaGeral($filtroDinamico, $queryBuilder) {
        $buscaGeral = \filter_input(\INPUT_GET, 'sisBuscaGeral');

        if ($buscaGeral) {


            $sql = $this->modoBuscaLIKE($filtroDinamico);
            $queryBuilder->andWhere($sql);
        }
    }

    /**
     * Receber uma string de parametros e o objetoform e processa-os retornando um vetor com os paremtros prontos para a inserção
     * retorna Array
     */
    public function insert($organograma, $tabela, array $campos, $objForm, array $ignorarObjetos = []) {

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
                throw new ErrorException('Parâmetro inválido, $objForm deve ser um Objeto ou um Array de valores!');
            }
        }

        $camposLimpos = \array_map("trim", $campos);
        $arrayParametros = \array_combine($camposLimpos, $camposLimpos);

        if ($objeto) {
            foreach ($arrayParametros as $nomeParametro) {

                if (\array_key_exists($nomeParametro, $arrayForm)) {

                    if (\method_exists($arrayForm[$nomeParametro], 'getDisabled')) {
                        if ($arrayForm[$nomeParametro]->getDisabled()) {
                            unset($arrayParametros[$nomeParametro]);
                            continue;
                        }
                    }

                    $arrayValores[] = $objForm->getSql($nomeParametro);
                    $arrayTipos[] = $objForm->getTipoPDO($nomeParametro);
                } else {
                    $arrayValores[] = \NULL;
                    $arrayTipos[] = \PDO::PARAM_NULL;
                }
            }
        } else {

            $form = new Form();

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
        }

        $camposVistoriados = $this->removeColchetes($arrayParametros);

        $this->con->startTransaction();

        $qb = $this->con->qb();

        $qb->insert($tabela);

        foreach ($camposVistoriados as $coluna) {
            $qb->setValue($coluna, '?');
        }

        foreach ($arrayValores as $chave => $valor) {

            $qb->setParameter($chave, $valor, $arrayTipos[$chave]);
        }

        $this->con->executar($qb);

        $codigo = $this->con->ultimoId();

        $this->tiposEspeciais($objeto, $arrayForm, $codigo, $ignorarObjetos);

        $this->con->stopTransaction();

        return $codigo;
    }

    public function update($organograma, $tabela, array $campos, $objForm, array $criterio, array $tipagemCriterio = [], array $ignorarObjetos = []) {
        $arrayValores = [];
        $arrayTipos = [];
        $linhasAfetadas = 0;

        $objeto = true;

        if (\is_object($objForm)) {
            $arrayForm = $objForm->getObjetos();
        } else {

            $objeto = false;

            if (\is_array($objForm)) {
                $arrayForm = $objForm;
            } else {
                throw new ErrorException('Parâmetro inválido, $objForm deve ser um Objeto ou um Array de valores!');
            }
        }

        $camposLimpos = \array_map("trim", $campos);
        $arrayParametros = \array_combine($camposLimpos, $camposLimpos);

        if ($objeto) {
            foreach ($arrayParametros as $nomeParametro) {

                if (\array_key_exists($nomeParametro, $arrayForm)) {

                    if (\method_exists($arrayForm[$nomeParametro], 'getDisabled')) {
                        if ($arrayForm[$nomeParametro]->getDisabled()) {
                            unset($arrayParametros[$nomeParametro]);
                            continue;
                        }
                    }

                    $arrayValores[] = $objForm->getSql($nomeParametro);
                    $arrayTipos[] = $objForm->getTipoPDO($nomeParametro);
                } else {
                    $arrayValores[] = \NULL;
                    $arrayTipos[] = \PDO::PARAM_NULL;
                }
            }
        } else {

            $form = new Form();

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
        }

        $camposVistoriados = $this->removeColchetes($arrayParametros);

        $this->con->startTransaction();

        $qb = $this->con->qb();

        $qb->update($tabela);

        foreach ($camposVistoriados as $coluna) {
            $qb->set($coluna, '?');
        }

        if (\is_array($arrayValores) and!empty($arrayValores)) {

            foreach ($arrayValores as $chave => $valor) {

                $qb->setParameter($chave, $valor, $arrayTipos[$chave]);
            }

            $pos = $chave;
            foreach ($criterio as $campo => $valor) {
                $pos++;

                $tipo = \PDO::PARAM_INT;
                if (\array_key_exists($campo, $tipagemCriterio)) {
                    $tipo = $tipagemCriterio[$campo];
                }

                $qb->andWhere($qb->expr()->eq($campo, '?'))
                        ->setParameter($pos, $valor, $tipo);
            }

            $linhasAfetadas = $this->con->executar($qb);
        }

        $codigo = \current($criterio);

        $this->tiposEspeciais($objeto, $arrayForm, $codigo, $ignorarObjetos);

        $this->con->stopTransaction();

        return $linhasAfetadas;
    }

    protected function tiposEspeciais($objeto, $arrayForm, $codigo, $ignorarObjetos) {
        if ($objeto) {

            foreach ($arrayForm as $objeto) {

                $tipoBase = $objeto->getTipoBase();

                if (\in_array($tipoBase, $ignorarObjetos)) {
                    continue;
                }

                switch ($tipoBase) {

                    case 'upload':

                        $upload = new ArquivoUpload($objeto->getOrganogramaCod(), $objeto->getConexao());

                        if (!$objeto->getCodigoReferencia()) {
                            $objeto->setCodigoReferencia($codigo);
                        }

                        $upload->sisUpload($objeto);
                        break;

                    case 'masterDetail':

                        $masterDetail = new MasterDetail();

                        if (!$objeto->getCodigoReferencia()) {
                            $objeto->setCodigoReferencia($codigo);
                        }

                        $masterDetail->gravar($objeto);

                        break;
                }
            }
        }
    }

    public function delete($organograma, $tabela, array $criterio, array $tipagemCriterio = []) {

        $qb = $this->con->qb();

        $qb->delete($tabela, '');

        $pos = 0;
        foreach ($criterio as $campo => $valor) {
            $pos++;

            $tipo = \PDO::PARAM_INT;
            if (\array_key_exists($campo, $tipagemCriterio)) {
                $tipo = $tipagemCriterio[$campo];
            }

            $qb->andWhere($qb->expr()->eq($campo, '?'))
                    ->setParameter($pos, $valor, $tipo);
        }

        return $this->con->executar($qb);
    }

    public function masterDetail($objForm, $codigo) {

        $arrayForm = $objForm->getObjetos();

        foreach ($arrayForm as $objeto) {

            $tipoBase = $objeto->getTipoBase();

            switch ($tipoBase) {

                case 'masterDetail':

                    $masterDetail = new MasterDetail();
                    if (!$objeto->getCodigoReferencia()) {
                        $objeto->setCodigoReferencia($codigo);
                    }
                    $masterDetail->gravar($objeto);
                    break;
            }
        }
    }

    /**
     * Receber uma string de parametros e o objetoform e processa-os retornando um vetor com os paremtros prontos para a inserção
     * retorna Array
     */
    public function getSqlInsertUpdate(Form $objForm, $sql) {
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
    public function getParametrosMetodo($parametrosForm, $parametrosSql, $chave, $metodo) {
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

    public function extractVar($array, $metodo) {
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

    public function startTransaction() {
        $this->con->startTransaction();
    }

    public function stopTransaction($erro = '') {
        $this->con->stopTransaction($erro);
    }

    protected function removeColchetes($campos) {
        if (\is_array($campos)) {
            foreach ($campos as $chave => $campo) {
                $campos[$chave] = \str_replace('[]', '', $campo);
            }
        } else {
            $campos = \str_replace('[]', '', $campos);
        }
        return $campos;
    }

    public function getColunasDinamicas($colunas, $configuracaoPersonalizada = true) {
        $usuarioGridColunas = '';

        if (\defined('MODULO') and $configuracaoPersonalizada) {

            $qbModulo = $this->con->qb();

            $qbModulo->select('modulo_cod')
                    ->from('_modulo', '')
                    ->where($qbModulo->expr()->eq('modulo_nome', $qbModulo->expr()->literal(\MODULO)));

            $moduloCod = $this->con->execRLinha($qbModulo);

            $ufc = \filter_input(\INPUT_GET, 'sisUFC');

            $qb = $this->con->qb();

            if (is_numeric($ufc)) {

                $qb->select('usuario_filtro_colunas')
                        ->from('_usuario_filtro', '')
                        ->andWhere($qb->expr()->eq('usuario_filtro_cod', ':usuario_filtro_cod'))
                        ->setParameter('usuario_filtro_cod', $ufc);
            } else {

                $qb->select('usuario_grid_colunas')
                        ->from('_usuario_grid', '');
            }

            $qb->andWhere($qb->expr()->eq('usuario_cod', ':usuario_cod'))
                    ->andWhere($qb->expr()->eq('modulo_cod', ':modulo_cod'))
                    ->andWhere($qb->expr()->eq('organograma_cod', ':organograma_cod'))
                    ->setParameter('usuario_cod', $_SESSION['usuario_cod'])
                    ->setParameter('modulo_cod', $moduloCod)
                    ->setParameter('organograma_cod', $_SESSION['organograma_cod']);

            $usuarioGridColunas = $this->con->execRLinha($qb);
        }

        $retorno = [];
        if ($usuarioGridColunas) {

            /* Dentro deste if é criado um array seguindo a ordem das colunas gravadas no banco */
            $campos = \explode(',', $usuarioGridColunas);
            $b1 = [];
            $b2 = [];
            foreach ($colunas as $cod => $dados) {

                $marcado = \in_array($cod, $campos) ? 'S' : 'N';

                if ($marcado === 'S') {
                    if (\is_array($dados)) {
                        $b1[$cod] = [$dados[0], $dados[1], $marcado];
                    } else {
                        $b1[$cod] = [$dados, $cod, $marcado];
                    }
                } else {
                    if (\is_array($dados)) {
                        $b2[$cod] = [$dados[0], $dados[1], $marcado];
                    } else {
                        $b2[$cod] = [$dados, $cod, $marcado];
                    }
                }
            }

            if (!empty($campos)) {
                $b3 = [];
                foreach ($campos as $chaveColuna) {
                    $b3[$chaveColuna] = $b1[$chaveColuna];
                }
                $retorno = \array_merge($b3, $b2);
            } else {
                $retorno = \array_merge($b1, $b2);
            }
        } else {
            foreach ($colunas as $cod => $dados) {

                if (\is_array($dados)) {
                    $retorno[$cod] = $dados;
                } else {
                    $retorno[$cod] = [$dados, $cod, 'S'];
                }
            }
        }

        return $retorno;
    }

    public function getColunasDinamicasSql($colunas) {
        $buffer = [];
        foreach ($colunas as $dados) {

            if ($dados[2] === 'S' or $dados[2] === true) {
                $buffer[] = $dados[1];
            }
        }

        return $buffer;
    }

    public function colunasNecessarias($colunasDinamicas, $necessarias) {
        foreach ($colunasDinamicas as $chave => $valor) {

            if ($valor == '') {
                unset($colunasDinamicas[$chave]);
            }
        }

        if (!\is_array($necessarias) or empty($necessarias)) {
            return $colunasDinamicas;
        }

        foreach ($necessarias as $coluna) {
            if (!in_array($coluna, $colunasDinamicas)) {
                \array_unshift($colunasDinamicas, $coluna);
            }
        }

        return $colunasDinamicas;
    }

}
