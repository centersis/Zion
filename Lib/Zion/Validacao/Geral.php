<?php
/**
 * Geral
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 24/9/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Tratamento de inputs específicamente Brasileiros.
 * 
 */
namespace Zion\Validacao;

class Geral
{
    public function __construct()
    {
        /***DEBUG: AINDA EM USO, FAVOR DESCONSIDERAR.
        print 'CPF: '. $this->validaCPF('024.016.271-46') .' ----'. $this->formataCPF('024.016.271-46') .'<hr>';
        print 'CNPJ: '. $this->validaCNPJ('10.582.517/0001-90') .' ----'. $this->formataCNPJ('10582517000190') .'<hr>';
        print 'CEP: '. $this->validaCEP('00000-198') .' ----'. $this->formataCEP('78.050-198') .'<hr>';
        */
    }

    public function validaCPF($cpf)
    {
		if(strlen($cpf) > 11)
		{
			//Retira Caracteres
			@list($Parte1, $Parte2, $Parte3) = @explode(".", $cpf);
			@list($Parte3, $Parte4)          = @explode("-",$Parte3);

            $cpf = $Parte1.$Parte2.$Parte3.$Parte4;
		}
							
		if(!is_numeric($cpf)) return false;
		
		if(($cpf == '11111111111') || ($cpf == '22222222222') || 
		   ($cpf == '33333333333') || ($cpf == '44444444444') || 
		   ($cpf == '55555555555') || ($cpf == '66666666666') || 
		   ($cpf == '77777777777') || ($cpf == '88888888888') || 
		   ($cpf == '99999999999') || ($cpf == '00000000000') ) 
		{ 
		     return  false; 
		} 
		else 
		{ 
			$DvInformado = substr($cpf, 9,2); 
			
			for($I=0; $I<=8; $I++) 
			{ 
				$Digito[$I] = substr($cpf, $I,1); 
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
    
    public function formataCPF($cpf){

        $cpfFormatado = NULL;
        
        if(preg_match('/^\d{3}.\d{3}.\d{3}-\d{2}$/', $cpf)) return($this->validaCPF($cpf) === true ? $cpf : false);
        
        if($this->validaCPF($cpf)){

            $cpfFormatado = substr($cpf, 0, 3) .'.'. substr($cpf, 3, 3) .'.'. substr($cpf, 6, 3) .'-'. substr($cpf, -2);

        } else {

            $cpfFormatado = false;

        }
        
        return $cpfFormatado;
    }
    
    public function validaCNPJ($cnpj)
    {

    	$j=0;
    	for($i=0; $i<(strlen($cnpj)); $i++)
    		{
    			if(is_numeric($cnpj[$i]))
    				{
    					$num[$j]=$cnpj[$i];
    					$j++;
    				}
    		}

    	if(count($num)!=14)
    		{
    			$isCnpjValid=false;
    		}

    	if (array_sum($num) == 0)
    		{
    			$isCnpjValid=false;
    		}
    	else
    		{
    			$j=5;
    			for($i=0; $i<4; $i++)
    				{
    					$multiplica[$i]=$num[$i]*$j;
    					$j--;
    				}
    			$soma = array_sum($multiplica);
    			$j=9;
    			for($i=4; $i<12; $i++)
    				{
    					$multiplica[$i]=$num[$i]*$j;
    					$j--;
    				}
    			$soma = array_sum($multiplica);	
    			$resto = $soma%11;			
    			if($resto<2)
    				{
    					$dg=0;
    				}
    			else
    				{
    					$dg=11-$resto;
    				}
    			if($dg!=$num[12])
    				{
    					$isCnpjValid=false;
    				} 
    		}

    	if(!isset($isCnpjValid))
        
    		{
    			$j=6;
    			for($i=0; $i<5; $i++)
    				{
    					$multiplica[$i]=$num[$i]*$j;
    					$j--;
    				}
    			$soma = array_sum($multiplica);
    			$j=9;
    			for($i=5; $i<13; $i++)
    				{
    					$multiplica[$i]=$num[$i]*$j;
    					$j--;
    				}
    			$soma = array_sum($multiplica);	
    			$resto = $soma%11;			
    			if($resto<2)
    				{
    					$dg=0;
    				}
    			else
    				{
    					$dg=11-$resto;
    				}
    			if($dg!=$num[13])
    				{
    					$isCnpjValid=false;
    				}
    			else
    				{
    					$isCnpjValid=true;
    				}
    		}

    	return $isCnpjValid;
    }
    
    public function formataCNPJ($cnpj){

        $cnpjFormatado = NULL;

        if(preg_match('/^\d{2}\.\d{3}.\d{3}\/\d{4}-\d{2}$/', $cnpj)) return($this->validaCNPJ($cnpj) === true ? $cnpj : false);

        if($this->validaCNPJ($cnpj)){

            $cnpjFormatado = substr($cnpj, 0, 2) .'.'. substr($cnpj, 2, 3) .'.'. substr($cnpj, 5, 3) .'/'. substr($cnpj, 8, 4) .'-'. substr($cnpj, -2);

        } else {

            $cnpjFormatado = false;

        }
        
        return $cnpjFormatado;
    }

    
    public function validaCEP($cep)
    {
        if(preg_match('/^\d{2}.\d{3}|\d{5}[-|\s]?[0-9]{3}$|^[0-9]{8}$/', $cep, $matches, PREG_OFFSET_CAPTURE)){

            $cepValido = (int) preg_replace('/[^0-9]/', '', $cep);
            return($cepValido > 0 ? true : false);
        }
    }

    public function formataCEP($cep){

        $cepFormatado = NULL;

        if(preg_match('/^\d{2}\.\d{3}[-|\s]?[0-9]{3}$/', $cep)) return($this->validaCEP($cep) === true ? $cep : false);

        $cep = preg_replace('/[^0-9]/', '', $cep);

        if($this->validaCEP($cep)){

            $cepFormatado = substr($cep, 0, 2) .'.'. substr($cep, 2, 3) .'-'. substr($cep, -3);

        } else {

            $cepFormatado = false;

        }
        
        return $cepFormatado;
    }

}