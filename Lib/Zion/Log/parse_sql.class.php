<?
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

class ParseSql

{

	//Funcao que troca todas as Aspas

	public function trocaAspas($Var)

	{

		$String      = "";

		$AspaSimples = false;

		$AspaDupla   = false;

		$CharPassado = "";



		for($I = 0; $I < strlen($Var); $I++)

		{

			$CharAtual	 = $Var{$I};

			if ($I > 0) $CharPassado = $Var{$I-1};

			$CharDuplo 	 = $CharPassado.$CharAtual;

			

			if($CharAtual == "'" and $CharDuplo <> "\\'" and $AspaSimples == false and $AspaDupla <> true)

			{

				$AspaSimples = true;

			}

			else if($CharAtual == "'" and $CharDuplo <> "\\'" and $AspaSimples == true and $AspaDupla <> true)

			{

				$AspaSimples = false;

				$CharAtual  = "'";

			}

			

			if($CharAtual == '"' and $CharDuplo <> '\\"' and $AspaDupla == false and $AspaSimples <> true)

			{

				$AspaDupla = true;

			}

			else if($CharAtual == '"' and $CharDuplo <> '\\"' and $AspaDupla == true and $AspaSimples <> true)

			{

				$AspaDupla = false;

				$CharAtual = "'";

			}

			

			if($AspaSimples)

			{

				if($CharAtual == '"' and $CharDuplo == '\\"')

				{

					$CharAtual = '\"';

				}

				

				if($CharAtual == '"' and $CharDuplo <> '\\"')

				{

					$CharAtual = '\"';

				}

				

				if($CharAtual == "'" and $CharDuplo == "\\'")

				{

					$CharAtual = "'";

				}

				

				if($CharAtual == "'" and $CharDuplo <> "\\'")

				{

					$CharAtual = "'";

				}

				

			}

			

			

			if($AspaDupla)

			{

				if($CharAtual == "'" and $CharDuplo == "\\'")

				{

					$CharAtual = "'";

				}

				

				if($CharAtual == "'" and $CharDuplo <> "\\'")

				{

					$CharAtual = "\'";

				}

				

				if($CharAtual == '"' and $CharDuplo <> '\\"')

				{

					$CharAtual = "'";

				}

				

				if($CharAtual == '"' and $CharDuplo == '\\"')

				{

					$CharAtual = '"';

				}				

			}

			

			$String .= $CharAtual;

		}

		return $String;

	}

	

	//Funcao que Quebra o Sql em 3 Partes

	public function quebraInsertSql($Sql)

	{

		$Vetor  = preg_split("/([^[:alnum:]_])/", trim($Sql));

				

		$Numero  = 0;

		$PosicaoI = 0;

		$PosicaoV = 0;

		foreach($Vetor as $Aux){

			if(strcasecmp('into', $Aux) == 0 and $PosicaoI == 0)

			{

				$PosicaoI = $Numero;

			}

			if(strcasecmp('values', $Aux) == 0 and $PosicaoV == 0)

			{

				$PosicaoV = $Numero;

			}

			$Numero++;

		}

		$Array = array();

		$RS = explode($Vetor[$PosicaoI], $Sql);

		$Array[0] = $RS[0];

		$RS = explode($Vetor[$PosicaoV], $RS[1]);

		$Array[1] = $RS[0];

		$Array[2] = $RS[1];

		

		return $Array;

	}

	

	//Funcao que retorna uma Array Com Valores

	public function valoresInsert($Sql)

	{

		$Parte 		= $this->quebraInsertSql($Sql);

		$ParteSql 	= $this->trocaAspas($Parte[2]);

		$Cont 		= 0;

		$Vetor 		= array();

		$Aspa		= false;

		$SemAspa	= false;

		$CharPassado = "";



		for($I = 0; $I < strlen($ParteSql); $I++)

		{

			$CharAtual	 = $ParteSql{$I};

			if ($I > 0) $CharPassado = $ParteSql{$I-1};

			$CharDuplo 	 = $CharPassado.$CharAtual;

			

			//Com Aspas

			if($CharAtual == "'" and $CharDuplo <> "\\'" and $Aspa == false)

			{

				$Aspa = true;

				$CharAtual = "";

				

			}

			else if($CharAtual == "'" and $CharDuplo <> "\\'" and $Aspa == true)

			{

				$Aspa = false;

				$Vetor[$Cont] .= "";

				++$Cont;

			}		

			

			

			//Sem Aspas

			if($SemAspa == false and $Aspa == false)

			{

				$SemAspa = true;

			}

			else if($CharAtual == "," and $SemAspa == true and $Aspa == false)

			{

				$SemAspa = false;

				$Cont++;

			}



			//Armazenamento no Vetor

			if($Aspa == true)

			{

				$Vetor[$Cont] .= $CharAtual;	

			}else if($SemAspa == true){ 

				if($CharAtual != "(" and $CharAtual != "," and $CharAtual != ")" and $CharAtual != "'"){

					@$Vetor[$Cont] .= $CharAtual;

				}

			}

		}

		

		//Limpando o Vetor Para Retorna

		$Array = array();

		

		foreach($Vetor as $Limpando)

		{

			$Limpando = trim($Limpando);

			if(!empty($Limpando))

			{

				$Array[] = $Limpando;

			}

		}

		

		return $Array;

	}

	

	//Funcao Que Controla Todas e Retorna um Array de 3 Posicoes

	public function parseInsert($Sql)

	{

		$ArrayInsert = array();

		$ArraySuport = array();

		$ArrayValores= $this->valoresInsert($Sql);

				

		$Parte = $this->quebraInsertSql($Sql);

		$Rs = preg_split("/([^[:alnum:]_])/", trim($Parte[0]));

		$ArrayInsert['Operacao'] = $Rs[0];

		

		$Array = preg_split("/([^[:alnum:]_])/", trim($Parte[1]));

		

		foreach($Array as $Aux)

		{

			if(!empty($Aux))

			{

				if(count($ArrayInsert) == 1)

				{

					$ArrayInsert['Tabela'] = $Aux;

				}else{

					$ArraySuport[] = $Aux;	

				}	

			}

		}

		

		$VariavelAux = "array(";

		for($I = 0; $I < count($ArraySuport); $I++)

		{

			if($I == 0){

				$VariavelAux .= '"'.$ArraySuport[$I].'"=>"'.$ArrayValores[$I].'"';

			}else{

				$VariavelAux .= ',"'.$ArraySuport[$I].'"=>"'.$ArrayValores[$I].'"';	

			}

		}

		$ArrayInsert['Array'] = $VariavelAux.")";

		

		return $ArrayInsert;

		

	}



        //Funcao que retorna apenas os atributos de um insert - Tambem Serve Para Replace

	public function getAtributosInsert($Sql)

	{

            $ArrayAtributos = array();



            $Parte = $this->quebraInsertSql($Sql);



            $Array = preg_split("/([^[:alnum:]_])/", trim($Parte[1]));



            $Cont = 0;

            

            foreach($Array as $Aux)

            {

                if(!empty($Aux))

                {

                    $Cont++;

                   

                    if($Cont != 1)

                    {

                        $ArrayAtributos[] = $Aux;

                    }

                }

            }

            

            return $ArrayAtributos;

	}





    //Funcao que retorna apenas os atributos de um update

    public function getAtributosUpdate($Sql)

    {

        $ArrayAtributos = array();

        

        $PartesSql  = $this->quebraUpdateSql($Sql);



        eval('$Valores = '.$this->valoresUpdate($PartesSql[1]).';');



        //Monta Vetor de Campos

        foreach($Valores as $Chave=>$Valor)

        {

            $Valor = trim($Valor);



            if($Valor == "%s") $ArrayAtributos[] = $Chave;

        }



        $ArrayAtributos[] = "Id";



        return $ArrayAtributos;

    }





	/*

	*	Funcoes para o Parse UPDATE

	*/

	

	public function quebraUpdateSql($Sql)

	{

		$Vetor  = preg_split("/([^[:alnum:]_])/", trim($Sql));



		$Numero  = 0;

		$PosicaoS = 0;

		$PosicaoW = 0;

		foreach($Vetor as $Aux){

			if(strcasecmp('set', $Aux) == 0 and $PosicaoS == 0)

			{

				$PosicaoS = $Numero;

			}

			if(strcasecmp('where', $Aux) == 0)

			{

				$PosicaoW = $Numero;

			}

			$Numero++;

		}

		$Array 		= array();

		$RS 		= explode($Vetor[$PosicaoS], $Sql);

		$Array[0] 	= trim($RS[0]);

		$RS 		= explode($Vetor[$PosicaoW], $RS[1]);

		$Array[1] 	= trim($RS[0]);

		$Array[2] 	= trim($RS[1]);

		

		return $Array;

	}

	

	//Funcao Que Controla Todas e Retorna um Array de 4 Posicoes

	public function parseUpdate($Sql)

	{

		$ArrayUpdate = array();

		$ArraySuport = array();

				

		$Parte = $this->quebraUpdateSql($Sql);

		$Rs = preg_split("/([^[:alnum:]_])/", trim($Parte[0]));

		$ArrayUpdate['Operacao'] 	= $Rs[0];

		$ArrayUpdate['Tabela'] 		= $Rs[1];		

		$ArrayUpdate['Array'] 		= $this->valoresUpdate($Parte[1]);		

		$ArrayUpdate['Comparacao'] 	= trim($Parte[2]);		

		

		

		return $ArrayUpdate;

	}

	

	public function valoresUpdate($Var)

	{

		$ParteSql 	= $this->trocaAspas($Var);

		$FinalString= (strlen($ParteSql)-1);

		$String		= "array(";

		$Aspa		= false;

		$SemAspa	= false;

		

		for($I = 0; $I < strlen($ParteSql); $I++)

		{

			$CharAtual	 = $ParteSql{$I};

			$CharPassado = $ParteSql{$I-1};

			$CharDuplo 	 = $CharPassado.$CharAtual;

			

			//Com Aspas

			if($CharAtual == "'" and $CharDuplo <> "\\'" and $Aspa == false)

			{

				$Aspa = true;

				$CharAtual = "";				

			}

			else if($CharAtual == "'" and $CharDuplo <> "\\'" and $Aspa == true)

			{

				$Aspa = false;

				$String .= "";

			}				

			

			//Sem Aspas

			if($SemAspa == false and $Aspa == false)

			{

				$SemAspa = true;

				$String .= '"';

			}

			else if($SemAspa == true and $Aspa == true)

			{

				$SemAspa = false;

			}



			//Armazenamento em Uma String

			if($Aspa)

			{

				$String .= $CharAtual;

			}

			elseif($SemAspa and $CharAtual <> "'")

			{

				if($CharAtual == "=")

				{ 

					$String .= '"=>"';

				}

				else if($CharAtual == ",")

				{

					$String .= '","';

				}				

				else if($CharAtual == " ")

				{

					$String .= '';

				}

				else if($I == $FinalString)

				{

					$String .= $CharAtual.'"';

				}

				else

				{

					$String .= $CharAtual;

				}				

			}

		}

		$String .= ")";

		return str_replace('""', '"', $String);

	}

	

	/*

	*	Funcoes para o Parse DELETE

	*/

	

	public function parseDelete($Sql)

	{

		$Vetor  = preg_split("/([^[:alnum:]_])/", trim($Sql));



		$ArrayUpdate = array();

		$Numero  = 0;

		$PosicaoF = 0;

		$PosicaoW = 0;

		foreach($Vetor as $Aux){

			if(strcasecmp('from', $Aux) == 0 and $PosicaoF == 0)

			{

				$PosicaoF = $Numero;

			}

			if(strcasecmp('where', $Aux) == 0 and $PosicaoW == 0)

			{

				$PosicaoW = $Numero;

			}

			$Numero++;

		}

		$RS 		= explode($Vetor[$PosicaoF], $Sql);

		$ArrayUpdate['Operacao']	= trim($RS[0]);

		$RS 		= explode($Vetor[$PosicaoW], $RS[1]);

		$ArrayUpdate['Tabela'] 		= $this->primeiraTabela(trim($RS[0]));

		$ArrayUpdate['Comparacao'] 	= trim($RS[1]);

		

		return $ArrayUpdate;

	}

	

	public function primeiraTabela($Parte)

	{

		$Vetor = explode( " ", $Parte);

		return $Vetor[0];

	}	

}

?>

