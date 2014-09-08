<?php

/**
 * 	@copyright CenterSis
 * 	@author Pablo Vanni - pablovanni@gmail.com
 * 	@since 23/02/2005
 * 	<br>Atualizada em: 24/10/2008<br>
 * 	<br>Atualizada em: 11/02/2011<br>
 * 	<br>Atualizada em: 11/07/2011<br>
 * 	<br>Atualizada em: 01/06/2012 - Multiplas Conexões<br>
 * 	Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br>
 * 	@name Conexão e interação com metodos de entrada e saida
 * 	@version 2.0
 *  	@package Framework
 */
include_once($_SESSION['DirBase'].'framework/conexao.conf.php');
include_once($_SESSION['FMBase'] . 'log.class.php');

class Conexao
{
    /*
     * 	Atributos da Classe
     */
    private static $Transaction;
    
    public static $Link = array();
    public static $Instancia = array();
    
    private $Banco;
    
    private $ArrayExcecoes = array();
    private $LinhasAfetadas = 0;
    
    //Atributos de Log
    private $ConteinerSql = array(); //Conteiner que irá receber as Intruções Sql Ocultas Ou Não
    private $LogOculto = false;   //Indicador - Indica se o tipo de log deve ser ou não oculto
    private $GravarLog = true;    //Indicador - Indica se o log deve ou não ser gravado
    private $InterceptaSql = false;   //Indicador - Indica se o sql seve ou não ser inteceptado

    /**
     * 	Método Construtor
     */

    private function __construct($Banco)
    {        
        $this->Banco = $Banco;
        
        $Conf = new ConexaoConf();
        
        $this->setExcecao();        

        self::$Link[$Banco] = new mysqli($Conf->getHost($Banco), $Conf->getUser($Banco), $Conf->getSenha($Banco), $Conf->getBanco($Banco));
    }

    private function setExcecao()
    {
        $this->ArrayExcecoes[0] = "Probelmas com o servidor impedem a conexão com o banco de dados.<br>";
        $this->ArrayExcecoes[1] = "Problemas ao executar a clausula SQL.<br>";
        $this->ArrayExcecoes[2] = "ResultSet Inválido.";
        $this->ArrayExcecoes[3] = "A Query SQL esta vazia.";
        $this->ArrayExcecoes[4] = "Array de querys invalido.";
    }

    private function getExcecao($Cod)
    {
        return "Erro - " . $this->ArrayExcecoes[$Cod];
    }

    //Seta Intercepatação de Sql
    public function setInterceptaSql($Valor)
    {
        $this->InterceptaSql = $Valor;
    }

    //Seta Atividade do Log
    public function setGravarLog($Valor)
    {
        $this->GravarLog = $Valor;
    }

    //Setando Log Oculto
    public function setLogOculto($Valor)
    {
        $this->LogOculto = $Valor;
    }

    //Retorna o número de linhas afetadas por comandos INSERT, REPLACE, UPDATE, DELETE
    public function getLinhasAfetadas()
    {
        return $this->LinhasAfetadas;
    }

    //Seta o Conteiner Sql
    private function setConteinerSql($Valor)
    {
        if ($this->GravarLog == true)
        {
            if ($this->LogOculto === true)
            {
                $this->ConteinerSql['OcultoSim'][] = $Valor;
            }
            else
            {
                $this->ConteinerSql['OcultoNao'][] = $Valor;
            }
        }
    }

    //Recupera Conteiner Sql
    public function getConteinerSql()
    {
        return $this->ConteinerSql;
    }

    public function resetLog()
    {
        $this->ConteinerSql = array();
        $this->LogOculto = false;
        $this->GravarLog = true;
        $this->InterceptaSql = true;
    }

    /**
     * 	Cria uma conexão com o banco de dados MYSQL (SINGLETON)
     * 	@return Objeto
     */
    public static function conectar($Banco = 'Padrao')
    {
        $Banco = strtoupper($Banco);
        
        if (!isset(self::$Instancia[$Banco]))
        {            
            self::$Instancia[$Banco] = new Conexao($Banco);
        }

        return self::$Instancia[$Banco];
    }
    
    /**
     * 	Fecha a Conexão com o Mysql
     * 	@return ResultSet
     */
    public function fecharConexao()
    {
        self::$Link[$this->Banco]->close();
    }

    /**
     * 	Executando uma clausula SQL
     * 	@param Sql String - Instrução SQL
     * 	@return ResultSet
     */
    public function executar($Sql)
    {
        if (empty($Sql))
            throw new Exception($this->getExcecao(3));

        $this->LinhasAfetadas = 0;

        $Executa = self::$Link[$this->Banco]->query($Sql);

        if (is_object($Executa))
        {
            return $Executa;
        }
        elseif ($Executa === true)
        {
            //Linhas Afetadas
            $this->LinhasAfetadas = self::$Link[$this->Banco]->affected_rows;

            //Interceptando Sql
            if ($this->InterceptaSql == true)
                $this->setConteinerSql($Sql);

            //Executa
            return $Executa;
        }
        else
        {

            $Email = "pablovanni@gmail.com";
            @mail($Email, "Erro - Muvuca Popular", "Erro ao Processar:<br><br>" . $Sql, "From:Centersis FrameWork<$Email>\nContent-type: text/html\n");
            throw new Exception($this->getExcecao(1) . "<br>$Sql<br>" . mysqli_error(self::$Link[$this->Banco]));
        }
    }

    /**
     * 	Executando uma ou mais clausulas SQL de um array
     * 	@param ArrayQuery Array() - Array Com Instruções SQL
     * 	@return ResultSet
     */
    public function executarArray($ArraySql, $Transaction = true)
    {
        if (!is_array($ArraySql))
            throw new Exception($this->getExcecao(4));

        if ($Transaction == true)
        {
            //Inicia Transação
            $this->startTransaction();
        }

        foreach ($ArraySql as $Sql)
        {
            $Executa = self::$Link[$this->Banco]->query($Sql);

            //Interceptando Sql
            if ($this->InterceptaSql == true)
                $this->setConteinerSql($Sql);

            if (!$Executa === true)
            {
                if ($Transaction == true)
                {
                    $this->stopTransaction($this->getExcecao(1));
                }
                throw new Exception($this->getExcecao(1) . "<br>$Sql<br>" . mysqli_error(self::$Link[$this->Banco]));
            }
        }


        if ($Transaction == true)
        {
            //Finaliza Transação
            $this->stopTransaction();
        }
    }

    /**
     * 	Executando um ResultSet e retornando um Array
     * 	@param Sql String - Instrução SQL
     * 	@return Array
     */
    public function linha($ResultSet, $Estilo = MYSQLI_BOTH)
    {
        if (!is_object($ResultSet))
            throw new Exception($this->getExcecao(2));

        $NLinhas = $ResultSet->num_rows;

        if ($NLinhas > 0)
        {
            $Linhas = $ResultSet->fetch_array($Estilo);
            return @array_map("trim", $Linhas);
        }
        else
        {
            return array();
        }
    }

    /**
     * 	Executando uma clusula SQL e retornando um Array
     * 	@param Sql String - Instrução SQL
     * 	@return Array
     */
    public function execLinha($Sql)
    {
        $ResultSet = $this->executar($Sql);

        return $this->linha($ResultSet);
    }

    public function execLinhaArray($Sql)
    {
        $ResultSet = $this->executar($Sql);

        return $this->linha($ResultSet, MYSQLI_ASSOC);
    }

    /**
     * 	Executando uma clusula SQL e retornando o resultado de uma array
     * 	@param Sql String - Instrução SQL
     * 	@param Posicao Inteiro - Posição do Resultado no Select
     * 	@return String
     */
    public function execRLinha($Sql, $Posicao = 0)
    {
        $ResultSet = $this->executar($Sql);

        $Array = $this->linha($ResultSet);
        if (isset($Array[$Posicao]))
        {
            return $Array[$Posicao];
        }
        else
        {
            return false;
        }
    }

    /**
     * Executa um comando SQL e retorna um array com Todos os resultados
     * @param string Instrução SQL
     * @return array() / false
     */
    public function execTodosArray($Sql, $Posicao = null, $Indice = null)
    {
        $Ret = $this->executar($Sql);

        if ($Ret !== false)
        {
            $Rows = array();

            while ($Row = $Ret->fetch_assoc())
            {
                if (empty($Posicao))
                {
                    if (empty($Indice))
                        $Rows[] = $Row;
                    else
                        $Rows[$Row[$Indice]] = $Row;
                }
                else
                {
                    if (empty($Indice))
                        $Rows[] = $Row[$Posicao];
                    else
                        $Rows[$Row[$Indice]] = $Row[$Posicao];
                }
            }
            return $Rows;
        }
        else
        {
            return array();
        }
    }

    /**
     * 	Retornando o número de resultados de um ResultSet
     * 	@param ResultSet ResultSet - ResultSet
     * 	@return Inteiro
     */
    public function nLinhas($ResultSet)
    {
        if (!is_object($ResultSet))
            throw new Exception($this->getExcecao(2));

        return $ResultSet->num_rows;
    }

    /**
     * 	Executando uma clausula Sqle retornando o numero de Linhas afetadas
     * 	@param Sql String - Instrução SQL
     * 	@return Inteiro
     */
    public function execNLinhas($Sql)
    {
        return $this->nLinhas($this->executar($Sql));
    }

    /**
     * 	Executando uma clausula SQL e retornando o maior ID encontrado
     * 	@param Tabela String - Nome da tabela a ser pesquisada
     * 	@param IdTabela String - Identificação di indice a ser pesquisado
     * 	@return Inteiro
     */
    public function ultimoId($Tabela, $IdTabela)
    {
        return $this->execRLinha("SELECT  " . $IdTabela . " FROM " . $Tabela . " ORDER BY " . $IdTabela . " DESC LIMIT 1");
    }

    public function ultimoInsertId()
    {
        return self::$Link[$this->Banco]->insert_id;
    }

    /**
     * 	Verifica se determinando valor é persistente no banco de dados
     * 	@param Campo    String   - Nome da coluna a ser encontrada
     * 	@param Valor    String   - Valor a ser encontrado na coluna
     * 	@param Tabela   String   - Tabela a ser pesquisada
     * 	@param SemAspas Booleano - Perquisar Por Campos com Aspas ou Sem Apas
     * 	@return Booleano
     */
    public function existe($Tabela, $Campo, $Valor, $SemAspas = false)
    {
        $Compara = ($SemAspas == false) ? "'" . $Valor . "'" : $Valor;

        return($this->execNLinhas("SELECT " . $Campo . " FROM " . $Tabela . " WHERE " . $Campo . " = $Compara LIMIT 1") < 1) ? false : true;
    }

    /**
     * 	Verifica se determinando valor já existe no banco de dados
     * 	@param Campo String - Nome da coluna a ser encontrada
     * 	@param Valor String - Valor a ser encontrado na coluna
     * 	@param Tabela String - Tabela a ser pesquisada
     * 	@param Criterio String - Critério - Condição de Visualização
     * 	@return Booleano
     */
    public function duplicado($Tabela, $Campo, $Valor, $Criterio = null)
    {
        $Criterio = (empty($Criterio)) ? null : " AND " . $Criterio;

        return($this->execNLinhas("SELECT " . $Campo . " FROM " . $Tabela . " WHERE " . $Campo . " = '" . $Valor . "' $Criterio") > 1) ? true : false;
    }

    /**
     * 	Busca o próximo registro a ser alterado
     * 	@param Campo String - Nome da coluna a ser encontrada
     * 	@param Tabela String - Tabela a ser pesquisada
     * 	@param IdAtual Inteiro - ID Atual a Ser Pesquisado
     * 	@return Inteiro
     */
    public function proximoId($Tabela, $Campo, $IdAtual)
    {
        $Sql = "SELECT $Campo as Valor FROM $Tabela WHERE $Campo > $IdAtual LIMIT 1";

        $Id = $this->execRLinha($Sql);

        if (!empty($Id))
        {
            return $Id;
        }
        else
        {
            $Sql = "SELECT $Campo as Valor FROM $Tabela ORDER BY $Campo ASC LIMIT 1";
            $Id = $this->execRLinha($Sql);

            return $Id;
        }
    }

    /**
     * 	Inicia uma Transação
     * 	@return VOID
     */
    public function startTransaction()
    {
        if (self::$Transaction < 1)
        {
            $this->executar("START TRANSACTION");
            self::$Transaction = 1;
        }
        else
        {
            self::$Transaction += 1;
        }
    }

    /**
     * 	Finaliza uma Transação - Commit se for vazio, senão Rollback
     * 	@param Erro: String - Indicador de Erro
     * 	@return Booleano
     */
    public function stopTransaction($Erro = null)
    {
        if (self::$Transaction == 1)
        {
            if (!empty($Erro))
            {
                $this->executar("ROLLBACK");
                self::$Transaction -= 1;
                return false;
            }
            else
            {
                $this->executar("COMMIT");
                self::$Transaction -= 1;
                return true;
            }
        }
        else
        {
            self::$Transaction -= 1;
        }
    }
}
