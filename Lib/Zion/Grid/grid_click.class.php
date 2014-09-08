﻿<? /***	@copyright DEMP - Soluções em Tecnologia da Informação Ltda*	@author Pablo Vanni - pablovanni@gmail.com*	@since 18/11/2005*	<br>Última Atualização: 16/05/2006<br>*	Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br>*	@name Cria uma grid de resultados com paginação* 	@version 2.0* 	@package Framework*///Classes Nescessáriasinclude_once($_SESSION['FMBase'].'paginacao.class.php');include_once($_SESSION['FMBase'].'conexao.class.php');include_once($_SESSION['FMBase'].'tabelas.class.php');include_once($_SESSION['FMBase'].'botoes_acesso.class.php');include_once($_SESSION['FMBase'].'grid.class.php');class GridClick extends Grid {	/**	*	Atributos da Classe	*/		private $Con, $Pag, $Tab;		/**	*	Metodo Construtor	*	@return VOID	*/	public function GridClick()	{		$this->Con = Conexao::conectar();		$this->Pag = new Paginacao();		$this->Tab = new Tabelas();	}				public function tituloGridClick()	{		$Titulos  = parent::getTitulos();		$Listados = parent::getListados(); 				$Html  = $this->Tab->abreTr();                if(parent::getModoImpressao() == false)		{			$Html .= $this->Tab->abreTd('checkFill', ConfigSIS::$CFG['ClassTitulo']).'<input type="checkbox" name="checkTodos" id="checkTodos" value="null" onclick="sis_selecionar()" />'.$this->Tab->fechaTd();		}		//Titulos		foreach($Titulos as $Key=>$Value)		{						$Html .= $this->Tab->abreTd(null, ConfigSIS::$CFG['ClassTitulo']);			$Html .= parent::ordena($Value, $Listados[$Key]);			$Html .= $this->Tab->fechaTd();		}					$Html .= $this->Tab->fechaTr();		return $Html;		}				 	/**	*	Contrução da Grid de Dados	*	@param Opcoes Array - Indica se cada item deve ser habilitado	*	@param QLinhas Inteiro - Número de Resultados por pagina	*	@param PaginaAtual Inteiro - Número da Pagina Atual	*	@param Parametros String - Query String com parametros nescessarios 	*	@return String	*/	public function montaGridClick()	{								//Recupera Valores		$Sql          = parent::getSql();		$Titulos      = parent::getTitulos();		$Listados     = parent::getListados();		$Chave        = parent::getChave();		$QLinhas      = parent::getQLinhas();        $FormatarComo = parent::getFormatarComo();								//Verifica se o SQL não esta Vazio		if(empty($Sql))	throw new Exception("Valor selecionado é inválido");        //Se Formatações existem, intancie funções PHP        if(!empty($FormatarComo))  $FPHP = new FuncoesPHP();				//Iniciando valores		$I        = 0;		$Html     = "";		$Paginado = "";						//Monta Paginanação		if($QLinhas > 0)		{						//Setando Valores para paginação			$this->Pag->setSql($Sql);			$this->Pag->setChave($Chave);			$this->Pag->setQLinhas($QLinhas);			$this->Pag->setPaginaAtual(parent::getPaginaAtual());			$this->Pag->setTipoOrdenacao(parent::getTipoOrdenacao());			$this->Pag->setQuemOrdena(parent::getQuemOrdena());			$this->Pag->setMetodoFiltra(parent::getMetodoFiltra());						$Exec     = $this->Pag->rsPaginado();			$Paginado = $this->Pag->listaResultados();		}		else 		{			$Exec = $this->Con->executar($Sql);		}				$NLinhas = $this->Con->NLinhas($Exec);					//Contruindo grid		if($NLinhas > 0)		{						$Html .= $this->Tab->tabIni(null, null, 1, null, "tableGrid"); 						//Objeto de Converssão			$ObjC = $this->getObjetoConverte();						//Estilos de um resultado (ESTILO DE RESULTADO ÙNICO)			$ERU = $this->getCondicaoResultadoUnico();			$ETR = $this->getCondicaoTodosResultados();									while($Linha = @mysqli_fetch_array($Exec))			{				$I += 1; 				$ClassCor  = $I % 2 == 0 ? ConfigSIS::$CFG['CorZebraA'] : ConfigSIS::$CFG['CorZebraB'];								$CRT = "";				if(is_array($ETR))				{					foreach ($ETR as $RT)					{						if($this->resultadoEval($Linha,array($RT[0])) === true) $CRT = $RT[1];						}				}								if($I == 1)				{					$Html .= $this->tituloGridClick(true);				}								$Html .= $this->Tab->abreTr();                                //Td de Checagem				if(parent::getModoImpressao() == false)				{					$Html .= $this->tdCheck("SisReg[".$Linha[$Chave]."]","SisReg", $Linha[$Chave], $ClassCor." $CRT largCheck");				}								foreach($Listados as $Value)				{					//Valor com possivel conversão					$ValorItem = (!empty($ObjC[$Value])) ? $this->converteValor($Linha, $ObjC[$Value]) : $Linha[$Value];										//Estilo de Resultado Único					$CRU = "";					if(!empty($ERU[$Value]))					{						if($this->resultadoEval($Linha,$ERU[$Value]) === true) $CRU = $ERU[$Value][1];					}                    //Formatação                    if(!empty($FormatarComo))                    {                        if(!empty($FormatarComo[$Value]))                        {                            $Como = strtoupper($FormatarComo[$Value]);                            switch ($Como)                            {                                case "DATA"    : $ValorItem = $FPHP->convertData($ValorItem);     break;                                case "DATAHORA": $ValorItem = $FPHP->convertDataHora($ValorItem); break;                                case "NUMERO"  : $ValorItem = $FPHP->floatCliente($ValorItem);    break;                                case "MOEDA"   : $ValorItem = $FPHP->moedaCliente($ValorItem);    break;                            }                        }                    }										//Alinhamento					$ClassAlinha = parent::getAlinhamento($Value);														$Html .= $this->Tab->abreTd(null, "textoGrid hResultado $ClassCor $CRU $CRT $CSSL $ClassAlinha mao");					$Html .= '<input name="SIS_REG" type="hidden" id="SIS_REG" value="'.$Linha[$Chave].'" />';					$Html .= ($ValorItem == "") ? "&nbsp;" : $ValorItem;					$Html .= $this->Tab->fechaTd();								}								$Html .= $this->Tab->fechaTr();			}						$Html .= $this->Tab->tabFim();		}		else		{			return "<div class=\"semResultado\">&nbsp;Nenhum resultado encontrado.</div>";					}						$Resultado  = '<div class="classPaginacao">'.$Paginado.'</div>';				return $Resultado.$Html.$Resultado;	}        public function tdCheck($Nome, $Id, $Valor, $ClassCor)	{		return $this->Tab->abreTd('checkFill', $ClassCor).'<input type="checkbox" name="'.$Nome.'" id="'.$Id.'" value="'.$Valor.'" />'.$this->Tab->fechaTd();	}}?>