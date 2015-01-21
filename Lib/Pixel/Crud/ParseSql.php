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

namespace Pixel\Crud;

class ParseSql
{

    //Funcao que troca todas as Aspas
    public function trocaAspas($var)
    {
        $string = "";
        $aspaSimples = false;
        $aspaDupla = false;
        $charPassado = "";

        for ($i = 0; $i < \strlen($var); $i++) {

            $charAtual = $var{$i};

            if ($i > 0) {
                $charPassado = $var{$i - 1};
            }

            $charDuplo = $charPassado . $charAtual;

            if ($charAtual == "'" and $charDuplo <> "\\'" and $aspaSimples == false and $aspaDupla <> true) {
                $aspaSimples = true;
            } else if ($charAtual == "'" and $charDuplo <> "\\'" and $aspaSimples == true and $aspaDupla <> true) {
                $aspaSimples = false;
                $charAtual = "'";
            }

            if ($charAtual == '"' and $charDuplo <> '\\"' and $aspaDupla == false and $aspaSimples <> true) {
                $aspaDupla = true;
            } else if ($charAtual == '"' and $charDuplo <> '\\"' and $aspaDupla == true and $aspaSimples <> true) {
                $aspaDupla = false;
                $charAtual = "'";
            }

            if ($aspaSimples) {
                if ($charAtual == '"' and $charDuplo == '\\"') {
                    $charAtual = '\"';
                }

                if ($charAtual == '"' and $charDuplo <> '\\"') {
                    $charAtual = '\"';
                }

                if ($charAtual == "'" and $charDuplo == "\\'") {
                    $charAtual = "'";
                }

                if ($charAtual == "'" and $charDuplo <> "\\'") {
                    $charAtual = "'";
                }
            }


            if ($aspaDupla) {
                if ($charAtual == "'" and $charDuplo == "\\'") {
                    $charAtual = "'";
                }

                if ($charAtual == "'" and $charDuplo <> "\\'") {
                    $charAtual = "\'";
                }

                if ($charAtual == '"' and $charDuplo <> '\\"') {
                    $charAtual = "'";
                }

                if ($charAtual == '"' and $charDuplo == '\\"') {
                    $charAtual = '"';
                }
            }

            $string .= $charAtual;
        }
        return $string;
    }

    //Funcao que Quebra o Sql em 3 Partes
    public function quebraInsertSql($sql)
    {
        $vetor = preg_split("/([^[:alnum:]_])/", trim($sql));

        $numero = 0;
        $posicaoI = 0;
        $posicaoV = 0;

        foreach ($vetor as $aux) {
            if (strcasecmp('into', $aux) == 0 and $posicaoI == 0) {
                $posicaoI = $numero;
            }
            if (strcasecmp('values', $aux) == 0 and $posicaoV == 0) {
                $posicaoV = $numero;
            }
            $numero++;
        }

        $array = array();
        $rSA = explode($vetor[$posicaoI], $sql);
        $array[0] = $rSA[0];

        $rSB = explode($vetor[$posicaoV], $rSA[1]);
        $array[1] = $rSB[0];
        $array[2] = $rSB[1];

        return $array;
    }

    //Funcao que retorna uma Array Com Valores
    public function valoresInsert($sql)
    {
        $parte = $this->quebraInsertSql($sql);
        $parteSql = $this->trocaAspas($parte[2]);
        $cont = 0;
        $vetor = array();
        $aspa = false;
        $semAspa = false;
        $charPassado = "";

        for ($i = 0; $i < strlen($parteSql); $i++) {
            
            $charAtual = $parteSql{$i};
            
            if ($i > 0){
                $charPassado = $parteSql{$i - 1};
            }
            
            $charDuplo = $charPassado . $charAtual;

            //Com Aspas
            if ($charAtual == "'" and $charDuplo <> "\\'" and $aspa == false) {
                $aspa = true;
                $charAtual = "";
            } else if ($charAtual == "'" and $charDuplo <> "\\'" and $aspa == true) {
                $aspa = false;
                $vetor[$cont] .= "";
                ++$cont;
            }


            //Sem Aspas
            if ($semAspa == false and $aspa == false) {
                $semAspa = true;
            } else if ($charAtual == "," and $semAspa == true and $aspa == false) {
                $semAspa = false;
                $cont++;
            }

            //Armazenamento no Vetor
            if ($aspa == true) {
                $vetor[$cont] .= $charAtual;
            } else if ($semAspa == true) {
                if ($charAtual != "(" and $charAtual != "," and $charAtual != ")" and $charAtual != "'") {
                    $vetor[$cont] .= $charAtual;
                }
            }
        }

        //Limpando o Vetor Para Retorna
        $array = array();

        foreach ($vetor as $limpando) {
            
            $limpando = trim($limpando);
            
            if (!empty($limpando)) {
                $array[] = $limpando;
            }
        }

        return $array;
    }

    //Funcao Que Controla Todas e Retorna um Array de 3 Posicoes
    public function parseInsert($sql)
    {
        $arrayInsert = array();
        $arraySuport = array();
        $arrayValores = $this->valoresInsert($sql);

        $parte = $this->quebraInsertSql($sql);
        $rs = preg_split("/([^[:alnum:]_])/", trim($parte[0]));
        $arrayInsert['operacao'] = $rs[0];

        $array = preg_split("/([^[:alnum:]_])/", trim($parte[1]));

        foreach ($array as $aux) {
            if (!empty($aux)) {
                if (count($arrayInsert) == 1) {
                    $arrayInsert['tabela'] = $aux;
                } else {
                    $arraySuport[] = $aux;
                }
            }
        }

        $variavelAux = "array(";
        for ($i = 0; $i < count($arraySuport); $i++) {
            if ($i == 0) {
                $variavelAux .= '"' . $arraySuport[$i] . '"=>"' . $arrayValores[$i] . '"';
            } else {
                $variavelAux .= ',"' . $arraySuport[$i] . '"=>"' . $arrayValores[$i] . '"';
            }
        }
        $arrayInsert['array'] = $variavelAux . ")";

        return $arrayInsert;
    }

    //Funcao que retorna apenas os atributos de um insert - Tambem Serve Para Replace
    public function getAtributosInsert($sql)
    {
        $arrayAtributos = array();

        $parte = $this->quebraInsertSql($sql);

        $array = preg_split("/([^[:alnum:]_])/", trim($parte[1]));

        $cont = 0;

        foreach ($array as $aux) {
            if (!empty($aux)) {
                $cont++;

                if ($cont != 1) {
                    $arrayAtributos[] = $aux;
                }
            }
        }

        return $arrayAtributos;
    }

    //Funcao que retorna apenas os atributos de um update
    public function getAtributosUpdate($sql)
    {
        $arrayAtributos = array();

        $partesSql = $this->quebraUpdateSql($sql);

        eval('$valores = ' . $this->valoresUpdate($partesSql[1]) . ';');

        //Monta Vetor de Campos
        foreach ($valores as $chave => $valor) {
            $valor = trim($valor);

            if ($valor == "%s"){
                $arrayAtributos[] = $chave;
            }
        }

        $arrayAtributos[] = "cod";

        return $arrayAtributos;
    }

    /**
     * 	Funcoes para o Parse UPDATE
     */
    public function quebraUpdateSql($sql)
    {
        $vetor = preg_split("/([^[:alnum:]_])/", trim($sql));

        $numero = 0;
        $posicaoS = 0;
        $posicaoW = 0;
        
        foreach ($vetor as $aux) {
            if (strcasecmp('set', $aux) == 0 and $posicaoS == 0) {
                $posicaoS = $numero;
            }
            if (strcasecmp('where', $aux) == 0) {
                $posicaoW = $numero;
            }
            $numero++;
        }
        
        $array = array();
        $rSA = explode($vetor[$posicaoS], $sql);
        $array[0] = trim($rSA[0]);
        
        $rSB = explode($vetor[$posicaoW], $rSA[1]);
        $array[1] = trim($rSB[0]);
        $array[2] = trim($rSB[1]);

        return $array;
    }

    //Funcao Que Controla Todas e Retorna um Array de 4 Posicoes
    public function parseUpdate($sql)
    {
        $arrayUpdate = [];

        $parte = $this->quebraUpdateSql($sql);
        $rs = \preg_split("/([^[:alnum:]_])/", \trim($parte[0]));
        $arrayUpdate['operacao'] = $rs[0];
        $arrayUpdate['tabela'] = $rs[1];
        $arrayUpdate['array'] = $this->valoresUpdate($parte[1]);
        $arrayUpdate['comparacao'] = trim($parte[2]);


        return $arrayUpdate;
    }

    public function valoresUpdate($var)
    {
        $parteSql = $this->trocaAspas($var);
        $finalString = (strlen($parteSql) - 1);
        $string = "array(";
        $aspa = false;
        $semAspa = false;

        for ($i = 0; $i < strlen($parteSql); $i++) {
            $charAtual = $parteSql{$i};
            $charPassado = $parteSql{$i - 1};
            $charDuplo = $charPassado . $charAtual;

            //Com Aspas
            if ($charAtual == "'" and $charDuplo <> "\\'" and $aspa == false) {
                $aspa = true;
                $charAtual = "";
            } else if ($charAtual == "'" and $charDuplo <> "\\'" and $aspa == true) {
                $aspa = false;
                $string .= "";
            }

            //Sem Aspas
            if ($semAspa == false and $aspa == false) {
                $semAspa = true;
                $string .= '"';
            } else if ($semAspa == true and $aspa == true) {
                $semAspa = false;
            }

            //Armazenamento em Uma String
            if ($aspa) {
                $string .= $charAtual;
            } elseif ($semAspa and $charAtual <> "'") {
                if ($charAtual == "=") {
                    $string .= '"=>"';
                } else if ($charAtual == ",") {
                    $string .= '","';
                } else if ($charAtual == " ") {
                    $string .= '';
                } else if ($i == $finalString) {
                    $string .= $charAtual . '"';
                } else {
                    $string .= $charAtual;
                }
            }
        }
        $string .= ")";
        
        return str_replace('""', '"', $string);
    }

    /**
     * 	Funcoes para o Parse DELETE
     */
    public function parseDelete($sql)
    {
        $vetor = \preg_split("/([^[:alnum:]_])/", \trim($sql));

        $arrayUpdate = array();
        $numero = 0;
        $posicaoF = 0;
        $posicaoW = 0;
        
        foreach ($vetor as $aux) {
            if (strcasecmp('from', $aux) == 0 and $posicaoF == 0) {
                $posicaoF = $numero;
            }
            if (strcasecmp('where', $aux) == 0 and $posicaoW == 0) {
                $posicaoW = $numero;
            }
            $numero++;
        }
        
        $rSA = explode($vetor[$posicaoF], $sql);
        $arrayUpdate['operacao'] = trim($rSA[0]);
        
        $rSB = explode($vetor[$posicaoW], $rSA[1]);
        $arrayUpdate['tabela'] = $this->primeiraTabela(\trim($rSB[0]));
        $arrayUpdate['comparacao'] = trim($rSB[1]);

        return $arrayUpdate;
    }

    public function primeiraTabela($parte)
    {
        $vetor = explode(" ", $parte);
        return $vetor[0];
    }

}
