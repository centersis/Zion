<?php

namespace Zion\Form;

class FormHtml extends \Zion\Form\FormAtributos
{

    public function __construct()
    {
        parent::__construct();
    }

    private function opcoesBasicas($config)
    {
        return array($this->attr('name', $config->getNome()),
            $this->attr('id', $config->getId() ? $config->getId() : $config->getNome()),
            $this->attr('value', $config->getValor()),
            $this->attr('complemento', $config->getComplemento()),
            $this->attr('disabled', $config->getDisabled()));
    }

    public function montaHidden(FormInputHidden $config)
    {
        if (empty($config->getNome())) {
            throw new \Exception('Atributo nome é obrigatório');
        }

        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'hidden')));

        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaSuggest(FormInputSuggest $config)
    {
        if (empty($config->getNome())) {
            throw new \Exception('Atributo nome é obrigatório');
        }

        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('size', $config->getLargura()),
            $this->attr('caixa', $config->getCaixa()),
            $this->attr('placeholder', $config->getPlaceHolder())));

        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaTexto(FormInputTexto $config)
    {
        if (empty($config->getNome())) {
            throw new \Exception('Atributo nome é obrigatório');
        }

        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('size', $config->getLargura()),
            $this->attr('caixa', $config->getCaixa()),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('autocomplete', $config->getAutoComplete())));

        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaDateTime(FormInputDateTime $config)
    {
        if (empty($config->getNome())) {
            throw new \Exception('Atributo nome é obrigatório');
        }

        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', $config->getAcao()),
            $this->attr('max', $config->getDataMaxima()),
            $this->attr('min', $config->getDataMinima())));

        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaNumber(FormInputNumber $config)
    {
        if (empty($config->getNome())) {
            throw new \Exception('Atributo nome é obrigatório');
        }

        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'number'),
            $this->attr('max', $config->getValorMaximo()),
            $this->attr('min', $config->getValorMinimo())));

        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaFloat(FormInputFloat $config)
    {
        if (empty($config->getNome())) {
            throw new \Exception('Atributo nome é obrigatório');
        }

        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text')));

        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaEscolha(FormEscolha $config)
    {
        if (empty($config->getNome())) {
            throw new \Exception('Atributo nome é obrigatório');
        }

        $inicio = $config->getInicio();
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

        if ($select and $inicio != '') {
            $opcoes = ($inicio === true) ? '<option value="">Selecione...</option>' : '<option value="">' . $inicio . '</option>';
        }

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

            $texto = new \Zion\Validacao\Texto();
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

        if ($select) {
            $opcoes = ($inicio === true) ? '<option value="">Selecione...</option>' : '<option value="">' . $inicio . '</option>';
        }

        $retorno = '';

        foreach ($array as $chave => $vale) {

            if ($select) { // Campo Select                           
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
            $retorno = sprintf("<select %s %s %s %s>%s</select>", $name, $id, $complemento, $disable, $opcoes);
        }

        return $retorno;
    }

    public function montaButton(FormInputButton $config)
    {
        if (empty($config->getNome())) {
            throw new \Exception('Atributo nome é obrigatório');
        }

        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('name', $config->getNome()),
            $this->attr('id', $config->getId()),
            $this->attr('type', $config->getAcao()),
            $this->attr('formmethod', $config->getMetodo()),
            $this->attr('formaction', $config->getAction()),
            $this->attr('formtarget', $config->getTarget())));

        $attr[] = $this->attr('valueButton', $config->getValor());

        return vsprintf($this->prepareButton(count($attr)), $attr);
    }

    public function abreForm(FormTag $config)
    {
        if (empty($config->getNome())) {
            throw new Exception('Atributo nome é obrigatório');
        }

        $attr = array(
            $this->attr('name', $config->getNome()),
            $this->attr('id', $config->getId() ? $config->getId() : $config->getNome()),
            $this->attr('action', $config->getAction()),
            $this->attr('autocomplete', $config->getAutoComplete()),
            $this->attr('enctype', $config->getEnctype()),
            $this->attr('method', $config->getMethod()),
            $this->attr('novalidate', $config->getNovalidate()),
            $this->attr('target', $config->getTarget()),
            $this->attr('complemento', $config->getComplemento()),
            $this->attr('classCss', $config->getClassCss()));

        return vsprintf($this->prepareForm(count($attr)), $attr);
    }

    public function fechaForm()
    {
        return '</form>';
    }

}
