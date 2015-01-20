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

/**
 * 
 * @author Feliphe "O Retaliador" Bueno - feliphezion@gmail.com
 * @since 03/11/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Exportação de dados do sistema, para grids e relatórios.
 * 
 */

namespace Zion\Exportacao;

class Exportacao extends ExportacaoVO 
{

    private $con;
    
    private $PDF;
    private $CSV;
    private $XLS;
    private $Email;
  
    public function __construct()
    {
        if(!is_object($this->con)){
            $this->con = \Zion\Banco\Conexao::conectar();
        }
    }
    
    public function getRelatorio($tipo = "PDF")
    {
        switch($tipo){
            case 'PDF':
                return \Zion\Exportacao\PDF::getPDF();
            break;
            case 'CSV':
                return \Zion\Exportacao\CSV::getCSV();
            break;
            case 'XLS':
                return \Zion\Exportacao\XLS::getXLS();
            break;
            case 'Email':
            break;
        }
    }

    public function setDadosRelatorio($grupo, $modulo, $colunas, $objForm)
    {
        $moduloSqlPath = '\\'. SIS_ID_NAMESPACE_PROJETO . '\\'. $grupo .'\\'. $modulo .'\\'. $modulo .'Sql';

        $moduloSql = new $moduloSqlPath;

        //Configurações Fixas da Grid
        parent::setSql($moduloSql::filtrarSql($objForm, $colunas));
        parent::setColunas($colunas);
        parent::setTipoOrdenacao(filter_input(INPUT_GET, 'to'));
        parent::setQuemOrdena(filter_input(INPUT_GET, 'qo'));
        parent::setDadosRelatorio($this->getDadosRelatorio());
        
        $tiuloRelatorio = 'Relatório dos registros em '. $grupo. ' >> '. $modulo;
        $descricaoRelatorio = 'Relatório gerado em '. date('d/m/Y') .' às '. date('H:i:s');

        $this->setEstiloRelatorio($tiuloRelatorio, $descricaoRelatorio);
    }
    
    public function getDadosRelatorio()
    {

        //Recupera Valores
        $sql            = parent::getSql();
        $listados       = array_keys(parent::getColunas());
        $formatarComo   = parent::getFormatarComo();

        //Verifica se o SQL não esta Vazio
        if (empty($sql)) {
            throw new \Exception("Valor selecionado inválido!");
        }

        //Se Formatações existem, intancie funções de Validação
        if (!empty($formatarComo)) {
            $fPHP = new \Zion\Validacao\Valida();
        }

        //Iniciando valores
        $i = 0;

        $html = "";

        $rs = $this->con->executar($sql);

        $nLinhas = $this->con->nLinhas($rs);
        
        $DadosRelatorio = array();

        //Contruindo grid
        if ($nLinhas > 0) {

            //Objeto de Converssão (Objeto Pastor :D)
            $objC = parent::getObjetoConverte();

            //Estilos de um resultado (ESTILO DE RESULTADO ÚNICO)
            $eRU = parent::getCondicaoResultadoUnico();
            $eTR = parent::getCondicaoTodosResultados();

            while ($linha = $rs->fetch()) {
                $i += 1;

                $cRT = "";
                if (is_array($eTR)) {
                    foreach ($eTR as $rT) {
                        if ($this->resultadoEval($linha, array($rT[0])) === true) {
                            $cRT = $rT[1];
                        }
                    }
                }

                foreach ($listados as $value) {
                    if (is_array($objC)) {
                        //Valor com possivel converssão
                        if (in_array($value, $objC)) {
                            $valorItem = $this->converteValor($linha, $objC[$value]);
                        } else {
                            $valorItem = $linha[$value];
                        }

                    } else {
                        $valorItem = $linha[$value];
                    }

                    //Estilo de Resultado Único
                    $cRU = "";
                    if (is_array($eRU)) {
                        if(in_array($value, $eRU)){
                            if ($this->resultadoEval($linha, $eRU[$value]) === true) {
                                $cRU = $eRU[$value][1];
                            }
                        }
                    }

                    //Formatação
                    if (!empty($formatarComo)) {
                        if(in_array($value, $formatarComo)){
                            $como = strtoupper($formatarComo[$value]);

                            switch ($como) {
                                case "DATA" : $valorItem = $fPHP->data()->converteData($valorItem);
                                    break;
                                case "DATAHORA": $valorItem = $fPHP->data()->converteDataHora($valorItem);
                                    break;
                                case "NUMERO" : $valorItem = $fPHP->numero()->floatCliente($valorItem);
                                    break;
                                case "MOEDA" : $valorItem = $fPHP->numero()->moedaCliente($valorItem);
                                    break;
                            }
                        }
                    }

                    $DadosRelatorio[$i][$value] = $valorItem;
                }                
            }
            
        } else {
                      
            array_push($DadosRelatorio, "Nenhum resultado encontrado...");

        }
        
        return $DadosRelatorio;

    }

    private function resultadoEval($linha, $evalCod)
    {
        eval($evalCod[0]);

        return $r;
    }
    
    private function setEstiloRelatorio($tiuloRelatorio, $descricaoRelatorio){

        $stylesheet = '
            h2{
				font-family: Arial, Helvetica, sans-serif; font-size: 14px;	color: #000; text-decoration: none; text-align:left;
			}
			pLinha{
				margin:0; padding:0;line-height:0;
			}
			#relatorio tbody tr {
				margin-top:15px;
			}
			td{
				text-align:center;
				height:20px;
			}
			tbody{
				margin-top:20px;
				border:1px solid #666666;
				border-bottom: none;
			}
			table{
				margin-bottom:20px;
			}

            .t12preto {
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 12px;
                color: #000000;
                text-decoration: none;
            }

            .mTextoRelatorio {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 10px;
                color: #000000;
            }
            
            .header{
                background-color: #666666;
            }
            .linhaConteudo {
                font-size: 10px;
                font-weight: bold;
                color: #FFFFFF;
                border-left:silver 1px;
            }
            .separador {
                color: #666666;
            }
            .logo{
            }
            .tituloRelatorio{
            }
            .descricaoRelatorio{
            }
            .cell{
            }
       ';

        parent::setMainTableStyle("background-color:#fff;font:10px Arial, Helvetica, sans-serif;border-collapse: collapse;");
        parent::setColsWidth(['30%', '9%', '60%']);
        parent::setColsHeight(25);
        parent::setUrlLogo('http:'. SIS_URL_BASE .'arquivos/logo.jpg');
        parent::setLogoAlignment('left');
        parent::setStylesheet($stylesheet);
        parent::setTituloRelatorio($tiuloRelatorio);
        parent::setDescricaoRelatorio($descricaoRelatorio);
        parent::setOrientacaoRelatorio('P');

        return $this;
    }
    
}