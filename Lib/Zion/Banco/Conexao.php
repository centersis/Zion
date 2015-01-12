<?php

/**
 * @author Pablo Vanni - pablovanni@gmail.com
 * @since 15/12/2014
 */

namespace Zion\Banco;

use Doctrine\Common\ClassLoader;

class Conexao
{

    private static $transaction;
    public static $link = [];
    public static $instancia = [];
    private $banco;
    private $arrayExcecoes = [];
    private $linhasAfetadas = 0;
    //Atributos de Log
    private $conteinerSql = []; //Conteiner que irá receber as Intruções Sql Ocultas Ou Não
    private $logOculto = false;   //Indicador - Indica se o tipo de log deve ser ou não oculto
    private $gravarLog = true;    //Indicador - Indica se o log deve ou não ser gravado
    private $interceptaSql = false;   //Indicador - Indica se o sql seve ou não ser inteceptado

    /**
     * Inicia uma conexão com o banco de dados, se os parametros opcionais não 
     * forem informados o metodo então tenta achar os parametros provenientes
     * do arquivo de configuração do sistema
     * @param string $banco
     * @param string $host
     * @param string $usuario
     * @param string $senha
     * @param string $driver
     */

    private function __construct($banco, $host = '', $usuario = '', $senha = '', $driver = '')
    {
        require_once SIS_FM_BASE . 'Lib/vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';

        $classLoader = new ClassLoader('Doctrine', SIS_FM_BASE . 'Lib/vendor/doctrine/dbal/lib');

        $classLoader->register();

        $this->banco = $banco;

        $this->arrayExcecoes[0] = "Conexão: Problemas com o servidor impedem a conexão com o banco de dados.<br>";
        $this->arrayExcecoes[1] = "Conexão: Problemas ao executar a clausula SQL.<br>";
        $this->arrayExcecoes[2] = "Conexão: ResultSet inválido.";
        $this->arrayExcecoes[3] = "Conexão: A query SQL esta vazia.";
        $this->arrayExcecoes[4] = "Conexão: Array de querys inválido.";

        if ($host) {
            $cHost = $host;
            $cUsuario = $usuario;
            $cSenha = $senha;
            $cBanco = $banco;
            $cDriver = $driver;
        } else {

            $namespace = '\\' . SIS_ID_NAMESPACE_PROJETO . '\\Config';

            $cHost = $namespace::$SIS_CFG['bases'][$banco]['host'];
            $cUsuario = $namespace::$SIS_CFG['bases'][$banco]['usuario'];
            $cSenha = $namespace::$SIS_CFG['bases'][$banco]['senha'];
            $cBanco = $namespace::$SIS_CFG['bases'][$banco]['banco'];
            $cDriver = $namespace::$SIS_CFG['bases'][$banco]['driver'];
        }

        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = [
            'dbname' => $cBanco,
            'user' => $cUsuario,
            'password' => $cSenha,
            'host' => $cHost,
            'driver' => $cDriver,
            'charset' => 'utf8',
            'wrapperClass' => 'Doctrine\DBAL\Portability\Connection',
            'portability' => \Doctrine\DBAL\Portability\Connection::PORTABILITY_ALL,
            'fetch_case' => \PDO::CASE_LOWER,
            'driverOptions' => [
                1002 => 'SET NAMES utf8']
        ];

        self::$link[$banco] = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    }

    /**
     * Retorna uma instancia de conexão com o banco de dados
     * @param string $banco
     * @return \Doctrine\DBAL\Connection
     */
    public function link($banco = \NULL)
    {
        return self::$link[$banco ? $banco : $this->banco];
    }

    /**
     * Retorna uma mensagem de erro de acordo com o código informado
     * @param int $cod
     * @return string
     */
    private function getExcecao($cod)
    {
        return "Erro - " . $this->arrayExcecoes[$cod];
    }

    public function setInterceptaSql($valor)
    {
        $this->interceptaSql = $valor;
    }

    public function setGravarLog($valor)
    {
        $this->gravarLog = $valor;
    }

    public function setLogOculto($valor)
    {
        $this->logOculto = $valor;
    }

    private function setConteinerSql($valor)
    {
        if ($this->gravarLog == true) {
            if ($this->logOculto === true) {
                $this->conteinerSql['OcultoSim'][] = $valor;
            } else {
                $this->conteinerSql['OcultoNao'][] = $valor;
            }
        }
    }

    //Recupera Conteiner Sql
    public function getConteinerSql()
    {
        return $this->conteinerSql;
    }

    public function resetLog()
    {
        $this->conteinerSql = array();
        $this->logOculto = false;
        $this->gravarLog = true;
        $this->interceptaSql = true;
    }

    /**
     * Retorna o número de linhas afetadas por uma clausula sql, podendo ser ela:
     * select, insert, update ou delete
     * @return int
     */
    public function getLinhasAfetadas()
    {
        return $this->linhasAfetadas;
    }

    /**
     * Cria uma conexão com o banco de dados MYSQL (SINGLETON)
     * @param string $banco
     * @return Conexao
     */
    public static function conectar($banco = 'padrao')
    {
        $bancoMaiusculo = empty($banco) ? 'padrao' : \strtolower($banco);

        if (!isset(self::$instancia[$bancoMaiusculo])) {
            self::$instancia[$bancoMaiusculo] = new Conexao($bancoMaiusculo);
        }

        return self::$instancia[$bancoMaiusculo];
    }

    /**
     * Retorna um link de conexão com o banco de dados após a informação dos
     * paremetros nescessários
     * @param string $host
     * @param string $banco
     * @param string $usuario
     * @param string $senha
     * @param string $driver
     * @return Conexao
     */
    public static function conectarManual($host, $banco, $usuario, $senha, $driver)
    {
        if (!isset(self::$instancia[$banco])) {
            self::$instancia[$banco] = new Conexao($banco, $host, $usuario, $senha, $driver);
        }

        return self::$instancia[$banco];
    }

    /**
     * Fecha a conexão co banco de dados
     * @param string $banco
     */
    public function fecharConexao($banco = \NULL)
    {
        self::$link[$banco ? $banco : $this->banco]->close();
    }

    /**
     * Executa uma string sql ou um objeto querybuilder, retorna um ResultSet
     * em caso de SELECT ou o número de linhas afetadas em caso de Insert
     * Update e Delete 
     * @param \Doctrine\DBAL\Query\QueryBuilder $sql
     * @return ResultSet
     * @throws \Exception
     */
    public function executar($sql)
    {
        if (empty($sql)) {
            throw new \Exception($this->getExcecao(3));
        }
        
        $this->linhasAfetadas = 0;

        if (\is_object($sql)) {

            $tipo = \strtoupper(\substr($sql, 0, 5));

            if ($tipo === 'SELECT') {
                $resultSet = $sql->execute();
                $this->linhasAfetadas = $this->nLinhas($resultSet);
                return $resultSet;
            } else {

                $resultSet = $sql->execute();
                $this->linhasAfetadas = $resultSet;
                return $resultSet;
            }
        }

        $executa = self::$link[$this->banco]->query($sql);
        $this->linhasAfetadas = $this->nLinhas($executa);

        return $executa;
    }

    /**
     * Retorna o ultimo ID
     * @param string $campo
     */
    public function ultimoId($campo = null)
    {
        return $this->link()->lastInsertId($campo);
    }

    /**
     * Executa um conjunto de querys iformadas em um array, tambem aceita
     * objeto query builder
     * @param array $arraySql
     * @param boolean $transaction
     * @throws Exception
     */
    public function executarArray($arraySql, $transaction = true)
    {
        if (!\is_array($arraySql)) {
            throw new \Exception($this->getExcecao(4));
        }

        if ($transaction == true) {
            $this->startTransaction();
        }

        foreach ($arraySql as $sql) {

            self::$link[$this->banco]->query($sql);

            if ($this->interceptaSql == true) {
                $this->setConteinerSql($sql);
            }
        }

        if ($transaction == true) {
            $this->stopTransaction();
        }
    }

    /**
     * Executando um resultSet e retornando um array
     * @param object $resultSet
     * @param string $estilo
     * @return array
     * @throws \Exception
     */
    public function linha($resultSet, $estilo = 4)
    {
        if (!\is_object($resultSet)) {
            throw new \Exception($this->getExcecao(2));
        }

        $nLinhas = $this->nLinhas($resultSet);

        if ($nLinhas > 0) {
            $linhas = $resultSet->fetchAll($estilo);
            return \array_map("trim", $linhas[0]);
        } else {
            return [];
        }
    }

    /**
     * Executa uma string Sql ou um objeto query builder e retona um array
     * com os resultados da clausula select
     * @param string|object $sql
     * @param string $estilo
     * @return array
     */
    public function execLinha($sql, $estilo = null)
    {
        if (\is_object($sql)) {
            $resultSet = $sql->execute();
            return $this->linha($resultSet, $estilo);
        }

        $resultSet = $this->executar($sql);

        return $this->linha($resultSet, $estilo);
    }

    /**
     * Executa uma string Sql ou um objeto query builder e retona um array
     * com os resultados da clausula select
     * @param string|object $sql
     * @return array
     */
    public function execLinhaArray($sql)
    {
        if (\is_object($sql)) {
            $resultSet = $sql->execute();
            return $this->linha($resultSet, 2);
        }

        $resultSet = $this->executar($sql);

        return $this->linha($resultSet, 2);
    }

    /**
     * Executa uma string Sql ou um objeto query builder e retorna o 
     * valor contido na posição especificada pelo parametro $posicao
     * @param string|object $sql
     * @param string|int $posicao
     * @return string
     * @throws \Exception
     */
    public function execRLinha($sql, $posicao = 0)
    {
        $resultSet = $this->executar($sql);

        $array = $this->linha($resultSet);

        if (\key_exists($posicao, $array)) {
            return $array[$posicao];
        } else {
            throw new \Exception('Conexão: Posição ' . $posicao . ' informada não foi encontrada!');
        }

        return \current($array);
    }

    /**
     * Executa uma string Sql ou um objeto query builder e retorna o 
     * valor contido na posição especificada pelos parametros $posicao
     * e $indice caso forem especificados
     * @param string|object $sql
     * @param string $posicao
     * @param string $indice
     * @return array
     * @throws \Exception
     */
    public function paraArray($sql, $posicao = null, $indice = null)
    {
        if (\is_object($sql)) {
            $ret = $sql->execute();
        } else {
            $ret = $this->executar($sql);
        }

        if ($this->nLinhas($ret) > 0) {
            $rows = [];

            $posicao = \strtolower($posicao);
            $indice = \strtolower($indice);

            while ($row = $ret->fetch()) {
                if (empty($posicao)) {
                    if (empty($indice)) {
                        $rows[] = $row;
                    } else {

                        if (!\key_exists($indice, $row)) {
                            throw new \Exception("Conexão: Indice $indice não encontrado!");
                        }

                        $rows[$row[$indice]] = $row;
                    }
                } else {
                    if (empty($indice)) {
                        if (!\key_exists($posicao, $row)) {
                            throw new \Exception("Conexão: Posição $posicao não encontrada!");
                        }
                        $rows[] = $row[$posicao];
                    } else {

                        if (!\key_exists($indice, $row)) {
                            throw new \Exception("Conexão: Indice $indice não encontrado!");
                        }

                        $rows[$row[$indice]] = $row[$posicao];
                    }
                }
            }
            return $rows;
        } else {

            return [];
        }
    }

    /**
     * Retornando o número de resultados de um ResultSet
     * @param resultset $resultSet
     * @return int
     * @throws \Exception
     */
    public function nLinhas($resultSet)
    {
        if (!\is_object($resultSet)) {
            throw new \Exception($this->getExcecao(2));
        }

        return (int) $resultSet->rowCount();
    }

    /**
     * Executa uma string Sql ou um objeto query builder e retorna o número
     * de linhas afetadas pela consulta
     * @param string $sql
     * @return int
     */
    public function execNLinhas($sql)
    {
        if (\is_object($sql)) {

            $rs = $sql->execute();
            $linhas = $this->nLinhas($rs);

            return $linhas;
        }

        return $this->nLinhas($this->executar($sql));
    }

    /**
     * Retorna o maior resultado de uma coluna de uma tabela do banco de dados
     * @param string $tabela
     * @param string $idTabela
     * @return string
     */
    public function maiorId($tabela, $idTabela)
    {
        $qb = $this->link()->createQueryBuilder();
        $qb->select($qb->expr()->max($idTabela))
                ->from($tabela, '');

        return $this->execRLinha($qb);
    }

    /**
     * Verifica se determinado valor existe em uma tabela especifica do banco 
     * de dados, o parametro inteiro indica se o valor informado deve ser
     * comparado como inteiro (true) ou string (false)
     * @param string $tabela
     * @param string $campo
     * @param string $valor
     * @param boolean $inteiro
     * @return type
     */
    public function existe($tabela, $campo, $valor, $inteiro = false)
    {
        $qb = $this->link()->createQueryBuilder();

        $sql = $qb->select($campo)
                ->from($tabela, '')
                ->where($qb->expr()->eq($campo, ':campo'))
                ->setParameter('campo', $inteiro ? $valor : $qb->expr()->literal($valor))
                ->setMaxResults(1);

        return($this->execNLinhas($sql) < 1) ? false : true;
    }

    /**
     * Inicia uma Transação
     */
    public function startTransaction()
    {
        if (self::$transaction < 1) {
            self::$link[$this->banco]->beginTransaction();
            self::$transaction = 1;
        } else {
            self::$transaction += 1;
        }
    }

    /**
     * Finaliza uma Transação - Commit se for vazio, senão Rollback
     * @param string $erro
     * @return boolean
     */
    public function stopTransaction($erro = '')
    {
        if (self::$transaction == 1) {
            if (!empty($erro)) {
                self::$link[$this->banco]->rollBack();
                self::$transaction -= 1;
                return false;
            } else {
                self::$link[$this->banco]->commit();
                self::$transaction -= 1;
                return true;
            }
        } else {
            self::$transaction -= 1;
        }
    }

}
