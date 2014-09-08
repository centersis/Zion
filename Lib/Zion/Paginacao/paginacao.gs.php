﻿<? /***	@copyright DEMP - Soluções em Tecnologia da Informação Ltda*	@author Pablo Vanni - pablovanni@gmail.com*	@since 23/02/2005*	<br>Última Atualização: 11/08/2008<br>*	Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br>*	@name  Paginação de resultado para uma consulta no banco de dados* 	@version 2.0* 	@package Framework*/include_once($_SESSION['FMBase'].'parametros.class.php');abstract class PaginacaoGS{	/**	*	Atributos da Classe	*/		private $TipoOrdenacao  = "";//String  - Indica o tipo de ordenação ASC ou DESC	private $QuemOrdena     = "";//String  - Indica um atributo especifico para ser ordenado	private $Sql            = "";//String  - Sql    private $TabelaMestra   = "";//String  - Tabela que deve ser extraida no numero de registros    private $SqlContador    = "";//String  - Sql responsável por contar número de registros da Grid    private $FiltroAtivo    = false;//Booleano - Deve indicar se existe ou não ocoorencia de filtro    private $LimitAtivo     = true;//Indica se o SELECT deve receber LIMIT's de Paginação	private $Chave          = "";//String  - Identificação da Chave Identificadora	private $MetodoFiltra   = "";//String  - Informa a Grid Qual Método ela deve chamar para executar o filtro	private $QLinhas        = 0;//Inteiro  - Informa o Número Maximo de Resultados por página	private $PaginaAtual    = 1;//Inteiro  - Informa o número da pagina atual - Este numero é varivael de acordo com o numero de linhas	private $IrParaPagina   = true;//Booleano - Ir diretamente para a página desejada | Habilitar ou não esta opção na paginação    private $AlterarLinhas  = true;//Booleano - Mostrar opção para alterar o numero de linhas(resultados) de uma grid		/**	*	Tipo de Ordenação	*/	public function setTipoOrdenacao($Valor)	{		$this->TipoOrdenacao = $Valor;	}	public function getTipoOrdenacao()	{		$Order = strtoupper($this->TipoOrdenacao);				return ($Order == "ASC") ? "ASC" : "DESC";	}				/**	*	Quem Ordena	*/	public function setQuemOrdena($Valor)	{		$this->QuemOrdena = $Valor;	}	public function getQuemOrdena()	{			return $this->QuemOrdena;	}			/**	*	Sql	*/	public function setSql($Valor)	{		$this->Sql = $Valor;	}	public function getSql()	{		return $this->Sql;	}    /**	*	Tabela Mestra	*/	public function setTabelaMestra($Valor)	{		$this->TabelaMestra = $Valor;	}	public function getTabelaMestra()	{		return $this->TabelaMestra;	}    /**	*	Sql Contador	*/	public function setSqlContador($Valor)	{		$this->SqlContador = $Valor;	}	public function getSqlContador()	{		return $this->SqlContador;    }    /**	*	Filtro Ativo	*/	public function setFiltroAtivo($Valor)	{		$this->FiltroAtivo = $Valor;	}	public function getFiltroAtivo()	{		return $this->FiltroAtivo;	}    /**	*	Limit Ativo	*/	public function setLimitAtivo($Valor)	{		$this->LimitAtivo = $Valor;	}	public function getLimitAtivo()	{		return $this->LimitAtivo ? true : false;	}		/**	*	Chave	*/	public function setChave($Valor)	{		$this->Chave = $Valor;	}	public function getChave()	{		return $this->Chave;	}			/**	*	Metodo para o filtro 	*/	public function setMetodoFiltra($Valor)	{		$this->MetodoFiltra = $Valor;	}	public function getMetodoFiltra()	{		return (empty($this->MetodoFiltra)) ? "sis_filtrar" : $this->MetodoFiltra;	}					/**	*	Quantidade de Linhas da Grid	*/	public function setQLinhas($Valor)	{		$this->QLinhas = $Valor;	}	public function getQLinhas()	{		return(is_numeric($this->QLinhas)) ? $this->QLinhas : 0;	}	/**	*	Página Atual	*/	public function setPaginaAtual($Valor)	{		$this->PaginaAtual = $Valor;	}	public function getPaginaAtual()	{		return (empty($this->PaginaAtual)) ? 1 : $this->PaginaAtual;	}		/**	*	Ir Para Página	*/	public function setIrParaPagina($Valor)	{		$this->IrParaPagina = (bool)$Valor;	}	public function getIrParaPagina()	{		return $this->IrParaPagina;	}    /**	*	Alterar Linhas (Resultados) da grid	*/	public function setAlterarLinhas($Valor)	{		$this->AlterarLinhas = (bool)$Valor;	}	public function getAlterarLinhas()	{		return $this->AlterarLinhas;	}                public function addVariaveisPaginacao(Array $Variaveis, $MetodoEnvio = 'GET'){            Parametros::setParametros($MetodoEnvio, $Variaveis);        }}