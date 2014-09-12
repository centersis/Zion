<?php

/**
 * @copyright CenterSis
 * @author Pablo Vanni - pablovanni@gmail.com
 * @since 28/05/2007
 * Última Atualização: 28/05/2007
 * Última Atualização: 22/05/2012
 * Autualizada Por: Pablo Vanni - pablovanni@gmail.com
 * @name Configurações de Acesso ao banco de dados
 * @version 3.0
 * @package Framework
 */

namespace Zion\Banco;

class ConexaoConf
{

    private $user = array();
    private $senha = array();
    private $banco = array();
    private $host = array();

    public function __construct()
    {

        $this->setUser('desetemb_01', 'PADRAO');
        $this->setSenha('!~2dIm[pO*Hh', 'PADRAO');
        $this->setBanco('desetemb_secundario', 'PADRAO');
        $this->setHost('localhost', 'PADRAO');

        $this->setUser('goemtorg_01', 'GOE');
        $this->setSenha('EFL-631H4TX0', 'GOE');
        $this->setBanco('goemtorg_principal', 'GOE');
        $this->setHost('goemt.com.br', 'GOE');
    }

    public function setUser($Valor, $Conexao = 'PADRAO')
    {
        $this->user[$Conexao] = $Valor;
    }

    public function getUser($Conexao = 'PADRAO')
    {
        return $this->user[$Conexao];
    }

    public function setSenha($Valor, $Conexao = 'PADRAO')
    {
        $this->senha[$Conexao] = $Valor;
    }

    public function getSenha($Conexao = 'PADRAO')
    {
        return $this->senha[$Conexao];
    }

    public function setBanco($Valor, $Conexao = 'PADRAO')
    {
        $this->banco[$Conexao] = $Valor;
    }

    public function getBanco($Conexao = 'PADRAO')
    {
        return $this->banco[$Conexao];
    }

    public function setHost($Valor, $Conexao = 'PADRAO')
    {
        $this->host[$Conexao] = $Valor;
    }

    public function getHost($Conexao = 'PADRAO')
    {
        return $this->host[$Conexao];
    }

}
