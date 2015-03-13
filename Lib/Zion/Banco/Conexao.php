<?php

/**
 *
 *    Sappiens Framework
 *    Copyright (C) 2014, BRA Consultoria
 *
 *    Website do autor: www.braconsultoria.com.br/sappiens
 *    Email do autor: sappiens@braconsultoria.com.br
 *
 *    Website do projeto, equipe e documentação: www.sappiens.com.br
 *   
 *    Este programa é software livre; você pode redistribuí-lo e/ou
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *    publicada pela Free Software Foundation, versão 2.
 *
 *    Este programa é distribuído na expectativa de ser útil, mas SEM
 *    QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *    detalhes.
 * 
 *    Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *    junto com este programa; se não, escreva para a Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *    02111-1307, USA.
 *
 *    Cópias da licença disponíveis em /Sappiens/_doc/licenca
 *
 */

namespace Zion\Banco;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Portability\Connection;
use Doctrine\DBAL\DriverManager;

class Conexao
{

    private static $transaction = [];
    public static $link = [];
    public static $instancia = [];
    private $banco;
    private $arrayExcecoes = [];
    private $linhasAfetadas = 0;

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

            $namespace = '\\' . \SIS_ID_NAMESPACE_PROJETO . '\\Config';

            $cHost = $namespace::$SIS_CFG['bases'][$banco]['host'];
            $cUsuario = $namespace::$SIS_CFG['bases'][$banco]['usuario'];
            $cSenha = $namespace::$SIS_CFG['bases'][$banco]['senha'];
            $cBanco = $namespace::$SIS_CFG['bases'][$banco]['banco'];
            $cDriver = $namespace::$SIS_CFG['bases'][$banco]['driver'];
        }

        $config = new Configuration();

        if($cSenha === 'NULL'){
            $cSenha = NULL;
        }
        
        $connectionParams = [
            'dbname' => $cBanco,
            'user' => $cUsuario,
            'password' => $cSenha,
            'host' => $cHost,
            'driver' => $cDriver,
            'charset' => 'utf8',
            'wrapperClass' => 'Doctrine\DBAL\Portability\Connection',
            'portability' => Connection::PORTABILITY_ALL,
            'fetch_case' => \PDO::CASE_LOWER,
            'driverOptions' => [
                1002 => 'SET NAMES utf8']
        ];


        //$config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
        self::$link[$banco] = DriverManager::getConnection($connectionParams, $config);
    }
    
    /**
     * Retorna uma instancia de conexão com o banco de dados
     * @param string $banco
     * @return \Doctrine\DBAL\Connection
     */
    public function dbal($banco = \NULL)
    {
        return self::$link[$banco ? $banco : $this->banco];
    }

    /**
     * Cria uma instancia direta do SQL query builder.
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function qb($banco = \NULL)
    {
        return self::$link[$banco ? $banco : $this->banco]->createQueryBuilder();
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

    /**
     * Retorna o número de linhas afetadas por uma clausula sql
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
     * @param \Doctrine\DBAL\Query\QueryBuilder|string $sql
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

            $resultSet = $sql->execute();

            if ($sql->getType() === 0) { //0 = SELECT                
                $this->linhasAfetadas = $this->nLinhas($resultSet);
            } else {
                $this->linhasAfetadas = $resultSet;
            }

            return $resultSet;
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
        return $this->dbal()->lastInsertId($campo);
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

            $this->executar($sql);

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
     * @param \Doctrine\DBAL\Query\QueryBuilder|string $sql
     * @param string $estilo
     * @return array
     */
    public function execLinha($sql, $estilo = null)
    {
        if (\is_object($sql)) {
            $resultSet = $this->executar($sql);
            return $this->linha($resultSet, $estilo);
        }

        $resultSet = $this->executar($sql);

        return $this->linha($resultSet, $estilo);
    }

    /**
     * Executa uma string Sql ou um objeto query builder e retona um array
     * com os resultados da clausula select
     * @param \Doctrine\DBAL\Query\QueryBuilder|string $sql
     * @return array
     */
    public function execLinhaArray($sql)
    {
        if (\is_object($sql)) {
            $resultSet = $this->executar($sql);
            return $this->linha($resultSet, 2);
        }

        $resultSet = $this->executar($sql);

        return $this->linha($resultSet, 2);
    }

    /**
     * Executa uma string Sql ou um objeto query builder e retorna o 
     * valor contido na posição especificada pelo parametro $posicao
     * @param \Doctrine\DBAL\Query\QueryBuilder|string $sql
     * @param string|int $posicao
     * @return string
     * @throws \Exception
     */
    public function execRLinha($sql, $posicao = 0)
    {
        $resultSet = $this->executar($sql);

        $array = $this->linha($resultSet);

        if (!\is_numeric($posicao)) {
            $posicao = \strtolower($posicao);
        }

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
     * @param \Doctrine\DBAL\Query\QueryBuilder|string $sql
     * @param string $posicao
     * @param string $indice
     * @return array
     * @throws \Exception
     */
    public function paraArray($sql, $posicao = null, $indice = null)
    {
        if (\is_object($sql)) {
            $ret = $this->executar($sql);
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
     * @param \Doctrine\DBAL\Driver\Statement $resultSet
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
     * @param \Doctrine\DBAL\Query\QueryBuilder|string $sql
     * @return int
     */
    public function execNLinhas($sql)
    {
        if (\is_object($sql)) {

            $rs = $this->executar($sql);
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
        $qb = $this->qb();
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
        $qb = $this->qb();

        $qb->select($campo)
                ->from($tabela, '');

        if ($inteiro) {
            $qb->where($qb->expr()->eq($campo, $qb->expr()->eq($campo, '?')))
                    ->setParameter(0, $valor, \PDO::PARAM_INT);
        } else {
            $qb->where($qb->expr()->eq($campo, '?'))
                    ->setParameter(0, $valor, \PDO::PARAM_STR);
        }

        $qb->setMaxResults(1);

        return ($this->execNLinhas($qb) < 1) ? false : true;
    }

    /**
     * Inicia uma Transação
     */
    public function startTransaction()
    {
        if (!\array_key_exists($this->banco, self::$transaction)) {
            self::$link[$this->banco]->beginTransaction();
            self::$transaction[$this->banco] = 1;
        } else {
            self::$transaction[$this->banco] += 1;
        }
    }

    /**
     * Finaliza uma Transação - Commit se for vazio, senão Rollback
     * @param string $erro
     * @return boolean
     */
    public function stopTransaction($erro = '')
    {
        if(!\array_key_exists($this->banco, self::$transaction)){
            return false;
        }
        
        if (self::$transaction[$this->banco] == 1) {
            if (!empty($erro)) {
                self::$link[$this->banco]->rollBack();
                self::$transaction[$this->banco] -= 1;
                return false;
            } else {
                self::$link[$this->banco]->commit();
                self::$transaction[$this->banco] -= 1;
                return true;
            }
        } else {
            self::$transaction[$this->banco] -= 1;
        }
    }

}
