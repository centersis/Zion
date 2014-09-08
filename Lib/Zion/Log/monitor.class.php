<?php
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