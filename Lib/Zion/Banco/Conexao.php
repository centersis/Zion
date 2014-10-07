<?php

/**
 * @author Pablo Vanni - pablovanni@gmail.com
 * @since 23/02/2005
 * Atualizada em: 24/10/2008
 * Atualizada em: 11/02/2011
 * Atualizada em: 11/07/2011
 * Atualizada em: 01/06/2012 - Multiplas Conexões
 * Autualizada Por: Pablo Vanni - pablovanni@gmail.com
 * @name Conexão e interação com metodos de entrada e saida
 * @version 3.0
 */

namespace Zion\Banco;

class Conexao
{

    private static $transaction;
    public static $link = array();
    public static $instancia = array();
    private $banco;
    private $arrayExcecoes = array();
    private $linhasAfetadas = 0;
    //Atributos de Log
    private $conteinerSql = array(); //Conteiner que irá receber as Intruções Sql Ocultas Ou Não
    private $logOculto = false;   //Indicador - Indica se o tipo de log deve ser ou não oculto
    private $gravarLog = true;    //Indicador - Indica se o log deve ou não ser gravado
    private $interceptaSql = false;   //Indicador - Indica se o sql seve ou não ser inteceptado

    private function __construct($banco)
    {
        $this->banco = $banco;

        $this->arrayExcecoes[0] = "Probelmas com o servidor impedem a conexão com o banco de dados.<br>";
        $this->arrayExcecoes[1] = "Problemas ao executar a clausula SQL.<br>";
        $this->arrayExcecoes[2] = "ResultSet inválido.";
        $this->arrayExcecoes[3] = "A query SQL esta vazia.";
        $this->arrayExcecoes[4] = "Array de querys inválido.";

        //self::$link[$banco] = new mysqli($conf->getHost($banco), $conf->getUser($banco), $conf->getSenha($banco), $conf->getBanco($banco));
        //self::$link[$banco] = new \mysqli('192.168.25.51', 'onyxprev_sapp', 'qwertybracom', 'onyxprev_sappiens');
        $namespace = '\\'.SIS_ID_NAMESPACE_PRJETO.'\\Config';      
        
        self::$link[$banco] = new \mysqli($namespace::$SIS_CFG['Bases'][$banco]['Host'], 
                $namespace::$SIS_CFG['Bases'][$banco]['Usuario'], 
                $namespace::$SIS_CFG['Bases'][$banco]['Senha'], 
                $namespace::$SIS_CFG['Bases'][$banco]['Banco']);
        self::$link[$banco]->set_charset("utf8");
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
    public static function conectar($banco = 'PADRAO')
    {               
        $bancoMaiusculo = empty($banco) ? 'PADRAO' : strtoupper($banco);

        if (!isset(self::$instancia[$bancoMaiusculo])) {
            self::$instancia[$bancoMaiusculo] = new Conexao($bancoMaiusculo);
        }

        return self::$instancia[$bancoMaiusculo];
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
            throw new Exception($this->getExcecao(3));
        }

        $this->linhasAfetadas = 0;

        $executa = self::$link[$this->banco]->query($sql);

        if (is_object($executa)) {
            return $executa;
        } elseif ($executa === true) {

            $this->linhasAfetadas = self::$link[$this->banco]->affected_rows;

            //Interceptando Sql
            if ($this->interceptaSql == true) {
                $this->setConteinerSql($sql);
            }

            return $executa;
        } else {
            throw new Exception\SqlException($this->getExcecao(1) . "<br>$sql<br>" . mysqli_error(self::$link[$this->banco]));
        }
    }

    /**
     * 	Executando uma ou mais clausulas SQL de um array
     * 	@param ArrayQuery Array() - Array Com Instruções SQL
     * 	@return ResultSet
     */
    public function executarArray($arraySql, $transaction = true)
    {
        if (!is_array($arraySql)) {
            throw new Exception($this->getExcecao(4));
        }

        if ($transaction == true) {
            $this->startTransaction();
        }

        foreach ($arraySql as $sql) {
            $executa = self::$link[$this->banco]->query($sql);

            if ($this->interceptaSql == true) {
                $this->setConteinerSql($sql);
            }

            if (!$executa === true) {
                if ($transaction == true) {
                    $this->stopTransaction($this->getExcecao(1));
                }
                throw new Exception($this->getExcecao(1) . "<br>$sql<br>" . mysqli_error(self::$link[$this->banco]));
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
    public function linha($resultSet, $estilo = MYSQLI_BOTH)
    {
        if (!is_object($resultSet)) {
            throw new Exception($this->getExcecao(2));
        }

        $nLinhas = $resultSet->num_rows;

        if ($nLinhas > 0) {
            $linhas = $resultSet->fetch_array($estilo);
            return array_map("trim", $linhas);
        } else {
            return array();
        }
    }

    /**
     * 	Executando uma clusula SQL e retornando um Array
     * 	@param Sql String - Instrução SQL
     * 	@return Array
     */
    public function execLinha($sql)
    {
        $resultSet = $this->executar($sql);

        return $this->linha($resultSet);
    }

    public function execLinhaArray($sql)
    {
        $resultSet = $this->executar($sql);

        return $this->linha($resultSet, MYSQLI_ASSOC);
    }

    /**
     * 	Executando uma clusula SQL e retornando o resultado de uma array
     * 	@param Sql String - Instrução SQL
     * 	@param Posicao Inteiro - Posição do Resultado no Select
     * 	@return String
     */
    public function execRLinha($sql, $posicao = 0)
    {
        $resultSet = $this->executar($sql);

        $array = $this->linha($resultSet);
        if (isset($array[$posicao])) {
            return $array[$posicao];
        } else {
            return false;
        }
    }

    /**
     * Executa um comando SQL e retorna um array com Todos os resultados
     * @param string Instrução SQL
     * @return array() / false
     */
    public function paraArray($sql, $posicao = null, $indice = null)
    {
        $ret = $this->executar($sql);

        if ($ret !== false) {
            $rows = array();

            while ($row = $ret->fetch_assoc()) {
                if (empty($posicao)) {
                    if (empty($indice)) {
                        $rows[] = $row;
                    } else {
                        $rows[$row[$indice]] = $row;
                    }
                } else {
                    if (empty($indice)) {
                        $rows[] = $row[$posicao];
                    } else {
                        $rows[$row[$indice]] = $row[$posicao];
                    }
                }
            }
            return $rows;
        } else {
            return array();
        }
    }

    /**
     * 	Retornando o número de resultados de um ResultSet
     * 	@param ResultSet ResultSet - ResultSet
     * 	@return Inteiro
     */
    public function nLinhas($resultSet)
    {
        if (!is_object($resultSet)) {
            throw new Exception($this->getExcecao(2));
        }

        return $resultSet->num_rows;
    }

    /**
     * 	Executando uma clausula Sqle retornando o numero de Linhas afetadas
     * 	@param Sql String - Instrução SQL
     * 	@return Inteiro
     */
    public function execNLinhas($sql)
    {
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
        return $this->execRLinha("SELECT  MAX(" . $idTabela . ") as maior FROM " . $tabela);
    }

    public function ultimoInsertId()
    {
        return self::$link[$this->banco]->insert_id;
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
        $compara = ($inteiro == false) ? "'" . $valor . "'" : $valor;

        return($this->execNLinhas("SELECT " . $campo . " FROM " . $tabela . " WHERE " . $campo . " = $compara LIMIT 1") < 1) ? false : true;
    }

    /**
     * 	Verifica se determinando valor já existe no banco de dados
     * 	@param Campo String - Nome da coluna a ser encontrada
     * 	@param Valor String - Valor a ser encontrado na coluna
     * 	@param Tabela String - Tabela a ser pesquisada
     * 	@param Criterio String - Critério - Condição de Visualização
     * 	@return Booleano
     */
    public function duplicado($tabela, $campo, $valor, $criterio = '')
    {
        $crt = (empty($criterio)) ? '' : " AND " . $criterio;

        return($this->execNLinhas("SELECT " . $campo . " FROM " . $tabela . " WHERE " . $campo . " = '" . $valor . "' $crt") > 1) ? true : false;
    }

    /**
     * 	Inicia uma Transação
     * 	@return VOID
     */
    public function startTransaction()
    {
        if (self::$transaction < 1) {
            $this->executar("START TRANSACTION");
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
                $this->executar("ROLLBACK");
                self::$transaction -= 1;
                return false;
            } else {
                $this->executar("COMMIT");
                self::$transaction -= 1;
                return true;
            }
        } else {
            self::$transaction -= 1;
        }
    }

}
