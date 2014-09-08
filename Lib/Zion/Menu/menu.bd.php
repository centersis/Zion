<?
/**
 *	@copyright DEMP - Solu��es em Tecnologia da Informa��o Ltda
 *	@author Pablo Vanni - pablovanni@gmail.com
 *	@since 01/06/2006
 *	<br>�ltima Atualiza��o: 28/05/2007<br>
 *	Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br>
 *	@name Metodos de intera��o com a base de dados
 * 	@version 2.0
 *  	@package Framework
 */

//Classes Nescess�rias
abstract class MenuBD
{
/**
 *	Atributos da Classe
 */
    protected $Con;

    /**
     *	Metodo Construtor
     *	@return VOID
     */
    public function MenuBD()
    {
        $this->Con = Conexao::conectar();
    }

    /**
     *	Retorna os Grupos dispon�veis para determinado usu�rio
     *	Utilizado para gerar tamanho do menu
     *	@return Quantidade de Grupos
     */
    protected function gruposDiponiveisUsuario()
    {
        $Sql = "SELECT d.GrupoDesc
				  FROM _tipo_permissao a,
				  	   _opcoes_modulo b,
					   _modulos c,
					   _grupomodulo d
				WHERE a.OpcoesModuloCod = b.OpcoesModuloCod
				  AND b.ModuloCod = c.ModuloCod
				  AND c.GrupoCod = d.GrupoCod
				  AND a.UsuarioCod = ".$_SESSION['UsuarioCod']."
				  AND Permissao = 'S'
			 GROUP BY d.GrupoCod ";

        return $this->Con->execNLinhas($Sql);
    }



    /**
     *	Retorna os grupos disponiveis no sistema
     *	@return ResultSet
     */
    protected function gruposDiponiveis()
    {
        $Sql = "SELECT 	 GrupoCod, GrupoDesc, Pacote
				FROM	 _grupomodulo  
				ORDER BY Posicao ASC";

        return $this->Con->executar($Sql);
    }

    protected function gruposDiponiveisSql()
    {
        $Sql = "SELECT 	 GrupoCod, GrupoDesc, Pacote
				FROM	 _grupomodulo
				ORDER BY Posicao ASC";

        return $Sql;
    }

    public function modulosDiponiveisSql()
    {
        $Sql = "SELECT 	 ModuloCod, GrupoCod, ModuloReferente, ModuloNome, 
                         ModuloDesc, NomeMenu, ModuloBase, VisivelMenu  
				FROM	 _modulos
                WHERE   1 ORDER BY Posicao ASC";

        return $Sql;
    }

    public function usuarioPermissaoModuloSql($UsuarioCod)
    {
        $Sql = "SELECT DISTINCT(ModuloCod) as ModuloCod
                FROM   _opcoes_modulo a INNER JOIN _tipo_permissao b ON (a.OpcoesModuloCod = b.OpcoesModuloCod)
                WHERE  b.Permissao = 'S' AND b.UsuarioCod = $UsuarioCod";

        return $Sql;
    }

    public function dadosModulo($ModuloCod, $Visivel = true)
    {
        
        $Visibilidade = $Visivel == false ? "" : " AND VisivelMenu = 'S' ";

        $Sql = "SELECT a.ModuloCod, a.GrupoCod, a.ModuloNome,
                       a.ModuloDesc, a.NomeMenu, a.ModuloBase,
                       b.Pacote
                FROM   _modulos a, _grupomodulo b
                WHERE  a.GrupoCod = b.GrupoCod
                       $Visibilidade AND
                       a.ModuloCod = $ModuloCod";
        return $this->Con->execLinha($Sql);
    }

    /**
     *	Retorna os grupos referentes
     *	@return ResultSet
     */
    protected function modulosReferentes($Referencia, $Visivel = true)
    {
        $Visibilidade = $Visivel == false ? "" : " AND VisivelMenu = 'S' ";

        $Sql = "SELECT 	 ModuloCod, NomeMenu
				FROM	 _modulos 
				WHERE 	 ModuloReferente = ".$Referencia."  
                                         $Visibilidade
				ORDER BY Posicao ASC";
        
        return $this->Con->executar($Sql);
    }


    /**
     *	Retorna os m�dulos disponiveis no sistema para cada grupo
     * 	@param GrupoCod String - C�digo do Grupo
     * 	@param Mostrar String  - T -> Todos, V ->Visiveis no menu
     *	@return ResultSet
     */
    protected function modulosGrupoSemReferencia($GrupoCod, $Mostrar = "V")
    {
        $CondicaoMostrar = ($Mostrar == "V") ? " AND a.VisivelMenu = 'S' " : "";

        $Sql = "SELECT a.ModuloCod
				FROM   _modulos a, _grupomodulo b 
				WHERE  a.GrupoCod = b.GrupoCod 
            $CondicaoMostrar
					   AND a.grupocod= ".$GrupoCod."
					   AND a.ModuloReferente = 0 
				ORDER BY a.Posicao ASC";

        return $this->Con->executar($Sql);
    }

    public function existeSubModulo($ModuloCod, $Mostrar = "V")
    {
        $CondicaoMostrar = ($Mostrar == "V") ? " VisivelMenu = 'S' AND" : "";

        $Sql = "SELECT ModuloCod FROM _modulos WHERE $CondicaoMostrar ModuloReferente = $ModuloCod ";
        
        return ($this->Con->execNLinhas($Sql) > 0) ? true : false;
    }

    /**
     *	Retorna o SQL para o n�mero de permiss�es ativas para um grupo inteiro
     * 	@param GrupoCod String - C�digo do Grupo
     *	@return String
     */
    protected function sqlPermissaoGrupo($GrupoCod)
    {
        $Sql = "SELECT count(a.GrupoCod) Total
				FROM   _grupomodulo a, _modulos b, 
					   _opcoes_modulo c, _usuarios d,
					   _tipo_permissao e   
				WHERE  a.GrupoCod         = b.GrupoCod
					   AND b.ModuloCod    = c.ModuloCod  
					   AND e.UsuarioCod   = d.UsuarioCod 
					   AND c.OpcoesModuloCod = e.OpcoesModuloCod 
					   AND d.UsuarioCod   = ".$_SESSION['UsuarioCod']." 
					   AND a.GrupoCod     = ".$GrupoCod."
					   AND e.Permissao    = 'S'";

        return $Sql;
    }

    /**
     *	Retorna o n�mero de permiss�es ativas para um grupo inteiro
     * 	@param GrupoCod String - C�digo do Grupo
     *	@return Inteiro
     */
    protected function permissaoGrupo($GrupoCod)
    {
		/*
		//Verifica Grupos Referentes
		$SqlRef  = "SELECT GrupoCod FROM _grupomodulo WHERE GrupoCod =".$GrupoCod;
		$RSRef   = $this->Con->executar($SqlRef);
		$NRef    = $this->Con->nLinhas($RSRef);
		$ContRef = 0;
		
		//Numero de M�dulos com permiss�o por referencia
		if($NRef > 0)
		{
			while($DadosRef = @mysqli_fetch_array($RSRef))
			{
				$TotalRef = $this->Con->execRLinha($this->sqlPermissaoGrupo($DadosRef['GrupoCod']));
				$ContRef += $TotalRef;
			}
		}*/

        //Total de Resultados Diretos
        $TotalDiretos = $this->Con->execRLinha($this->sqlPermissaoGrupo($GrupoCod));

        $TotalGeral = $TotalDiretos;// + $ContRef;

        return ((int) $TotalGeral);
    }

    /**
     *	Retorna o n�mero Permiss�es ativas para um m�dulo especifico
     * 	@param GrupoCod String - C�digo do Grupo
     * 	@param ModuloCod String - C�digo do M�dulo
     *	@return Inteiro
     */
    protected function ocorrenciasModulo($GrupoCod, $ModuloCod)
    {
        //Verifica o numero de ocorrencias para este grupo
        $Sql = "SELECT count(a.GrupoCod) Total
				FROM   _grupomodulo a, _modulos b, 
					   _opcoes_modulo c, _usuarios d,
					   _tipo_permissao e   
				WHERE  a.GrupoCod         = b.GrupoCod
					   AND b.ModuloCod    = c.ModuloCod  
					   AND e.UsuarioCod   = d.UsuarioCod 
					   AND c.OpcoesModuloCod = e.OpcoesModuloCod
					   AND b.ModuloCod    = ".$ModuloCod." 
					   AND d.UsuarioCod   = ".$_SESSION['UsuarioCod']." 
					   AND a.GrupoCod     = ".$GrupoCod."
					   AND e.Permissao    = 'S'";

        $LinhaTotal = $this->Con->execRLinha($Sql);

        return $LinhaTotal['Total'];
    }


    /**
     * @abstract Retorna se existe algum submenu filho do modulo que o usuario tenha permissao
     * @author Yuri Gauer Marques
     */
    protected function checaPermissaoMenuPai($ModuloCod)
    {
        $Sql = "SELECT a.ModuloCod Total FROM _modulos a, _opcoes_modulo b, _tipo_permissao c
                WHERE a.ModuloReferente = ".$ModuloCod." AND
                a.ModuloCod = b.ModuloCod AND
                b.OpcoesModuloCod = c.OpcoesModuloCod AND
                c.UsuarioCod = ".$_SESSION['UsuarioCod']."  AND
                c.Permissao = 'S' LIMIT 1";

        $Ocorrencias = $this->Con->executar($Sql);

        $NumOcorrencias = $this->Con->nLinhas($Ocorrencias);

        return $NumOcorrencias;
    }
}
?>