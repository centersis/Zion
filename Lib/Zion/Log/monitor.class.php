<?php
/*

    Sappiens Framework
    Copyright (C) 2014, BRA Consultoria

    Website do autor: www.braconsultoria.com.br/sappiens
    Email do autor: sappiens@braconsultoria.com.br

    Website do projeto, equipe e documentação: www.sappiens.com.br
   
    Este programa é software livre; você pode redistribuí-lo e/ou
    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
    publicada pela Free Software Foundation, versão 2.

    Este programa é distribuído na expectativa de ser útil, mas SEM
    QUALQUER GARANTIA; sem mesmo a garantia implícita de
    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
    detalhes.
 
    Você deve ter recebido uma cópia da Licença Pública Geral GNU
    junto com este programa; se não, escreva para a Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
    02111-1307, USA.

    Cópias da licença disponíveis em /Sappiens/_doc/licenca

*/

class Monitor
{
    private $TempoInicial, $TempoFinal;

    private $MonitorCod;

    public function inicioMonitoramento()
    {
        //Inicia Conexão
        $Con = Conexao::conectar();

        //Definindo Código do Módulo
        $ModuloCod = $Con->execRLinha("SELECT ModuloCod FROM _modulos WHERE ModuloNome  = '".MODULO."'");        

        //Ação
        $MonitorAcao = $_GET['Op'];

        //Env
        $MonitorEnv = $_GET['Env'] ? 'true' : 'false';

        //SisReg
        if(is_array($_REQUEST['SisReg']))
        $MonitorRegistroCod = var_export(array_keys($_REQUEST['SisReg']), true);

        $Sql = "INSERT INTO _monitor (UsuarioCod, ModuloCod, MonitorAcao, MonitorTempo, MonitorData, MonitorRegistroCod, MonitorEnv)
                VALUES (".$_SESSION['UsuarioCod'].", ".$ModuloCod.", '".$MonitorAcao."', NULL, now(), '".$MonitorRegistroCod."', '".$MonitorEnv."')";

        $Con->executar($Sql);
        
        //Código Gerado
        $this->MonitorCod = $Con->ultimoInsertId();

        list($Usec, $Sec) = explode(" ",microtime());

        $this->TempoInicial = ((float)$Usec + (float)$Sec);
    }

    public function fimMonitoramento()
    {
        list($Usec, $Sec) = explode(" ",microtime());

        $this->TempoFinal = ((float)$Usec + (float)$Sec);

        $this->salvaMonitoramento();
    }

    public function salvaMonitoramento()
    {
        //Inicia Conexão
        $Con = Conexao::conectar();        

        //Tempo de Monitoramento
        $MonitorTempo = substr(($this->TempoFinal - $this->TempoInicial),0,6);

        $Sql = "UPDATE _monitor SET MonitorTempo = '".$MonitorTempo."' WHERE MonitorCod = ".$this->MonitorCod;

        $Con->executar($Sql);
    }
}