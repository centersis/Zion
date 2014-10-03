<?
/**
*	@copyright DEMP - Soluções em Tecnologia da Informação Ltda
*	@author Pablo Vanni - pablovanni@gmail.com
*	@since 23/02/2005
*	<br>Última Atualização: 24/03/2005<br>
*	Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br>
*	@name  Paginação de resultado para uma consulta no banco de dados
* 	@version 2.0
* 	@package Framework
*/

//Starta Sessão
session_start();

// HTTP/1.1 - Elimina Cache
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Content-Type: text/html; charset=ISO-8859-1",true);

//Busca Dados de Configuração
//include_once('config.conf.php'); ConfigSIS::Conf();

include_once($_SESSION['FMBase'].'conexao.class.php');

class Complete
{

        public function resultados()
    {
        $Tabela   = @utf8_decode($_POST['Tabela']);
        $Campo    = @utf8_decode($_POST['Campo']);
        $Limite   = (empty($_POST['Limite'])) ? 10 : $_POST['Limite'];
        $q        = @utf8_decode($_POST['q']);
        $Condicao = @utf8_decode($_POST['Condicao']);
        $Cod        =      $_POST['CampoCod'];
        $SelectCod = (!empty ($Cod))? ", ($Cod) AS Cod":"";
        //Converte Condicao
        if(!empty($Condicao))
        {
            $Condicao = str_replace(":","'",$Condicao);
        }

        try
        {
            $Con = Conexao::conectar();

            $Sql = "SELECT $Campo AS Campo $SelectCod FROM $Tabela WHERE $Campo LIKE '%".$q."%' $Condicao LIMIT $Limite ";

            $RS  = $Con->executar($Sql);

            while($Dados = @mysqli_fetch_array($RS))
            {
                echo $Dados['Campo']."|".$Dados['Cod']."\n";
            }
        }
        catch(Exception $E)
        {
            echo "";
        }
    }
//	public function resultados()
//	{
//		$Tabela   = @utf8_decode($_POST['Tabela']);
//		$Campo    = @utf8_decode($_POST['Campo']);
//		$Limite   = (empty($_POST['Limite'])) ? 10 : $_POST['Limite'];
//		$q        = @utf8_decode($_POST['q']);
//		$Condicao = @utf8_decode($_POST['Condicao']);
//
//		//Converte Condicao
//		if(!empty($Condicao))
//		$Condicao = str_replace(":","'",$Condicao);
//
//		try
//		{
//			$Con = Conexao::conectar();
//
//			$Sql = "SELECT $Campo Campo FROM $Tabela WHERE $Campo LIKE '%".$q."%' $Condicao LIMIT $Limite ";
//
//			$RS  = $Con->executar($Sql);
//
//			while($Dados = @mysqli_fetch_array($RS))
//			{
//				echo $Dados['Campo']."\n";
//			}
//		}
//		catch(Exception $E)
//		{
//			echo "";
//		}
//	}
}

//Executando Classe
$C = new Complete();
$C->resultados();
?>