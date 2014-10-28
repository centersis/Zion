<?php
/**
 * @author Feliphe "O Retaliador" Bueno - feliphezion@gmail.com
 * @since 24/9/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Validação de inputs específicamente Brasileiros.
 * 
 */
namespace Zion\Validacao;

class Geral extends \Zion\Tratamento\Geral
{

    /** 
     * @var object $instancia Instância da classe singleton
     */
    private static $instancia;

    /**
     * Geral::__construct()
     * Construtor, tão tosco quanto necessário para a implementação singleton.
     * 
     * @return void
     */
    private function __construct(){
        
    }

    /**
     * Geral::instancia()
     * Retorna sempre a mesma instância da classe, de acordo com o Singleton pattern.
     * 
     * @return object
     */
    public static function instancia(){

        if(!isset(self::$instancia)){
            self::$instancia = new self;
        }

        return self::$instancia;
    }

    /**
     * Geral::validaCPF()
     * 
     * @param mixed $cpf
     * @return
     */
    public function validaCPF($cpf)
    {
		if(strlen($cpf) > 11) {
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
 
		} else {

			$DvInformado = substr($cpf, 9,2); 
			
			for($I=0; $I<=8; $I++) { 
				$Digito[$I] = substr($cpf, $I,1); 
			} 
			
			$Posicao = 10; 
			$Soma    = 0; 
			
			for($I=0; $I<=8; $I++) { 
				$Soma    = $Soma + $Digito[$I] * $Posicao; 
				$Posicao = $Posicao - 1; 
			} 
			
			$Digito[9] = $Soma % 11; 
			
			if($Digito[9] < 2) 
			{ 
				$Digito[9] = 0; 
			} else { 
				$Digito[9] = 11 - $Digito[9]; 
			}
			
			$Posicao = 11; 
			$Soma    = 0; 
			
			for ($I=0; $I<=9; $I++) { 
				$Soma    = $Soma + $Digito[$I] * $Posicao; 
				$Posicao = $Posicao - 1; 
			} 
			
			$Digito[10] = $Soma % 11; 
			
			if ($Digito[10] < 2) { 
				$Digito[10] = 0; 
			} else { 
				$Digito[10] = 11 - $Digito[10]; 
			}
			
			$Dv = $Digito[9] * 10 + $Digito[10]; 
			
			return($Dv != $DvInformado) ? false : true;
		}
    }
   
    /**
     * Geral::validaCNPJ()
     * 
     * @param mixed $cnpj
     * @return
     */
    public function validaCNPJ($cnpj)
    {

    	$j=0;
    	for($i=0; $i<(strlen($cnpj)); $i++){
    		if(is_numeric($cnpj[$i])){
				$num[$j]=$cnpj[$i];
				$j++;
			}
    	}
    
    	if(count($num)!=14){
    		$isCnpjValid=false;
    	}
    
    	if (array_sum($num) == 0){
    			$isCnpjValid=false;
		} else {
			$j=5;
			for($i=0; $i<4; $i++){
				$multiplica[$i]=$num[$i]*$j;
				$j--;
			}
			$soma = array_sum($multiplica);
			$j=9;
			for($i=4; $i<12; $i++) {
				$multiplica[$i]=$num[$i]*$j;
				$j--;
			}
			$soma = array_sum($multiplica);	
			$resto = $soma%11;			
			if($resto<2){
				$dg=0;
			} else {
				$dg=11-$resto;
			}

			if($dg!=$num[12]) {
				$isCnpjValid=false;
			} 
		}

    	if(!isset($isCnpjValid)) {
			$j=6;
			for($i=0; $i<5; $i++) {
				$multiplica[$i]=$num[$i]*$j;
				$j--;
			}
			$soma = array_sum($multiplica);
			$j=9;
			for($i=5; $i<13; $i++) {
				$multiplica[$i]=$num[$i]*$j;
				$j--;
			}
			$soma = array_sum($multiplica);	
			$resto = $soma%11;			
			if($resto<2) {
				$dg=0;
			} else {
				$dg=11-$resto;
			}
			if($dg!=$num[13]) {
				$isCnpjValid=false;
			} else {
				$isCnpjValid=true;
			}
    	}

    	return $isCnpjValid;
    }
   
    /**
     * Geral::validaCEP()
     * 
     * @param mixed $cep
     * @return
     */
    public function validaCEP($cep)
    {
        if(preg_match('/^\d{2}.\d{3}|\d{5}[-|\s]?[0-9]{3}$|^[0-9]{8}$/', $cep, $matches, PREG_OFFSET_CAPTURE)){

            $cepValido = (int) preg_replace('/[^0-9]/', '', $cep);
            return($cepValido > 0 ? true : false);
        }
    }

    /**
     * Geral::validaTelefone()
     * 
     * @param string $telefone
     * @return void
     * @throws RuntimeException Método ainda não implementado.
     */
    public function validaTelefone($telefone)
    {
        throw new RuntimeException("Metodo ainda nao implementado.");
    }

}