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
        $ignoreCod = $config->getIgnoreCod();

        if ($tabela and $campoCod and $campoDesc) {

            $con = \Zion\Banco\Conexao::conectar($config->getIdConexao());

            if (!empty($sqlCompleto)) {
                $sql = $sqlCompleto;
            } else {
                
                $sql = $con->link()->createQueryBuilder();
                
                $sql->select($campoCod,$campoDesc)
                        ->from($tabela);
                
                if($where){
                    $sql->where($where);
                }                
            }

            $rs = $con->executar($sql);

            while ($linha = $rs->fetch()) {
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
        
        if(\is_array($ignoreCod)){
            foreach ($ignoreCod as $ignorar){
                if(\key_exists($ignorar, $array)){
                    unset($array[$ignorar]);
                }
            }
        }

        return $array;
    }

    private function ordenaArray($vetor)
    {
        if(!\is_array($vetor)){
            return [];
        }
        
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
        $chosen = $config->getChosen();

        if ($chosen === true) {
            return 'select';
        }

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
            if (is_array($complementos)) {
                if (key_exists($chave, $complementos)) {
                    $complemento = $complementos[$chave];
                } else {
                    $complemento = '';
                }
            } else {
                $complemento = $complementos;
            }

            $html = sprintf("<input %s %s %s %s %s %s %s %s>", $type, $name, $id, $value, $complemento, $disable, $checked, $classCss);

            $buffer = '';

            if ($config->getContainer()) {
                $buffer .= '<div id="' . $config->getContainer() . '">';
            }

            $buffer .= $html;

            if ($config->getContainer()) {
                $buffer .= '</div>';
            }
            
            if ($retornarArray === true) {
                $retorno[] = [
                    'html' => $buffer,
                    'label' => $vale];
            } else {
                $retorno .= $buffer;
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
        $id = 'id="' . str_replace('[]', '', $config->getId()) . '"';
        $complemento = $config->getComplemento();
        $classCss = $config->getClassCss() ? 'class="' . $config->getClassCss() . '"' : '';
        $disable = ($config->getDisabled() === true) ? 'disabled="disabled"' : '';
        $valor = $config->getValor();
        $chosen = $config->getChosen();

        $opcoes = '';

        if ($inicio != '' and $chosen !== true) {
            $opcoes = ($inicio === true) ? '<option value="">Selecione...</option>' : '<option value="">' . $inicio . '</option>';
        }

        if ($chosen === true) {

            $opcoes = '<option></option>';

            if ($config->getMultiplo() === true) {
                $complemento .= ' multiple="multiple"';
            }
        }

        if ($valor) {
            $valorPadrao = '';
        } else {
            $valorPadrao = $config->getValorPadrao();
        }

        $eSelecionado = false;
        $cont = 0;

        if(is_array($array)) {
            foreach ($array as $chave => $vale) {

                $cont++;

                $opcoes .= '<option value="' . $chave . '" ';

                if (is_array($valor)) {
                    if (empty($valor)) {

                        if (is_array($valorPadrao)) {

                            if (in_array($chave, $valorPadrao)) {
                                $opcoes .= 'selected';
                            }
                        } else {

                            if ("{$valorPadrao}" === "$chave") {
                                $opcoes .= 'selected';
                            }
                        }
                    } elseif (in_array($chave, $valor)) {
                        $opcoes .= 'selected';
                    }
                } else {
                    if ($eSelecionado === false) {
                        if ($valor == '') {

                            if (is_array($valorPadrao)) {

                                if (in_array($chave, $valorPadrao)) {
                                    $opcoes .= 'selected';
                                }
                            } else {

                                if ("{$valorPadrao}" === "$chave") {
                                    $opcoes .= 'selected';
                                }
                            }
                        } elseif ("{$chave}" === "{$valor}") {
                            $eSelecionado = true;
                            $opcoes .= 'selected';
                        }
                    }
                }

                $opcoes .= ' > ' . $vale . ' </option>';
            }
        }

        $retorno = sprintf('<select %s %s %s %s %s>%s</select>', $name, $id, $complemento, $disable, $classCss, $opcoes);

        $buffer = '';

        if ($config->getContainer()) {
            $buffer .= '<div id="' . $config->getContainer() . '">';
        }

        $buffer .= $retorno;

        if ($config->getContainer()) {
            $buffer .= '</div>';
        }

        return $buffer;
    }

}
