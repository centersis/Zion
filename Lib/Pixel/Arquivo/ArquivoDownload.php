<?php

session_start();

include_once($_SESSION['DirBase'] . 'framework/config.conf.php');
include_once($_SESSION['FMBase'] . 'conexao.class.php');

ConfigSIS::Conf();

$Con = Conexao::conectar();

//Recuperando Valores
$Caminho = urldecode($_GET['Caminho']);
$ArquivoCod = $_GET['ArquivoCod'];
$Tabela = htmlspecialchars($_GET['TB'], ENT_QUOTES);
$Modo = $_GET['Modo'];

//Valindando
if (empty($Caminho))
    exit("Caminho Inválido!");

if (!is_numeric($ArquivoCod))
    exit("Código Inválido!");

if (empty($Tabela))
    exit("Tabela Inválida!");

if ($Modo != 'Download' and $Modo != 'Ver')
    exit("Modo Inválido!");

//Buscando mais informações
$SqlArquivo = "SELECT Nome, Hash, Extensao FROM " . $Tabela . " WHERE ArquivoCod = " . $ArquivoCod;

try {
    $DadosArquivo = $Con->execLinha($SqlArquivo);
} catch (Exception $E) {
    exit($E->getMessage());
}

$Arquivo = $Caminho . $DadosArquivo['Hash'];

if (!file_exists($Arquivo))
    die("Arquivo não Existe!");

$ExtensaoAtual = $DadosArquivo['Extensao'] == 'jpg' ? 'jpeg' : $DadosArquivo['Extensao'];

if ($Modo == 'Ver') { //Visualizar icone do arquivo, se for imagem mostrar ela mesma
    //Extenssões que é possivel ele ver
    $ExtensoesVer = array('jpeg', 'gif', 'png');

    if (in_array($ExtensaoAtual, $ExtensoesVer)) {
        header('Content-Type: image/' . $ExtensaoAtual);
        @readfile($Arquivo);
    } else {
        //Caminho para Uma Imagem Padrão
        header('Content-Type: image/gif');
        readfile($_SESSION['DirBase'] . 'figuras/icone0.gif');
    }
} else { //Download do Arquivo
    //Atualiza Numero de Dowloads
    try {
        $Con->executar("UPDATE " . $Tabela . " SET Downloads = (Downloads +1) WHERE ArquivoCod = $ArquivoCod");
    } catch (Exception $E) {
        
    }

    set_time_limit(0);

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    //header("Content-Type:".mime_content_type($Arquivo)."");
    header("Content-Disposition: attachment; filename=\"" . $DadosArquivo['Nome'] . "\";");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . @filesize($Arquivo));

    @readfile("$Arquivo") or die("Arquivo não encontrado.");
}
?>