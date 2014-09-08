﻿<?//Classes Nescessáriasinclude_once($_SESSION['FMBase'].'funcoes_php.class.php');class ValidacaoPHP extends FuncoesPHP {		public function validar($Config, $TipoCampo)	{			try 		{			//Define Maximo e Minimo			$Config['VMax'] = $this->getMaxMin($Config['Nome'],$Config['VMax']);			$Config['VMin'] = $this->getMaxMin($Config['Nome'],$Config['VMin']);			$Config['Max']  = $this->getMaxMin($Config['Nome'],$Config['Max']);			$Config['Min']  = $this->getMaxMin($Config['Nome'],$Config['Min']);						//Filtra o Tipo de Campo			switch ($TipoCampo)			{				//Tipo Texto				case "Texto" : 										if($Config['Tratar'] == "" ) $Config['Tratar'] = array("L","H","A"); 										if($Config['Obrigatorio'] == true)					if($Config['Valor'] == "") throw new Exception("Valor informado é inválido!");										//Verifica Tamanho					$this->varificaTamanho($Config['Valor'], $Config['VMax'], $Config['VMin'], 1);					$this->varificaTamanho($Config['Valor'], $Config['Max'], $Config['Min'], 2);										//Retorna Valor					return $this->tratar($Config['Valor'], $Config['Tratar']);										break;									//Tipo Hidden				case "Hidden" : 														if($Config['Tratar'] == "" ) $Config['Tratar'] = array("L","H","A"); 									//Retorna Valor					return $this->tratar($Config['Valor'], $Config['Tratar']);										break;									//Tipo Select				case "Select" : 									if($Config['Tratar'] == "" ) $Config['Tratar'] = array("L","H","A"); 																				if($Config['Obrigatorio'] == true)					if(strlen($Config['Valor']) < 1) throw new Exception("Valor selecionado é inválido!");										//Retorna Valor					return $this->tratar($Config['Valor'], $Config['Tratar']);										break;					//Tipo Inteiro				case "Inteiro" :										//Valida					if(!is_numeric($Config['Valor'])) throw new Exception("Valor Informado não é um valor numérico válido!");										//Verifica Tamanho da Sring Numérica					$this->varificaTamanho($Config['Valor'], $Config['VMax'], $Config['VMin'], 2);					$this->varificaTamanho($Config['Valor'], $Config['Max'], $Config['Min'], 1);										//Retorna Valor					return intval($Config['Valor']);									break;									//Tipo Flutuante				case "Float" :															if($Config['Valor'] == "") throw new Exception("Valor Informado não é um valor numérico válido!");										//Valor para validação					$ValorV = $this->floatBanco($Config['Valor']);										$this->varificaTamanho($ValorV, $Config['VMax'], $Config['VMin'], 1);					$this->varificaTamanho($Config['Valor'], $Config['Max'], $Config['Min'], 2);										//Retorna Valor					return $ValorV;									break;												//Tipo Data				case "Data" :					                        if($this->dataMenor1900($Config['Valor']))                        {                            return $Config['Valor'];                        }                                                $this->varificaTamanho($Config['Valor'], $Config['VMax'], $Config['VMin'], 3);										//Retorna Valor					return $Config['Valor'];									break;								//Tipo CPF				case "CPF" :					                                    //Verifica CPF                                    if(parent::verificaCpf($Config['Valor']))                                    {                                        //Retorna Valor                                        return $Config['Valor'];                                    }                                    else                                    {                                        throw new Exception("CPF inválido!");                                    }													break;													//Tipo CNPJ				case "CNPJ" :					                                    //Verifica CNPJ                                    if(parent::verificaCNPJ($Config['Valor']))                                    {                                        //Retorna Valor                                        return $Config['Valor'];                                    }                                    else                                    {                                        throw new Exception("CNPJ inválido!");                                    }													break;																		//Tipo CNPJ				case "CEP" :										//Verifica CNPJ					if(parent::verificaCEP($Config['Valor']))					{						//Retorna Valor						return $Config['Valor'];					}					else 					{							throw new Exception("CEP inválido!");					}														break;																			//Tipo E-Mail				case "Email" :										//Verifica Email					if(parent::verificaEmail($Config['Valor']))					{						//Retorna Valor						return $Config['Valor'];					}					else 					{												throw new Exception("E-mail inválido!");					}														break;				//Tipo Telefone				case "Fone" :										//Verifica Telefone					if(parent::verificaFone($Config['Valor']))					{						//Retorna Valor						return $Config['Valor'];					}					else 					{												throw new Exception("Telefone inválido!");					}														break;																	}		}		catch (Exception $E)		{			throw new Exception($E->getMessage());		}			}				/**	*	Faz a verificação completa para saber se a variavel e válida	*	@param Var String - String a Ser comparada	*	@param Max Inteiro - Tamanho máximo da variavel	*	@param Min Inteiro - Tamanho minimo da variavel	*	@param Tipo Inteiro - 1 para Inteiro e 2 para String	*	@return Boolean	*/	public function tratar($Valor, $Modo)	{				if(is_array($Modo))		{			foreach ($Modo as $Tratamento)			{				switch ($Tratamento)				{					case "L": $Valor = parent::limpa($Valor);        break;					case "H": $Valor = parent::converteHTML($Valor); break;					case "A": $Valor = parent::trataAspas($Valor);   break;				}			}		}				//Remove apostrofo de qualqeur maneira		$Valor = str_replace("'","&#039;",$Valor);				return $Valor;	}		/*	*			*/	public function varificaTamanho($Valor, $Max, $Min, $Tipo)	{		$Var = $this->limpa($Valor);				if($Tipo == 1)//Numerico		{			//Se os dois são vazios retorna true			if($Max == "" and $Min == "") return true;						//Se os dois não são vazios verifica compatibilidade			if($Max <> "" and $Min <> "")			{				if($Min > $Max) throw new Exception("O valor minimo não pode ser maior que o valor maximo!");			}						//Validando Minimo			if($Min <> "")			{				if($Valor < $Min) throw new Exception("O valor mínimo permitido é $Min!"); 			}						//Valida maximo			if($Max <> "")			{				if($Valor > $Max) throw new Exception("O valor máximo permitido é $Max!"); 			}						return true;		}		else if($Tipo == 2)		{						//Se os dois são vazios retorna true			if($Max == "" and $Min == "") return true;						//Se os dois não são vazios verifica compatibilidade			if($Max <> "" and $Min <> "")			{				if($Min > $Max) throw new Exception("O valor minimo não pode ser maior que o valor maximo!");			}						//Validando Minimo			if($Min <> "")			{				if(strlen($Valor) < $Min) throw new Exception("O texto deve conter no minimo $Min caracteres!"); 			}						//Valida maximo			if($Max <> "")			{				if(strlen($Valor) > $Max) throw new Exception("O texto deve conter no máximo $Max carcteres!"); 			}						return true;		}		else if($Tipo == 3)		{              if($this->verificaData($Valor))			{				if($Max == "") $Max = "31/12/2037";				if($Min == "") $Min = "01/01/1900";				if($this->intervalos($Valor, $Max, $Min))				{					return true;				}				else 				{					throw new Exception("Intervalo de data inválido! Data minima: $Min e Data Maxima: $Max");				}			}			else 			{				throw new Exception("Data inválida use dd/mm/aaaa!");			}		}			return false;		}	private function getMaxMin($Nome, $Valor)	{		if(!is_numeric($Valor{0}))		{			$Retorno = $_REQUEST[$Valor];						if($Retorno <> "")			{				return $Retorno;			}			else 			{				return null;			}		}				return $Valor;	}          private function dataMenor1900($Data)     {         if(empty($Data)) return false;                   if(strlen($Data) <> 10) throw new Exception("Data Inválida!");                           list($Dia,$Mes,$Ano) = explode("/",$Data);                  if($Ano >= 1900)         {                             return false;         }         else         {             return (bool)($this->verificaData($Dia.'/'.$Mes.'/'.date('Y')));                      }     }}