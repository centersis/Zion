<?php 
/**
*	@copyright CenterSis
*	@author Pablo Vanni - pablovanni@gmail.com
*	@since 28/05/2007
*	<br>Última Atualização: 28/05/2007<br>
 *   <br>Última Atualização: 22/05/2012<br>
*	Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br>
*	@name Configurações de Acesso ao banco de dados
* 	@version 2.0
*  	@package Framework
*/

class ConexaoConf 
{ 
    protected $User  = array();
    protected $Senha = array();
    protected $Banco = array();
    protected $Host  = array();
    
    protected function __construct()
    {                
        $this->setUser('supremom_01',0);
        $this->setSenha(']$66A8[w0tmb',0);
        $this->setBanco('supremom_principal',0);
        $this->setHost('localhost',0);
        
        $this->setUser('goemtorg_01',1);
        $this->setSenha('EFL-631H4TX0',1);
        $this->setBanco('goemtorg_principal',1);
        $this->setHost('goemt.com.br',1);
    }

    public function setUser($Valor, $Conexao = 0)
    {
        $this->User[$Conexao] = $Valor;
    }

    public function getUser($Conexao = 0)
    {
        return $this->User[$Conexao];
    }

    public function setSenha($Valor, $Conexao = 0)
    {
        $this->Senha[$Conexao] = $Valor;
    }

    public function getSenha($Conexao = 0)
    {
        return $this->Senha[$Conexao];
    }

    public function setBanco($Valor, $Conexao = 0)
    {
        $this->Banco[$Conexao] = $Valor;
    }

    public function getBanco($Conexao = 0)
    {
        return $this->Banco[$Conexao];
    }

    public function setHost($Valor, $Conexao = 0)
    {
        $this->Host[$Conexao] = $Valor;
    }

    public function getHost($Conexao = 0)
    {
        return $this->Host[$Conexao];
    }	
}