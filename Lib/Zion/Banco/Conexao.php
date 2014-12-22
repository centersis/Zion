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
     * 
     * @param type $banco
     * @param type $host
     * @param type $usuario
     * @param type $senha
     * @param type $driver
     * @return Conexao
     */

    private function __construct($banco, $host = '', $usuario = '', $senha = '', $driver = '')
    {
        require_once SIS_FM_BASE . 'Lib/vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';

        $classLoader = new ClassLoader('Doctrine', SIS_FM_BASE . 'Lib/vendor/doctrine/dbal/lib');

        $classLoader->register();

        $this->banco = $banco;

        $this->arrayExcecoes[0] = "Problemas com o servidor impedem a conexão com o banco de dados.<br>";
        $this->arrayExcecoes[1] = "Problemas ao executar a clausula SQL.<br>";
        $this->arrayExcecoes[2] = "ResultSet inválido.";
        $this->arrayExcecoes[3] = "A query SQL esta vazia.";
        $this->arrayExcecoes[4] = "Array de querys inválido.";

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

        //['wrapperClass' => 'Doctrine\DBAL\Portability\Connection', 'portability' => \octrine\DBAL\Portability\Connection::PORTABILITY_ALL];

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
     * 
     * @return \Doctrine\DBAL\Connection
     */
    public function link()
    {
        return self::$link[$this->banco];
    }

    private function getExcecao($cod)
    {
        return "Erro - " . $this->arrayExcecoes[$cod];
    }

    //Seta Intercepatação de Sql
    public function setInterceptaSql($valor)
    {
        $this->interceptaSql = $valor;
    }

    //Seta Atividade do Log
    public function setGravarLog($valor)
    {
        $this->gravarLog = $valor;
    }

    //Setando Log Oculto
    public function setLogOculto($valor)
    {
        $this->logOculto = $valor;
    }

    //Retorna o número de linhas afetadas por comandos INSERT, REPLACE, UPDATE, DELETE
    public function getLinhasAfetadas()
    {
        return $this->linhasAfetadas;
    }

    //Seta o Conteiner Sql
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
     * 	Cria uma conexão com o banco de dados MYSQL (SINGLETON)
     * 	@return Conexao
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
     * 	Cria uma conexão com o banco de dados MYSQL (SINGLETON)
     * 	@return Conexao
     */
    public static function conectarManual($host, $banco, $usuario, $senha, $driver)
    {
        if (!isset(self::$instancia[$banco])) {
            self::$instancia[$banco] = new Conexao($banco, $host, $usuario, $senha, $driver);
        }

        return self::$instancia[$banco];
    }

    /**
     * 	Fecha a Conexão com o Mysql
     */
    public function fecharConexao()
    {
        self::$link[$this->banco]->close();
    }

    /**
     * 	Executando uma clausula SQL
     * 	@param Sql String - Instrução SQL
     * 	@return ResultSet
     */
    public function executar($sql)
    {
        if (empty($sql)) {
            throw new \Exception($this->getExcecao(3));
        }

        $this->linhasAfetadas = 0;

        if (\is_object($sql)) {
            
            $resultSet = $sql->executa();
            $this->linhasAfetadas = $this->nLinhas($resultSet);
            return $resultSet;            
        }
        
        $executa = self::$link[$this->banco]->query($sql);
        $this->linhasAfetadas = $executa->rowCount();

        return $executa;
    }

    /**
     * 	Executando uma ou mais clausulas SQL de um array
     * 	@param ArrayQuery Array() - Array Com Instruções SQL
     * 	@return ResultSet
     */
    public function executarArray($arraySql, $transaction = true)
    {
        if (!\is_array($arraySql)) {
            throw new Exception($this->getExcecao(4));
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
     * 	Executando um ResultSet e retornando um Array
     * 	@param Sql String - Instrução SQL
     * 	@return Array
     */
    public function linha($resultSet, $estilo = null)
    {
        if (!\is_object($resultSet)) {
            throw new \Exception($this->getExcecao(2));
        }

        $nLinhas = $resultSet->rowCount();

        if ($nLinhas > 0) {
            $linhas = $resultSet->fetchAll($estilo);
            return \array_map("trim", $linhas[0]);
        } else {
            return array();
        }
    }

    /**
     * 	Executando uma clusula SQL e retornando um Array
     * 	@param Sql String - Instrução SQL
     * 	@return Array
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
     * 	Executando uma clusula SQL e retornando o resultado de uma array
     * 	@param Sql String - Instrução SQL
     * 	@param Posicao String - Posição do Resultado no Select
     * 	@return String
     */
    public function execRLinha($sql, $posicao = 0)
    {
        $resultSet = $this->executar($sql);

        $array = $this->linha($resultSet);

        if (\key_exists($posicao, $array)) {
            return $array[$posicao];
        }

        return \current($array);
    }

    /**
     * Executa um comando SQL e retorna um array com Todos os resultados
     * @param string Instrução SQL
     * @return array() / false
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
                            throw new \Exception("Indice $indice não encontrado!");
                        }

                        $rows[$row[$indice]] = $row;
                    }
                } else {
                    if (empty($indice)) {
                        if (!\key_exists($posicao, $row)) {
                            throw new \Exception("Posição $posicao não encontrada!");
                        }
                        $rows[] = $row[$posicao];
                    } else {

                        if (!\key_exists($indice, $row)) {
                            throw new \Exception("Indice $indice não encontrado!");
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
     * 	Retornando o número de resultados de um ResultSet
     * 	@param ResultSet ResultSet - ResultSet
     * 	@return Inteiro
     */
    public function nLinhas($resultSet)
    {
        if (!\is_object($resultSet)) {
            throw new \Exception($this->getExcecao(2));
        }

        return (int) $resultSet->rowCount();
    }

    /**
     * Executando uma clausula Sql e retornando o numero de linhas afetadas,
     * aceita string sql nativa ou objeto querybuilder
     * @param string/object $sql
     * @return int
     */
    public function execNLinhas($sql)
    {
        if (\is_object($sql)) {

            $rs = $sql->execute();
            $linhas = $rs->rowCount();

            return $linhas;
        }

        return $this->nLinhas($this->executar($sql));
    }

    /**
     * 	Executando uma clausula SQL e retornando o maior ID encontrado
     * 	@param Tabela String - Nome da tabela a ser pesquisada
     * 	@param IdTabela String - Identificação di indice a ser pesquisado
     * 	@return Inteiro
     */
    public function maiorId($tabela, $idTabela)
    {
        $sql = $this->link()
                ->createQueryBuilder()
                ->select('MAX(' . $idTabela . ') as maior')
                ->from($tabela, '');
        return $this->execRLinha($sql);
    }

    public function ultimoInsertId()
    {
        return self::$link[$this->banco]->lastInsertId();
    }

    /**
     * 	Verifica se determinando valor é persistente no banco de dados
     * 	@param Campo    String   - Nome da coluna a ser encontrada
     * 	@param Valor    String   - Valor a ser encontrado na coluna
     * 	@param Tabela   String   - Tabela a ser pesquisada
     * 	@param Inteiro Booleano - Perquisar Por Campos com Aspas ou Sem Apas
     * 	@return Booleano
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
     * 	Inicia uma Transação
     * 	@return VOID
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
     * 	Finaliza uma Transação - Commit se for vazio, senão Rollback
     * 	@param Erro: String - Indicador de Erro
     * 	@return Booleano
     */
    public function stopTransaction($erro = null)
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
