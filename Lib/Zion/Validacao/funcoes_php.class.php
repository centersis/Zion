<? 
/**
*	@copyright DEMP - Soluï¿½ï¿½es em Tecnologia da Informaï¿½ï¿½o Ltda
*	@author Pablo Vanni - pablovanni@gmail.com
*	@since 22/05/2006
*	<br>ï¿½ltima Atualizaï¿½ï¿½o: 22/05/2006<br>
*	Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br>
*	@name Agrupa as funï¿½ï¿½es de validaï¿½ï¿½o em PHP
* 	@version 1.0
* 	@package Framework
*/
//Classes Nescessï¿½rias
include_once($_SESSION['FMBase'].'variaveis.class.php');

class FuncoesPHP extends Variaveis 
{		
	public function extractVar($Array, $Metodo)
	{
		if(is_array($Array))
		{
			$Metodo = strtoupper($Metodo);
			
			switch ($Metodo)
			{
				case "GET": 
					foreach ($Array as $Chave=>$Valor)
					{
						$_GET[$Chave] = $Valor;
					}
				break;
				
				case "POST": 
					foreach ($Array as $Chave=>$Valor)
					{
						$_POST[$Chave] = $Valor;
					}
				break;
				
				case "REQUEST": 
					foreach ($Array as $Chave=>$Valor)
					{
						$_REQUEST[$Chave] = $Valor;
					}
				break;
			}			
		}
		
		return false;
	}
	
	public function formataErro($ArrayErro)
	{
		if(is_array($ArrayErro))
		{
			$Html.= "<ul>";
			foreach ($ArrayErro as $Nome=>$Erro) 
			{
				$Html.= "<li>$Nome<ul>";
				foreach ($Erro as $Mensagem) $Html.="<li>$Mensagem</li>";
				$Html.="</ul></li>";
			}
			$Html.= "</ul>";
			
			return $Html;
			
			//return '<script language="javascript"> sis_mensagem("'.$Html.'") </script > ';
		}
		else 
		{
			if($ArrayErro == "")
                        {
                            return "";
                        }
                        else
                        {
                            return "Erro Indefinido ".$ArrayErro;
                        }
		}
	}
	
	/**
	*	Converte data no formato americano ou brasileiro
	*	@param Data String - Data a ser convertida
	*	@param Separador String - Tipo de separador da data ex "/", "-"
	*	@return String
	*/
	public function convertData($Data)
	{	
		$Separador = $this->buscaDataSeparador($Data);
		
		if(!$this->verificaData($Data)) return "";
		
		$VetData = explode($Separador,$Data);
		return $VetData[2]."/".$VetData[1]."/".$VetData[0];
	}
	
	
	/**
	*	Recebe um DateTime
	*	@param Data String - DataTime a ser convertida
	*	@return String Somente Data
	*/
	public function convertDataHoraRetData($DataTime)
	{	
		$Data = explode(' ',trim($DataTime));
		return $this->convertData($Data[0]);
	}
		
	/**
	*	Converte data e hora no formato americano ou brasileiro
	*	@param Data String - Data a ser convertida
	*	@param Separador String - Tipo de separador da data ex "/", "-"
	*	@return String
	*/
	public function convertDataHora($DataHora)
	{	
		$DataHora = $this->limpa($DataHora);
		
		$Separador = $this->buscaDataSeparador($DataHora);
		
		list($Data, $Hora) = explode(" ",$DataHora);
		
		if(!$this->verificaData($Data)) return "";
		
		$VetData = explode($Separador,$this->limpa($Data));
		
		return $VetData[2]."/".$VetData[1]."/".$VetData[0]." ".$Hora;
	}		

	/**
	*	Limita uma string a um tamanho mï¿½ximo adcionando sufixo "..."
	*	@param String String - String a Ser manipulada
	*	@param Limite Inteiro - Limite maximo em caracteres da string
	*	@return String
	*/
	public function limita($String, $Limite)
	{
		if($this->tamanho($String) > $Limite)
		$String = substr($String, 0, $Limite)."...";
		
		return $String;
	}

	
/**
	*	Inverte e Troca Separador de Uma data
	*	@param Data String - Data a ser convertida
	*	@param Separador String - Tipo de separador da data ex "/", "-"
	*	@return String
	*/
	public function dataTrocaSeparador($Data, $SeparaAtual, $SeparaTroca)
	{	
		if(!$this->verificaData($Data)) return "";
		
		$VetData = explode($SeparaAtual,$this->limpa($Data));
		
		return $VetData[2].$SeparaTroca.$VetData[1].$SeparaTroca.$VetData[0];
	}	
	
	/**
	*	Verifica os intervalos maximos e minimos de uma data
	*	@param Data String - Data a ser Comparada
	*	@param DataMax String - Data Mï¿½xima que a data pode ser
	*	@param DataMin String - Data Mï¿½nima que a data pode ser
	*	@return String
	*/
	public function intervalos($Data, $DataMax, $DataMin)
	{	
		//Separador
		$Separador = $this->buscaDataSeparador($Data);
		
		//Verifica a Integridade das datas
		if(!$this->verificaData($Data)) return false;
		
		//Inverte
		$Data = $this->convertData($Data, $Separador);
		
		if(!empty($DataMax))
		{
			//Verifica
			if(!$this->verificaData($DataMax)) return false;
			
			//Inverte
			$DataMax = $this->convertData($DataMax, $Separador);
			
			//Compara
			if($Data > $DataMax) return false;
		}

		if(!empty($DataMin))
		{
			//Verifica
			if(!$this->verificaData($DataMin)) return false;
			
			//Inverte
			$DataMin = $this->convertData($DataMin, $Separador);
			
			//Compara
			if($Data < $DataMin) return false;
		}
		
		//Retorno
		return true;
	}	
	
	/**
	*	Verifica se a data ï¿½ uma data Real
	*	@param Data String - Data a ser Manipulada
	*	@param Separador String - Tipo de separador da data ex "/", "-"
	*	@return Boolen
	*/
	public function verificaData($Data)
	{	
		//Separador
		$Separador = $this->buscaDataSeparador($Data);
		
		//Formato da data ï¿½ aceitavel
		if(substr_count($Data, $Separador) == 2)
		{	
			//Separa os componente da data
			list($Dia, $Mes, $Ano) = explode($Separador, $Data);
			
			//Inverte
			if(strlen($Dia) == 4)
			{
				$DiaA = $Dia; //Auxiliar 
				$Dia  = $Ano;
				$Ano  = $DiaA;
			}
			
			//Ano - Considerando limites do MKTIME
			if($Ano < 1900 or $Ano > 2038) return false;
			
			return @checkdate($Mes, $Dia, $Ano);
		}
		else
		{
			return false;
		}	
	}

	public function verificaHora($Hora)
	{
		if(empty($Hora)) return false;
		
		list($H,$M,$S) = explode(":",$Hora);
		
		//Hora
		if($H > 23 or $H < 0) return false;
		
		//Minuto
		if($M <> "") 
		{
			if($M > 59 or $M < 0) return false;
		}
		
		//Segundo
		if($S <> "") 
		{
			if($S > 59 or $S < 0) return false;
		}
				
		return true; 
	}
	
	//Verifica a Data e a Hora
	function verificaDataHora($DataHora)
	{
		if(empty($DataHora)) return false;
		
		list($Data, $Hora) = explode(' ',$DataHora);
		
		$BolData = $this->verificaData($Data); 
		$BolHora = (empty($Hora)) ? true : $this->verificaHora($Hora);
		
		return ($BolData and $BolHora) ? true : false;
	}
	
	public function verificaCNPJ($Valor)
	{
		$L = strlen($Valor = str_replace(array(".","-","/"),"",$Valor));
		
		if ((!is_numeric($Valor)) || (!in_array($L,array(11,14))) || (count(count_chars($Valor,1))==1)) 
		{
			return false;
		}
		
		$CNPJ = str_split(substr($Valor,0,$L-2));
		$K = 9;
		
		for ($J=0;$J<2;$J++) 
		{
			for ($I=(count($CNPJ));$I>0;$I--) 
			{
				$S += $CNPJ[$I-1] * $K;
				$K--;
				$L==14&&$K<2?$K=9:1;
			}
			$CNPJ[] = $S%11==10?0:$S%11;
			$S = 0;
			$K = 9;
		}    
		
		return $Valor==join($CNPJ);
	}
	
	public function verificaCEP($Valor)
	{
		return (preg_match("/^[0-9]{5}-[0-9]{3}$/", $Valor)) ? true : false;
	}
	
	public function verificaFone($Valor) 
	{ 
		return (strlen($Valor) >= 1) ? true : false; 
	}
	
	/**
	*	Verifica se o CPF e Vï¿½lido
	*	@param Cpf String - Digito a Ser verificado
	*	@return Booleano
	*/
	public function verificaCpf($Cpf)
	{
		if(strlen($Cpf) > 11)
		{
			//Retira Caracteres
			@list($Parte1, $Parte2, $Parte3) = @explode(".", $Cpf);
			@list($Parte3, $Parte4)          = @explode("-",$Parte3);
		}
						
		$Cpf = $Parte1.$Parte2.$Parte3.$Parte4;
		
		if(!is_numeric($Cpf)) return false;
		
		if(($Cpf == '11111111111') || ($Cpf == '22222222222') || 
		   ($Cpf == '33333333333') || ($Cpf == '44444444444') || 
		   ($Cpf == '55555555555') || ($Cpf == '66666666666') || 
		   ($Cpf == '77777777777') || ($Cpf == '88888888888') || 
		   ($Cpf == '99999999999') || ($Cpf == '00000000000') ) 
		{ 
		     return  false; 
		} 
		else 
		{ 
			$DvInformado = substr($Cpf, 9,2); 
			
			for($I=0; $I<=8; $I++) 
			{ 
				$Digito[$I] = substr($Cpf, $I,1); 
			} 
			
			$Posicao = 10; 
			$Soma    = 0; 
			
			for($I=0; $I<=8; $I++) 
			{ 
				$Soma    = $Soma + $Digito[$I] * $Posicao; 
				$Posicao = $Posicao - 1; 
			} 
			
			$Digito[9] = $Soma % 11; 
			
			if($Digito[9] < 2) 
			{ 
				$Digito[9] = 0; 
			} 
			else 
			{ 
				$Digito[9] = 11 - $Digito[9]; 
			} 
			
			$Posicao = 11; 
			$Soma    = 0; 
			
			for ($I=0; $I<=9; $I++) 
			{ 
				$Soma    = $Soma + $Digito[$I] * $Posicao; 
				$Posicao = $Posicao - 1; 
			} 
			
			$Digito[10] = $Soma % 11; 
			
			if ($Digito[10] < 2) 
			{ 
				$Digito[10] = 0; 
			} 
			else 
			{ 
				$Digito[10] = 11 - $Digito[10]; 
			} 
			
			$Dv = $Digito[9] * 10 + $Digito[10]; 
			
			return($Dv != $DvInformado) ? false : true;
		}
	}
	
	/**
	*	Retorna se o e-mail tem formato verdadeiro ou falso
	*	@param Email String - E-mail a ser comparado
	*	@return String
	*/
	public function verificaEmail($Email)
	{ 
            return filter_var($Email, FILTER_VALIDATE_EMAIL);
	}
	
	//Retorna o valor formatado em reais
	public function moedaCliente($Valor, $Casas = NULL)
	{
		//Valor da Saida em Moeda
		if(!empty($Valor) and is_numeric($Valor))
		{
			return "R$ ".$this->floatCliente($Valor, $Casas);
		}
		else
		{
			return "R$ 0,00";
		}
	}	
	
	public function formataValor($Valor)
	{
		return $this->floatCliente($Valor);
	}

	//Retorna o valor float para o cliente entender
	public function floatCliente($Valor, $Casas = NULL)
	{		
		$Casas = (empty($Casas)) ? 4 : $Casas;
		
		if(!empty($Valor) and is_numeric($Valor))
		{
            $Partes = explode(".", $Valor);
            $Final = substr($Partes[1], 2, 3)*1;
			$Casas = ($Final) ? (empty($Casas)) ? 2 : $Casas : 2;

            return number_format($Valor, $Casas, ',', '.');
		}
		else
		{
			return "0,00";
		}
	}


    /*
    ** Retorna o valor float para o cliente entender
    ** Usado no Vizualizar
    */
	public function floatClienteVizualizar($Valor, $Casas = 2)
	{
		if(!empty($Valor) && is_numeric($Valor))
		{
			return number_format($Valor, $Casas, ',', '.');
		}
		else
		{
			return NULL;
		}
	}
	
	//Retorna o valor float para o banco entender
	public function floatBanco($Valor)
	{	
		if(!empty($Valor))
		{
			//Verifica de o nï¿½mero ja esta formatado
			if(is_numeric($Valor)) return (float) $Valor;
			
			$Valor = str_replace(".","",$Valor);
			$Valor = str_replace(",",".",$Valor);
			return  (float) $Valor;
		}
		
		return 0;
	}
	
	/**
	*	Incrementar ou Decrementar Dias, Meses ou Anos em uma data
	*	@param Data String - Data a ser Manipulada
	*	@param Dias Inteiro - Numero de dias a ser somando ou decrementado
	*	@param Meses Inteiro - Numero de meses a ser somando ou decrementado
	*	@param Anos Inteiro - Numero de anos a ser somando ou decrementado
	*	@param Separador String - Tipo de separador da data ex "/", "-"
	*	@param Operaï¿½ï¿½o String - Tipo de Operaï¿½ï¿½o soma "+" ou subtracao "-"
	*	@return String
	*/
	public function atribuiData($Data, $Dias, $Meses, $Anos, $Separador, $Operacao)
	{		
		//Verifica a Integridade da data
		if(!$this->verificaData($Data)) throw new Exception("Data informada para atribuiï¿½ï¿½o Invï¿½lida! - 001");
		
		//Valores da data
		list($Dia, $Mes, $Ano) = explode($Separador,$Data);
		
		return($Operacao == "-") 
		? date('d'.$Separador.'m'.$Separador.'Y',@mktime(0,0,0,$Mes - $Meses, $Dia - $Dias, $Ano - $Anos))
		: date('d'.$Separador.'m'.$Separador.'Y',@mktime(0,0,0,$Mes + $Meses, $Dia + $Dias, $Ano + $Anos));
	}	
	
	public function getIntervalos($DataInicial, $DataFinal)
	{	
		if(!$this->verificaData($DataInicial)) throw new Exception("Data informada para atribuiï¿½ï¿½o Invï¿½lida! - 002");
		if(!$this->verificaData($DataFinal)) throw new Exception("Data informada para atribuiï¿½ï¿½o Invï¿½lida! - 003");
		if(!$this->intervalos($DataFinal,null,$DataInicial)) throw new Exception("Intervalo invï¿½lido! - 004");
		
		//Dados da Data Inicial
		$DiaI = substr($DataInicial,0,2); 
		$MesI = substr($DataInicial,3,2);  
		$AnoI = substr($DataInicial,6,4);  
		
		//Dados da Data Final
		$DiaF = substr($DataFinal,0,2); 
		$MesF = substr($DataFinal,3,2);  
		$AnoF = substr($DataFinal,6,4);    
		
		$Diferenca = @mktime(0, 0, 0, $MesF, $DiaF, $AnoF) - @mktime(0, 0, 0, $MesI, $DiaI, $AnoI); 
		

		for($I = $AnoF; $I < $AnoF; $I++) 
		{
			$X = 0 + ($I / 4);
			if (($X - floor($X)) == 0)
			$BissextYears ++;
		}
		
		//Anos
		$Years = floor($Diferenca / (3600 * 24 * (365 + $BissextYears)));
		$Diferenca = $Diferenca % (3600 * 24 * (365 + $BissextYears));
		
		//Meses
		$Months = floor($Diferenca / (3600 * 24 * ((365  + $BissextYears)/12)));
		$Diferenca = $Diferenca % (3600 * 24 * ((365  + $BissextYears)/12));
		
		//Dias
		$Days = floor($Diferenca / (3600 * 24));
		
		$Retorno = array();
		
		$Retorno['Dias']  = ($Days + (($Years * 365)));
		$Retorno['Meses'] = ($Months) + ($Years * 12);
		$Retorno['Anos']  = $Years;
		
		return $Retorno;
	
	}

	public function getMesExt($Mes)
	{
		$Meses = array(1=>"Janeiro",2=>"Fevereiro",3=>"Março",4=>"Abril",5=>"Maio",6=>"Junho",7=>"Julho",8=>"Agosto",9=>"Setembro",10=>"Outubro",11=>"Novembro",12=>"Dezembro");

		return $Meses[(int)$Mes];
	}
	
	public function getMesAbreExt($Mes)
	{
		$Meses = array(1=>"Jan",2=>"Fev",3=>"Mar",4=>"Abr",5=>"Maio",6=>"Jun",7=>"Jul",8=>"Ago",9=>"Set",10=>"Out",11=>"Nov",12=>"Dez");

		return $Meses[(int)$Mes];
	}
	
	
	//Esta funcao foi feita para Grafico...
	public function intervaloData($DataI, $DataF)
	{		
		list($AnoI,$MesI,$DiaI) = explode("/",$this->convertData((!empty($DataI))? $DataI : date("01/m/Y")));
		list($AnoF,$MesF,$DiaF) = explode("/",$this->convertData((!empty($DataF))? $DataF : date("d/m/Y")));
		
		if ($AnoI == $AnoF and $MesI == $MesF and $DiaI <= $DiaF) {
			
			$Vetor[0]['DataI'] = $AnoI.'/'.$MesI.'/'.$DiaI;
			$Vetor[0]['Nome'] = gmdate("n/y", mktime(0, 0, 0, $MesI, 01, $AnoI));	
			$Vetor[0]['DataF'] = $AnoF.'/'.$MesF.'/'.++$DiaF;					
			return $Vetor;
		} elseif ($AnoI == $AnoF and $MesI != $MesF) {
			$Fim = $MesF - $MesI;
			$Mes = $MesI;
			
			for ($X = 0; $X <= $Fim; $X++ ){
				
				if ($X == 0) {
					$Vetor[$X] ['DataI'] = $AnoI.'/'.$MesI.'/'.$DiaI;
					$Vetor[$X]['Nome'] = gmdate("n/y", mktime(0, 0, 0, $MesI, 01, $AnoI));
				} else {
					$Vetor[$X] ['DataI'] = $AnoI.'/'.$Mes.'/01';
					$Vetor[$X]['Nome'] = gmdate("n/y", mktime(0, 0, 0, $Mes, 01, $AnoI));					
				}
				
				if ($X == $Fim) {
					$Vetor[$X] ['DataF'] = $AnoF.'/'.$MesF.'/'.($DiaF+1);
				} else {
					$Vetor[$X] ['DataF'] = $AnoF.'/'.++$Mes.'/01';					
				}
			}
			
		} elseif ($AnoI != $AnoF and $MesI == $MesF) {
			$Fim = 12*($AnoF - $AnoI);
			$Mes = $MesI;
			$Ano = $AnoI;
			
			for ($X = 0; $X <= $Fim; $X++ ){
				
				if ($X == 0) {
					$Vetor[$X] ['DataI'] = $AnoI.'/'.$MesI.'/'.$DiaI;
					$Vetor[$X]['Nome'] = gmdate("n/y", mktime(0, 0, 0, $MesI, 01, $AnoI));				
				} else {
					$Vetor[$X] ['DataI'] = $Ano.'/'.$Mes.'/01';
					$Vetor[$X]['Nome'] = gmdate("n/y", mktime(0, 0, 0, $Mes, 01, $Ano));
				}
				
				if ($X == $Fim) {
					$Vetor[$X] ['DataF'] = $AnoF.'/'.$MesF.'/'.($DiaF+1);
				} elseif ($Mes == 12) {
					$Mes = 01;
					++$Ano;
					$Vetor[$X] ['DataF'] = $Ano.'/'.$Mes.'/01';					
				} else {
					$Vetor[$X] ['DataF'] = $Ano.'/'.++$Mes.'/01';	
				}
			}
			
		} elseif ($AnoI != $AnoF and $MesI != $MesF) {
			$X = 0;
			for($Ano = $AnoI; $Ano <= $AnoF; $Ano++){
				
				($X == 0)? $VarAno = $MesI : $VarAno = 01;
				
				if($AnoF != $Ano){
					$VarMes = 12;
				} else {
					$VarMes = $MesF;
				}
				
				
				
				for($Mes = $VarAno; $Mes <= $VarMes; $Mes++){
					
					if($X == 0) {
						$Vetor[$X] ['DataI'] = $Ano.'/'.$Mes.'/'.$DiaI;
						$Vetor[$X]['Nome'] = gmdate("n/y", mktime(0, 0, 0, $Mes, 01, $Ano));
					} else {
						$Vetor[$X] ['DataI'] = $Ano.'/'.$Mes.'/01';
						$Vetor[$X]['Nome'] = gmdate("n/y", mktime(0, 0, 0, $Mes, 01, $Ano));
					}
					
					
					if($Mes == $MesF and $Ano == $AnoF) {
						$Vetor[$X] ['DataF'] = $Ano.'/'.$Mes.'/'.($DiaF+1);
					} elseif ($Mes == 12) {
						$Vetor[$X] ['DataF'] = ($Ano+1).'/01/01';
					} else {
						$Vetor[$X] ['DataF'] = $Ano.'/'.($Mes+1).'/01';
					}
					$X++;					
				}
			}
			
		} else {
			exit("Nï¿½o existem dados para geraï¿½ï¿½o do Grafico!");
		}
		
		return $Vetor;		
	}
	
	//Retorna o ultimo valor de uma divisï¿½o nï¿½o exata
	public function getUltimoValor($ValorTotal, $NumeroDivisoes)
	{			
		$ValorDividido = bcdiv($ValorTotal, $NumeroDivisoes,2);		
		
		$MultValor = bcmul($NumeroDivisoes,$ValorDividido,2);
		
		if($MultValor > $ValorDividido)
		{
			return $ValorDividido - ($MultValor - $ValorTotal);
		}
		elseif ($MultValor < $ValorDividido)
		{
			return $ValorDividido + ($ValorTotal - $ValorDividido);
		}
		else 
		{
			return $ValorDividido;
		}
	}
	
	
	/*
		retorna o valor descontado de um unico iten
		$ValorIten     = Valor do Iten 
		$ValorTotal    = Valor da Soma de Todos os Itens
		$DescontoTotal = Total do Desconto em Todos os Itens
	*/
	public function valorDescontado($ValorIten, $ValorTotal, $DescontoTotal)
	{
		$Desconto = (100 - $DescontoTotal);
		
		if($ValorIten == 0 or $Desconto == 0) return $ValorIten;
			
		$Resultado = bcdiv(($ValorTotal * 100),($ValorIten * $Desconto),2);
		
		return $this->floatBanco($Resultado);
	}
	
	/**
	* Fomarto de Data Brasileira.
	* Esta Funï¿½ao Retorna o numero de dias entre a Data Inicial e a Data Final,
	* Caso a Data Inicial for Menor que a Data Final Retornara a Exceï¿½ï¿½o.
	* Aceitando Esse tres Formato.
	* Ex: 01.01.2008, 01-01-2008 ou 01/01/2008
	*/
	public function getIntervaloDia($DataInicio, $DataFim)
	{	
		list($DiaI, $MesI, $AnoI) = split('[/.-]', $DataInicio);
		list($DiaF, $MesF, $AnoF) = split('[/.-]', $DataFim);
		
		$MkTimeInicio 	= mktime(0, 0, 0, $MesI, $DiaI, $AnoI);
		$MkTimeFim 	= mktime(0, 0, 0, $MesF, $DiaF, $AnoF);
		
		if(($MkTimeFim-$MkTimeInicio) >= 0)
		{
			return round(($MkTimeFim-$MkTimeInicio)/86400);
		}
		else
		{
			throw new Exception("Data Inicial Maior Que a Data Final"); 
		}
	}	
	
	/**
	 * Funcao retorna o primeiro nao-nulo da lista
	 * Possui uma funcao equivalente MySql
	 */
    public function coalesce() 
    {
	    $Args = func_get_args();
	    
	    foreach ($Args as $Arg) 
	    {
	       if (!empty($Arg)) 
	       {
	           return $Arg;
	       }
	    }
	    
	    return $args[0];
	}
	
	
	//Limpa Uma String Para Ser Apresentado em mensagens Javascript
	function limpaStringJS($String)
	{
		$Partes  = preg_split("/([^[:graph:]_])/", trim($String));
		
		return implode(' ',$Partes);
	}
	
	function formataDataHora($Data, $Hora) 
	{
		//Converte Data para os valores do Banco
		$Data = $this->convertDataHora($Data);
		
		//verifica se a hora estï¿½ preenchida
		if(!empty($Hora)) {
			//Verifica se o horï¿½rio ï¿½ valido (00:00 atï¿½ 23:59)
			$verificaHora = $this->verificaHora($Hora);
			//Caso nï¿½o seje, retorna um erro.
			if(empty($Hora)) throw new Exception("ATENï¿½ï¿½O: A valor digitado no horï¿½rio nï¿½o estï¿½ correta!");
		}
		
		//Junta a Data com o Horario
		$Data  = trim($Data." ".$Hora);
		
		//Verifica se estï¿½ vazio ou nï¿½o. Caso esteja, retorna NULL
		return $DataHora = (empty($Data) ? 'NULL' : $Data);
	}
	
	/*
	*	Função que retorna o numero de meses entre duas datas
	*/
	function nIntervaloMeses($DataInicial, $DataFinal)
	{
		if(!$this->verificaData($DataInicial)) throw new Exception("Data Inicial Inválida!");
		if(!$this->verificaData($DataFinal))   throw new Exception("Data Final Inválida!");
		if($DataInicial > $DataFinal)          throw new Exception("A data inicial não pode ser maior que a dara final!");
		
		$NMeses = 0;
		
		list($AnoIncial, $MesIncial) = explode("/",$DataInicial);
		list($AnoFinal,  $MesFinal)  = explode("/",$DataFinal);
		
		if($DataInicial == $DataFinal) return 0;
		
		$DataFinal = $AnoFinal.(abs($MesFinal));
				
		for ($A = $AnoIncial; $A <= $AnoFinal; $A++)
		{
			for ($M = abs($MesIncial); $M <= 12; $M++)
			{	
				$NMeses +=1;
				
				if(($A.$M) == ($DataFinal))
				{
					return $NMeses;
				}
				else if($M == 12) 
				{
					$M = 1;
					break 1;
				}
			}
		}

		return $NMeses;	
	}

     public function arrayPHPtoJS($array){

        if( !is_array($array) ){
            return false;
        }

        $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
        if( $associative ){
            $construct = array();
            foreach( $array as $key => $value ){

                // We first copy each key/value pair into a staging array,
                // formatting each key and value properly as we go.

                // Format the key:
                if( is_numeric($key) ){
                    $key = "$key";
                }
                $key = "'".addslashes($key)."'";

                // Format the value:
                if( is_array( $value )){
                    $value = $this->arrayPHPtoJS($value);
                } else if( !is_numeric( $value ) || is_string( $value ) ){
                    $value = "'".addslashes($value)."'";
                }

                // Add to staging array:
                $construct[] = "$key: $value";
            }

            // Then we collapse the staging array into the JSON form:
            $result = "{ " . implode( ", ", $construct ) . " }";

        } else { // If the array is a vector (not associative):
            
            $construct = array();
            foreach( $array as $value ){

                // Format the value:
                if( is_array( $value )){
                    $value = array_to_json( $value );
                } else if( !is_numeric( $value ) || is_string( $value ) ){
                    $value = "'".addslashes($value)."'";
                }

                // Add to staging array:
                $construct[] = $value;
            }

            // Then we collapse the staging array into the JSON form:
            $result = "[ " . implode( ", ", $construct ) . " ]";
        }

        return $result;
    }
	
	
	
	
	/* Funï¿½ï¿½o que verifica navegador e retorna sugestï¿½o
	 * By: Roger (rogersborchia@live.com);
	 */
	 public function alertaNavegador()
	 {
		//Inicia Verificaï¿½ï¿½es
		$user_agente 	= $_SERVER["HTTP_USER_AGENT"];
		$Browser_Nome 	= strtok($user_agente, "/");
		$Browser_Versao = strtok(" ");

		//Opera
		if(preg_match("/Opera/", $user_agente))  {
			$Browser_Nome = "Opera"; 
		}
		//Safari
		if(preg_match("/Safari/", $user_agente))    {
			$Browser_Nome = "Safari";
			$Browser_Versao = strtok("Safari");
			$Browser_Versao = strtok("/");
			$Browser_Versao = strtok("/");
			$Browser_Versao = strtok(" ");
		}
		//Chrome
		if(preg_match("/Chrome/", $user_agente)) {
			$Browser_Nome = "Chrome"; 
		}
		//Internet Explorer
		if(preg_match("/MSIE/",$user_agente)) {
			$Browser_Nome = "Internet Explorer";
			$Browser_Versao = strtok("MSIE");
			$Browser_Versao= strtok(" ");
			$Browser_Versao = strtok(";");
		}
		//FireFox
		if(preg_match("/Firefox/", $user_agente))    {
			$Browser_Nome = "Firefox";
			$Browser_Versao = strtok("Firefox");
			$Browser_Versao = strtok("/");
			$Browser_Versao = strtok("/");
			$Browser_Versao = strtok(" ");
		}

		//OPEN
	 	$URL 		= "http://www.demp.com.br/atualizar/index.php?Browser=".$Browser_Nome."&Versao=".$Browser_Versao;
		$AbreURL 	= @fopen($URL,"r");

		while(!@feof($AbreURL)) {
			$RetornaArquivo .= @fread($AbreURL, 8192);
		}
		
		@fclose($AbreURL);		
		return $RetornaArquivo;
	 }

         function valorPorExtenso($Valor = 0)
         {
            $Singular = array("centavo", "real", "mil", "milhï¿½o", "bilhï¿½o", "trilhï¿½o", "quatrilhï¿½o");
            $Plural   = array("centavos", "reais", "mil", "milhï¿½s", "bilhï¿½es", "trilhï¿½es", "quatrilhï¿½es");
            $Centena  = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
            $Dezena   = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
            $DezenaD  = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
            $Unidade  = array("", "um", "dois", "trï¿½s", "quatro", "cinco", "seis", "sete", "oito", "nove");

//          $Singular = array("cent", "dollar", "thousand", "million", "billion", "trillion", "quadrillion");
//          $Plural   = array("cents", "dollars", "thousand", "million", "billion", "trillion", "quadrillion");
//          $Centena  = array("", "one hundred", "two hundred", "three hundred", "four hundred", "five hundred", "six hundred", "seven hundred", "eight hundred", "nine hundred");
//          $Dezena   = array("", "ten", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety");
//          $DezenaD  = array("ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen");
//          $Unidade  = array("", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine");
            
            $Cont    = 0;
            $Valor   = $this->floatBanco($Valor);
            $Valor   = number_format($Valor, 2, ".", ".");
            $Inteiro = explode(".", $Valor);

            for($I = 0; $I < count($Inteiro); $I++)
            {
                for($II = strlen($Inteiro[$I]); $II < 3; $II++)
                {
                    $Inteiro[$I] = "0".$Inteiro[$I];
                }
            }

            $Fim = count($Inteiro) - ($Inteiro[count($Inteiro)-1] > 0 ? 1 : 2);


            for($I = 0; $I < count($Inteiro); $I++)
            {
                $Valor = $Inteiro[$I];

                $RCentena = (($Valor > 100) && ($Valor < 200)) ? "cento" : $Centena[$Valor[0]];
                $RDezena  = ($Valor[1] < 2) ? "" : $Dezena[$Valor[1]];
                $RUnidade = ($Valor > 0) ? (($Valor[1] == 1) ? $DezenaD[$Valor[2]] : $Unidade[$Valor[2]]) : "";

                $R = $RCentena.(($RCentena && ($RDezena || $RUnidade)) ? " e " : "").$RDezena.(($RDezena && $RUnidade) ? " e " : "").$RUnidade;
                $T = count($Inteiro)-1-$I;
                $R.= $R ? " ".($Valor > 1 ? $Plural[$T] : $Singular[$T]) : "";

                if($Valor == "000")
                {
                    $Cont++;
                }
                elseif($Cont > 0)
                {
                    $Cont--;
                }

                if(($T==1) && ($Cont>0) && ($Inteiro[0] > 0))
                {
                    $R .= (($Cont>1) ? " de " : "").$Plural[$T];
                }

                if($R)
                {
                    $RT = $RT.((($I > 0) && ($I <= $Fim) && ($Inteiro[0] > 0) && ($Cont < 1)) ? ( ($I < $Fim) ? ", " : " e ") : " ") . $R;
                }

            }

            return ($RT ? $RT : "zero");
        }


    public function ordenaVetor($Vetor)
    {
        $Original = $Vetor;
        
        foreach($Vetor as $Posicao => $String)
        {
            $Vetor[$Posicao] = $this->removeAcentos($String);
        }
        
        natcasesort($Vetor);
        
        foreach($Vetor as $Posicao => $String)
        {
            $Vetor[$Posicao] = $Original[$Posicao];
        }
        
        return $Vetor;
    }
    
        
    public function removeAcentos($String)
    {
	return strtr($String,'???????¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ','SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');        
    }
}