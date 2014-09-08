<?
class Parametros
{
   public static $Parametros = array();	

   public static function limpaParametros()
   {
       self::$Parametros = array();
   }

   public static function setParametros($Metodo, $Valores)
   {
       $Metodo  = strtoupper($Metodo);

       switch ($Metodo)
       {
           case "GET": 
           foreach ($Valores as $Valor)
           {
               if($_GET[$Valor] != '')
               {
                   self::$Parametros[$Valor] = $_GET[$Valor];
               }
           }
           break;

           case "POST": 
           foreach ($Valores as $Valor)
           {
               if($_POST[$Valor] != '')
               {
                   self::$Parametros[$Valor] = $_POST[$Valor];
               }
           }	
           break;

           case "REQUEST": 
           foreach ($Valores as $Valor)
           {
               if($_REQUEST[$Valor] != '')
               {
                   self::$Parametros[$Valor] = $_REQUEST[$Valor];
               }
           }

           break;

           case "FULL": 
           foreach ($Valores as $Valor=>$Conteudo)
           {
               self::$Parametros[$Valor] = $Conteudo;
           }

           break;	
       }
   }

   public static function getQueryString()
   {
       if(is_array(self::$Parametros) and !empty(self::$Parametros))
       {                        
           foreach (self::$Parametros as $Campo=>$Valor)
           {
               $Query .= "&".$Campo."=".urlencode($Valor);
           }

           return substr($Query,1);
       }
       else 
       {
           return "";
       }
   }

   public static function addQueryString($QueryAtual, $CampoValor)
   {
       if(!empty($QueryAtual))
       {
           $VCampos = explode("&",$QueryAtual);

           if(is_array($VCampos))
           {
               foreach ($VCampos as $CampoJunto)
               {
                   $VetJunto = explode("=",$CampoJunto);
                   $ArrayRetorno[$VetJunto[0]] = $VetJunto[1];
               }

               foreach ($CampoValor as $Campo=>$Valor) $ArrayRetorno[$Campo] = $Valor;

               foreach ($ArrayRetorno as $Campo=>$Valor) $NovaQuery.="&".$Campo."=".$Valor;

               return substr($NovaQuery,1);
           }
       }
       else
       {
           foreach ($CampoValor as $Campo=>$Valor) $NovaQuery.= "&".$Campo."=".$Valor;

           return substr($NovaQuery,1);
       }
   }      
}