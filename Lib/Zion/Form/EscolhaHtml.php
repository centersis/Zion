<?php

/**
 * \Zion\Form\EscolhaHtml()
 * 
 * @author The Sappiens Team
 * @copyright Sappiens 2014
 * @version 2014
 * @access public
 */
 
namespace Zion\Form;

class EscolhaHtml
{
    /**
     * EscolhaHtml::montaEscolha()
     * 
     * @param mixed $config
     * @return
     */
    public function montaEscolha(FormEscolha $config)
    {
        if (empty($config->getNome())) {
            throw new \Exception('Atributo nome é obrigatório');
        }

        //$inicio = $config->getInicio();
        $ordena = $config->getOrdena();

        $array = $config->getArray();

        $tabela = $config->getTabela();
        $campoCod = $config->getCampoCod();
        $campoDesc = $config->getCampoDesc();
        $where = $config->getWhere();
        $sqlCompleto = $config->getSqlCompleto();

        $multiplo = $config->getMiltiplo();
        $Expandido = $config->getExpandido();

        $valor = $config->getValor();

        //Define Tipo
        $select = false;
        $radio = false;
        $check = false;

        if ($Expandido === true and $multiplo === true) {
            $check = true;
        } else if ($Expandido === true and $multiplo === false) {
            $radio = true;
        } elseif ($Expandido === false and $multiplo === false) {
            $select = true;
        }

        $opcoes = '';
//        if ($select and $inicio != '') {
//             = ($inicio === true) ? '<option value="">Selecione...</option>' : '<option value="">' . $inicio . '</option>';
//        }

        //É Selecionado ?
        $eSelecionado = false;

        if ($tabela and $campoCod and $campoDesc) {
            $con = \Zion\Banco\Conexao::conectar($config->getIdConexao());

            if (!empty($sqlCompleto)) {
                $sql = $sqlCompleto;
            } else {
                $sqlWhere = $where ? 'WHERE ' . $where : '';
                $sql = 'SELECT ' . $campoCod . ', ' . $campoDesc . ' FROM ' . $tabela . ' ' . $sqlWhere;
            }

            $rs = $con->executar($sql);

            //$array = array();
            while ($linha = $rs->fetch_array()) {
                $array[$linha[$campoCod]] = $linha[$campoDesc];
            }
        }

        if (!is_bool($ordena)) {
            $ordena = strtoupper($ordena);
        }

        $ordenaArray = function($vetor) {

            $texto = \Zion\Validacao\Texto::instancia();
            
            $original = $vetor;

            foreach ($vetor as $posicao => $string) {
                $vetor[$posicao] = $texto->removerAcentos($string);
            }

            natcasesort($vetor);

            foreach ($vetor as $posicao => $string) {
                $vetor[$posicao] = $original[$posicao];
            }

            return $vetor;
        };

        if ($ordena !== false) {
            if ($ordena == "ASC" or $ordena == "") {
                $array = $ordenaArray($array);
            } elseif ($ordena == "DESC") {
                $array = array_reverse($ordenaArray($array));
            }
        }

        $name = 'name="' . $config->getNome() . '"';
        $id = ($config->getId() == '') ? 'id="' . $config->getNome() . '" ' : 'id="' . $config->getId() . '"';
        $complemento = $config->getComplemento();
        $disable = ($config->getDisabled() === true) ? 'disabled="disabled"' : '';

//        if ($select) {
//            $opcoes = ($inicio === true) ? '<option value="">Selecione...</option>' : '<option value="">' . $inicio . '</option>';
//        }

        $retorno = '';
        $cont = 0;
        foreach ($array as $chave => $vale) {

            $cont++;
            if ($select) { // Campo Select
                
                if($cont == 1){
                    $opcoes.= '<option></option>';
                }                    
                
                $opcoes .= '<option value="' . $chave . '" ';

                if ($eSelecionado === false) {
                    if ($valor == '') {
                        if ("{$config->getValorPadrao()}" === "$chave") {
                            $eSelecionado = true;
                            $opcoes .= 'selected';
                        }
                    } elseif ("$chave" === "$valor") {
                        $eSelecionado = true;
                        $opcoes .= 'selected';
                    }
                }

                $opcoes .= ' > ' . $vale . ' </option>';
            } else {
                if ($check) {
                    $checked = '';
                    $type = 'type="checkbox"';
                    $name = 'name="' . $config->getNome() . $chave . '"';
                    $id = 'id="' . $config->getNome() . $chave . '"';
                } else {
                    $type = 'type="radio"';
                    $id = 'id="' . $config->getNome() . $chave . '"';

                    $checked = '';
                    if ($eSelecionado === false) {
                        if ($valor == '') {
                            if ("{$config->getValorPadrao()}" === "$chave") {
                                $eSelecionado = true;
                                $checked = 'checked="checked"';
                            }
                        } elseif ("$chave" === "$valor") {
                            $eSelecionado = true;
                            $checked = 'checked="checked"';
                        }
                    }
                }

                $value = 'value="' . $chave . '"';

                $retorno .= sprintf("<label><input %s %s %s %s %s %s %s>%s</label>", $type, $name, $id, $value, $complemento, $disable, $checked, $vale);
            }
        }

        if ($select) {
            
            $html = new \Zion\Layout\Html();
        
            $retorno = '';

            if($config->getemColunaDeTamanho()){
                $retorno .= $html->abreTagAberta('section', array('class'=>'col col-'.$config->getemColunaDeTamanho()));                    
            }

            $retorno.= sprintf('<select %s %s %s %s style="width:100%s" class="select2" '.($config->getPlaceHolder() ? 'data-placeholder="'.$config->getPlaceHolder().'"' : 'data-placeholder="Selecione..."').'>%s</select>', $name, $id, $complemento, $disable, '%', $opcoes);
              
            if($config->getemColunaDeTamanho()){
                $retorno .= $html->fechaTag('section');
            }
        }

        return $retorno;
    }
    
    /**
     * EscolhaHtml::montaCheck()
     * 
     * @param mixed $config
     * @return
     */
    private function montaCheck(FormEscolha $config)
    {
        
    }
    
    /**
     * EscolhaHtml::montaRadio()
     * 
     * @param mixed $config
     * @return
     */
    private function montaRadio(FormEscolha $config)
    {
        
    }
    
    /**
     * EscolhaHtml::montaSelect()
     * 
     * @param mixed $config
     * @return
     */
    private function montaSelect(FormEscolha $config)
    {
        
    }
}
