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

    private $html;

    public function __construct()
    {
        $this->html = new \Zion\Layout\Html();
    }

    /**
     * EscolhaHtml::montaEscolha()
     * 
     * @param mixed $config
     * @return
     */
    public function montaEscolha(FormEscolha $config, $retornarArray = false)
    {
        $tipoApresentacao = $this->getTipoApresentacao($config);

        $array = $this->dadosCampo($config);

        switch ($tipoApresentacao) {
            case 'select': return $this->montaSelect($config, $array);
            case 'check' : return $this->montaCheckRadio($tipoApresentacao, $config, $array, $retornarArray);
            case 'radio' : return $this->montaCheckRadio($tipoApresentacao, $config, $array, $retornarArray);
        }
    }

    private function dadosCampo($config)
    {
        $ordena = $config->getOrdena();

        $array = $config->getArray();

        $tabela = $config->getTabela();
        $campoCod = $config->getCampoCod();
        $campoDesc = $config->getCampoDesc();
        $where = $config->getWhere();
        $sqlCompleto = $config->getSqlCompleto();

        if ($tabela and $campoCod and $campoDesc) {

            $con = \Zion\Banco\Conexao::conectar($config->getIdConexao());

            if (!empty($sqlCompleto)) {
                $sql = $sqlCompleto;
            } else {
                $sqlWhere = $where ? 'WHERE ' . $where : '';
                $sql = 'SELECT ' . $campoCod . ', ' . $campoDesc . ' FROM ' . $tabela . ' ' . $sqlWhere;
            }

            $rs = $con->executar($sql);

            while ($linha = $rs->fetch_array()) {
                $array[$linha[$campoCod]] = $linha[$campoDesc];
            }
        }

        if ($ordena !== false) {

            if (!\is_bool($ordena)) {
                $ordena = strtoupper($ordena);
            }

            if ($ordena === "ASC" or $ordena === "" or $ordena === true) {
                $array = $this->ordenaArray($array);
            } elseif ($ordena === "DESC") {
                $array = \array_reverse($this->ordenaArray($array));
            }
        }

        return $array;
    }

    private function ordenaArray($vetor)
    {
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
    }

    protected function getTipoApresentacao(FormEscolha $config)
    {
        $multiplo = $config->getMultiplo();
        $expandido = $config->getExpandido();

        if ($expandido === true and $multiplo === true) {
            return 'check';
        } else if ($expandido === true and $multiplo === false) {
            return 'radio';
        } elseif ($expandido === false and $multiplo === false) {
            return 'select';
        }
    }

    private function montaCheckRadio($tipo, FormEscolha $config, $array, $retornarArray)
    {        
        $type = $tipo === 'radio' ? 'type="radio"' : 'type="checkbox"';

        $name = 'name="' . $config->getNome() . '"';

        if (!$config->getId()) {
            $config->setId($config->getNome());
        }

        $eSelecionado = false;
        $valor = $config->getValor();
        
        if ($valor) {
            $valorPadrao = '';
        } else {
            $valorPadrao = $config->getValorPadrao();
        }
        
        $complementos = $config->getComplemento();

        foreach ($array as $chave => $vale) {

            $id = 'id="' . str_replace('[]', '', $config->getId()) . $chave . '"';
            $classCss = $config->getClassCss() ? 'class="' . $config->getClassCss() . '"' : '';            
            $disable = ($config->getDisabled() === true) ? 'disabled="disabled"' : '';

            $value = 'value="' . $chave . '"';

            $checked = '';
            if ($eSelecionado === false) {

                if (is_array($valor)) {
                    
                    if (empty($valor)) {

                        if (is_array($valorPadrao)) {
                            
                            if (in_array($chave, $valorPadrao)) {
                                $checked = 'checked="checked"';
                            }
                            
                        } else {
                            
                            if ("{$valorPadrao}" === "$chave") {
                                $checked = 'checked="checked"';
                            }
                        }
                    } elseif (in_array($chave, $valor)) {
                        $checked = 'checked="checked"';
                    }
                    
                } else {
                    
                    if ($valor == '') {

                        if (is_array($valorPadrao)) {
                            
                            if (in_array($chave, $valorPadrao)) {
                                $checked = 'checked="checked"';
                            }
                            
                        } else {
                            
                            if ("{$valorPadrao}" === "$chave") {
                                $eSelecionado = true;
                                $checked = 'checked="checked"';
                            }
                        }
                    } elseif ("$chave" === "$valor") {
                        $eSelecionado = true;
                        $checked = 'checked="checked"';
                    }
                }
            }
            
            //Complemento
            if(is_array($complementos)){
                if(key_exists($chave, $complementos)){
                    $complemento = $complementos[$chave];
                }
                else{
                    $complemento = '';
                }
            }
            else{
                $complemento = $complementos;
            }
            
            $html = sprintf("<input %s %s %s %s %s %s %s %s>", $type, $name, $id, $value, $complemento, $disable, $checked, $classCss);

            if ($retornarArray === true) {
                $retorno[] = [
                    'html' => $html,
                    'label' => $vale];
            } else {
                $retorno .= $html;
            }
        }

        return $retorno;
    }

    /**
     * EscolhaHtml::montaSelect()
     * 
     * @param mixed $config
     * @return
     */
    private function montaSelect(FormEscolha $config, $array)
    {
        $inicio = $config->getInicio();
        $name = 'name="' . $config->getNome() . '"';
        $id = ($config->getId() == '') ? 'id="' . $config->getNome() . '" ' : 'id="' . $config->getId() . '"';
        $complemento = $config->getComplemento();
        $classCss = $config->getClassCss() ? 'class="' . $config->getClassCss() . '"' : '';
        $disable = ($config->getDisabled() === true) ? 'disabled="disabled"' : '';
        $valor = $config->getValor();

        $opcoes = '';
        if ($inicio != '') {
            $opcoes = ($inicio === true) ? '<option value="">Selecione...</option>' : '<option value="">' . $inicio . '</option>';
        }

        $eSelecionado = false;
        $cont = 0;
        foreach ($array as $chave => $vale) {

            $cont++;

            $opcoes .= '<option value="' . $chave . '" ';

            if ($eSelecionado === false) {
                if ($valor == '') {
                    if ("{$config->getValorPadrao()}" === "{$chave}") {
                        $eSelecionado = true;
                        $opcoes .= 'selected';
                    }
                } elseif ("{$chave}" === "{$valor}") {
                    $eSelecionado = true;
                    $opcoes .= 'selected';
                }
            }

            $opcoes .= ' > ' . $vale . ' </option>';
        }

        $retorno = sprintf('<select %s %s %s %s %s>%s</select>', $name, $id, $complemento, $disable, $classCss, $opcoes);

        return $retorno;
    }

}
