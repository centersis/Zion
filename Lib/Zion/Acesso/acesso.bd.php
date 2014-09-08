<?php
/**
*	@copyright DEMP - Soluï¿½ï¿½es em Tecnologia da Informaï¿½ï¿½o Ltda
*	@author Pablo Vanni - pablovanni@gmail.com
*	@since 28/05/2005
*	<br>ï¿½ltima Atualizaï¿½ï¿½o: 28/05/2007<br>
*	Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br>
*	@name Cï¿½digo SQL de Acesso
* 	@version 2.0
*  	@package Framework
*/

class AcessoBD
{
    /**
    *	Atributos da Classe
    */
    private $Con;


    /**
    *	Metodo Construtor
    *	@return VOID
    */
    public function AcessoBD()
    {
        $this->Con = Conexao::conectar();
    }

    /**
    *	Retorna a descriçãoo do módulo indicado pelo parametro
    *	@return String
    */
    public function dadosModulo($Modulo, $Posicao = 0)
    {
        $Sql = "SELECT ModuloDesc, ModuloCod FROM _modulos WHERE ModuloNome='$Modulo'";

        return $this->Con->execRLinha($Sql, $Posicao);
    }

    /**
    *	Verifica se o usuário tem permissão para acessar o módulo
    *	@return Booleano
    */
    public function permissao($Opcao, $UsuarioCod, $Modulo)
    {
        $Sql = "SELECT 	d.TipoPermissaoCod
                FROM 	_opcoes_modulo a, _modulos b,
                        _usuarios c, _tipo_permissao d
                WHERE 	b.ModuloNome = '$Modulo' AND
                        a.IdPermissao = '$Opcao' AND
                        d.Permissao = 'S' AND
                        b.ModuloCod = a.ModuloCod AND
                        a.OpcoesModuloCod = d.OpcoesModuloCod AND
                        c.UsuarioCod = d.UsuarioCod AND
                        c.UsuarioCod = $UsuarioCod";
        
        return($this->Con->execNLinhas($Sql) > 0) ? true : false;
    }

    public function permissaoMinima($UsuarioCod, $Modulo)
    {
        $Sql = "SELECT 	b.ModuloCod
                FROM 	_opcoes_modulo a INNER JOIN _modulos b ON (b.Modulocod  = a.ModuloCod)
                        INNER JOIN _tipo_permissao c ON (a.OpcoesModuloCod = c.OpcoesModuloCod)
                WHERE 	b.Modulonome = '".$Modulo."' AND
                        c.UsuarioCod = ".$UsuarioCod;

         return($this->Con->execNLinhas($Sql) > 0) ? true : false;
    }

    /**
    *	Retorna as opções que o usuário tem direito
    *	@return Array()
    */
    public function opPermissao($UsuarioCod, $Modulo)
    {
        $Sql = "SELECT  a.IdPermissao, d.Permissao
                FROM    _opcoes_modulo a, _modulos b,
                        _usuarios c, _tipo_permissao d
                WHERE   b.Modulonome = '".$Modulo."' AND
                        b.Modulocod  = a.ModuloCod AND
                        a.OpcoesModuloCod = d.OpcoesModuloCod AND
                        c.UsuarioCod = d.UsuarioCod AND
                        c.UsuarioCod = ".$UsuarioCod;

        $RS = $this->Con->executar($Sql);

        while ($D = @mysqli_fetch_array($RS))
        {
            $Retorno[$D['IdPermissao']] = $D['Permissao'];
        }

        return $Retorno;
    }

    public function retornaAcao($Modulo, $Acao)
    {
            $ModuloCod = $this->dadosModulo($Modulo,1);

            $Sql = "SELECT NomePermissao
                    FROM   _opcoes_modulo
                    WHERE  ModuloCod    = $ModuloCod AND
                           IdPermissao  = '".$Acao."'";
            return $this->Con->execRLinha($Sql);
    }

    public function acessoBotoes($Modulo, $UsuarioCod)
    {
        $Sql = "SELECT 	a.NomePermissao, a.IdPermissao,
                        d.Permissao, a.ImagemOn, a.ImagemOff,
                        a.AltP, a.AltNP, a.PrecisaId, a.Funcao, a.Posicao 
                FROM 	_opcoes_modulo a, _modulos b,
                        _usuarios c, _tipo_permissao d
                WHERE 	b.Modulonome = '".$Modulo."' AND
                        b.Modulocod  = a.ModuloCod AND
                        a.OpcoesModuloCod = d.OpcoesModuloCod AND
                        c.UsuarioCod = d.UsuarioCod AND
                        c.UsuarioCod = $UsuarioCod ORDER BY a.Posicao ASC";
        return $this->Con->executar($Sql);
    }

    public function precisaId($Modulo, $Opcao, $UsuarioCod)
    {
        $Sql = "SELECT a.PrecisaId
                FROM   _opcoes_modulo a, _modulos b,
                       _usuarios c, _tipo_permissao d
                WHERE  b.ModuloNome = '$Modulo' AND
                       a.IdPermissao = '$Opcao' AND
                       d.Permissao = 'S' AND
                       b.ModuloCod = a.ModuloCod AND
                       a.OpcoesModuloCod = d.OpcoesModuloCod AND
                       c.UsuarioCod = d.UsuarioCod AND
                       c.UsuarioCod = ".$UsuarioCod;
        
        return ($this->Con->execRLinha($Sql) == 'S') ? true : false;
    }

    public function existeHelp()
    {
        $Sql = "SELECT Help FROM _modulos WHERE  ModuloNome = '".MODULO."'";

        $Help = $this->Con->execRLinha($Sql);

        return (empty($Help)) ? false : true;
    }
}