<?php

/**
 *
 *    Sappiens Framework
 *    Copyright (C) 2014, BRA Consultoria
 *
 *    Website do autor: www.braconsultoria.com.br/sappiens
 *    Email do autor: sappiens@braconsultoria.com.br
 *
 *    Website do projeto, equipe e documentação: www.sappiens.com.br
 *   
 *    Este programa é software livre; você pode redistribuí-lo e/ou
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *    publicada pela Free Software Foundation, versão 2.
 *
 *    Este programa é distribuído na expectativa de ser útil, mas SEM
 *    QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *    detalhes.
 * 
 *    Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *    junto com este programa; se não, escreva para a Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *    02111-1307, USA.
 *
 *    Cópias da licença disponíveis em /Sappiens/_doc/licenca
 *
 */

namespace Pixel\Arquivo;

class ArquivoDownload
{

    public function getImagem($modulo, $nomeCampo, $uploadCodReferencia)
    {
        $con = \Zion\Banco\Conexao::conectar();

        $upload = new ArquivoUpload();

        $moduloCod = $upload->getModuloCod($modulo);

        $qbSelect = $con->qb();

        $qbSelect->select('uploadCod', 'uploadCodReferencia', 'uploadNomeOriginal', 'uploadDataCadastro')
                ->from('_upload')
                ->where($qbSelect->expr()->eq('uploadCodReferencia', ':uploadCodReferencia'))
                ->andWhere($qbSelect->expr()->eq('moduloCod', ':moduloCod'))
                ->andWhere($qbSelect->expr()->eq('uploadNomeCampo', ':uploadNomeCampo'))
                ->setParameters([
                    'uploadCodReferencia' => $uploadCodReferencia,
                    'moduloCod' => $moduloCod,
                    'uploadNomeCampo' => $nomeCampo]);

        $rS = $con->executar($qbSelect);
        $nR = $con->nLinhas($rS);
    }

}

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
        header('Content-Type: image/' . $ExtensaoAtual);
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
    header("Content-Type:" . mime_content_type($Arquivo) . "");
    header("Content-Disposition: attachment; filename=\"" . $DadosArquivo['Nome'] . "\";");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . @filesize($Arquivo));

    @readfile("$Arquivo") or die("Arquivo não encontrado.");
}