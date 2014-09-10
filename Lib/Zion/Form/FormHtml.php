<?php

namespace Zion\Form;

class FormHtml
{
    protected function montaTexto(FormInputTexto $config)
    {
        if (empty($config->getNome())) {
            throw new Exception('Atributo nome é obrigatório');
        }

        $name = 'name="' . $config->getNome() . '"';
        $id = ($config->getId() == '') ? 'id="' . $config->getNome() . '" ' : 'id="' . $config->getId() . '"';
        $tipo = 'type="' . strtolower($config->getTipo()) . '"';
        $value = ' value="' . $config->getValor() . '" ';
        $size = ($config->getLargura()) ? 'size="' . $config->getLargura() . '"' : '';
        $len = (is_numeric($config->getMaximoCaracteres())) ? 'maxlength="' . $config->getMaximoCaracteres() . '"' : '';
        $complemento = $config->getComplemento();
        $disable = ($config->getDisabled() === false) ? 'disabled="disabled"' : '';
        $placeholder = ($config->getPlaceHolder() != '') ? 'placeholder="' . $config->getPlaceHolder() . '"' : '';
        $autocomplete = ($config->getAutoComplete() === false) ? 'autocomplete="off"' : '';

        if ($config->getMaiusculoMinusculo() == "ALTA") {
            $estiloCaixa = 'style="text-transform: uppercase;"';
        } else if ($config->getMaiusculoMinusculo() == "BAIXA") {
            $estiloCaixa = 'style="text-transform: lowercase;"';
        } else {
            $estiloCaixa = '';
        }

        $retorno = sprintf("<input %s %s %s %s %s %s %s %s %s %s %s/>", $name, $id, $tipo, $value, $size, $len, $estiloCaixa, $complemento, $disable, $placeholder, $autocomplete);

        return $retorno;
    }

    protected function montaDate(FormInputDate $config)
    {
        if (empty($config->getNome())) {
            throw new Exception('Atributo nome é obrigatório');
        }

        $name = 'name="' . $config->getNome() . '"';
        $id = ($config->getId() == '') ? 'id="' . $config->getNome() . '" ' : 'id="' . $config->getId() . '"';
        $tipo = 'type="' . strtolower($config->getTipo()) . '"';
        $value = $config->getValor();
        $complemento = $config->getComplemento();
        $disable = ($config->getDisabled() === false) ? 'disabled="disabled"' : '';
        $max = ($config->getDataMaxima()) ? 'max="' . $config->getDataMaxima() . '"' : '';
        $min = ($config->getDataMinima()) ? 'min="' . $config->getDataMinima() . '"' : '';

        $retorno = sprintf("<input %s %s %s %s %s %s %s %s />", $name, $id, $tipo, $value, $max, $min, $complemento, $disable);

        return $retorno;
    }
    
    protected function montaNumber(FormInputNumber $config)
    {
        if (empty($config->getNome())) {
            throw new Exception('Atributo nome é obrigatório');
        }

        $name = 'name="' . $config->getNome() . '"';
        $id = ($config->getId() == '') ? 'id="' . $config->getNome() . '" ' : 'id="' . $config->getId() . '"';
        $tipo = 'type="' . strtolower($config->getTipo()) . '"';
        $value = $config->getValor();
        $complemento = $config->getComplemento();
        $disable = ($config->getDisabled() === false) ? 'disabled="disabled"' : '';
        $max = ($config->getValorMaximo()) ? 'max="' . $config->getValorMaximo() . '"' : '';
        $min = ($config->getValorMinimo()) ? 'min="' . $config->getValorMinimo() . '"' : '';

        $retorno = sprintf("<input %s %s %s %s %s %s %s %s />", $name, $id, $tipo, $value, $max, $min, $complemento, $disable);

        return $retorno;
    }
    
    protected function montaFloat(FormInputFloat $config)
    {
        if (empty($config->getNome())) {
            throw new Exception('Atributo nome é obrigatório');
        }

        $name = 'name="' . $config->getNome() . '"';
        $id = ($config->getId() == '') ? 'id="' . $config->getNome() . '" ' : 'id="' . $config->getId() . '"';
        $tipo = 'type="' . strtolower($config->getTipo()) . '"';
        $value = $config->getValor();
        $complemento = $config->getComplemento();
        $disable = ($config->getDisabled() === false) ? 'disabled="disabled"' : '';
        $max = ($config->getValorMaximo()) ? 'max="' . $config->getValorMaximo() . '"' : '';
        $min = ($config->getValorMinimo()) ? 'min="' . $config->getValorMinimo() . '"' : '';
        $prefixo = $config->getPrefixo();

        $retorno = sprintf("%s<input %s %s %s %s %s %s %s %s />", $prefixo, $name, $id, $tipo, $value, $max, $min, $complemento, $disable);

        return $retorno;
    }

    protected function montaButton(FormInputButton $config)
    {
        if (empty($config->getNome())) {
            throw new Exception('Atributo nome é obrigatório');
        }

        $name = 'name="' . $config->getNome() . '"';
        $id = ($config->getId() == '') ? 'id="' . $config->getNome() . '" ' : 'id="' . $config->getId() . '"';
        $tipo = 'type="' . strtolower($config->getTipo()) . '"';
        $value = $config->getValor();
        $complemento = $config->getComplemento();
        $disable = ($config->getDisabled() === false) ? 'disabled="disabled"' : '';
        $metodo = ($config->getMetodo()) ? 'formmethod="'.$config->getMetodo().'"' : '';
        $action = ($config->getAction()) ? 'formaction="'.$config->getAction().'"' : '';
        $target = ($config->getAction()) ? 'formtarget="'.$config->getAction().'"' : '';

        $retorno = sprintf("<button %s %s %s %s %s %s %s %s>%s</button>", $name, $id, $tipo, $complemento, $disable, $metodo, $action, $target, $value);

        return $retorno;
    }
}
