<?php

namespace Zion\Form;

use Zion\Form\FormAtributos;
use Zion\Form\FormInputCpf;
use Zion\Form\FormInputCnpj;
use Zion\Form\FormInputCep;
use Zion\Form\FormInputEmail;
use Zion\Form\FormInputTelefone;
use Zion\Form\FormInputSenha;
use Zion\Form\FormInputTextArea;
use Zion\Form\FormInputData;
use Zion\Form\FormInputHora;
use Zion\Form\FormInputNumber;
use Zion\Form\FormInputFloat;
use Zion\Form\FormEscolha;

class FormHtml extends FormAtributos
{

    public function __construct()
    {
        parent::__construct();
    }

    public function opcoesBasicas($config, $ignore = [])
    {
        $valor = ($config->getValor() === NULL or $config->getValor() === '') ? $config->getValorPadrao().'' : $config->getValor().'';

        $ret = [];

        $ret['name'] = $this->attr('name', $config->getNome());
        $ret['id'] = $this->attr('id', $config->getId() ? $config->getId() : $config->getNome());
        $ret['value'] = $this->attr('value', $valor);
        $ret['complemento'] = $this->attr('complemento', $config->getComplemento());
        $ret['disabled'] = $this->attr('disabled', $config->getDisabled());
        $ret['classCss'] = $this->attr('classCss', $config->getClassCss());

        // Adiciona atributos do input, se existirem
        if (\is_array($config->getAtributos())) {
            foreach ($config->getAtributos() as $attrName => $v) {
                $ret[$attrName] = "{$attrName}=\"{$v}\"";
            }
        }

        foreach ($ignore as $ignorar) {
            unset($ret[$ignorar]);
        }

        return $ret;
    }

    /**
     * 
     * @param \Zion\Form\FormInputHidden $config
     * @return array
     */
    public function montaHiddenHtml($config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'hidden')]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param \Zion\Form\FormUpload $config
     * @return array
     */
    public function montaUploadHtml($config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'file'),
            $this->attr('multiple', $config->getMultiple()),
            $this->attr('form', $config->getForm())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param \Zion\Form\FormInputTexto $config
     * @return array
     */
    public function montaTextoHtml($config)
    {
        $type = 'text';

        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', $type),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('size', $config->getLargura()),
            $this->attr('caixa', $config->getCaixa()),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('autocomplete', $config->getAutoComplete())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param FormInputCpf $config
     * @return array
     */
    public function montaCpfHtml($config)
    {

        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param FormInputCnpj $config
     * @return array
     */
    public function montaCnpjHtml($config)
    {

        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param FormInputCep $config
     * @return array
     */
    public function montaCepHtml($config)
    {

        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param FormInputEmail $config
     * @return array
     */
    public function montaEmailHtml($config)
    {

        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaCorHtml($config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'hidden')]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param FormInputTelefone $config
     * @return array
     */
    public function montaTelefoneHtml($config)
    {

        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param FormInputSenha $config
     * @return array
     */
    public function montaSenhaHtml($config)
    {

        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'password'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param FormInputTextArea $config
     * @return array
     */
    public function montaTextAreaHtml($config)
    {
        $attr = \array_merge($this->opcoesBasicas($config,['value']), [
            $this->attr('type', 'textarea'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('readonly', $config->getReadonly()),
            $this->attr('colunas', $config->getColunas()),
            $this->attr('linhas', $config->getLinhas()),
            $this->attr('form', $config->getForm())]);

        $attr[] = $this->attr('valueTextArea', $config->getValor());

        return \vsprintf($this->prepareTextArea(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param FormInputData $config
     * @return array
     */
    public function montaDataHtml($config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param \Zion\Form\FormInputDataHora $config
     * @return array
     */
    public function montaDataHoraHtml($config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getDataHoraMaxima()),
            $this->attr('min', $config->getDataHoraMinima())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param FormInputHora $config
     * @return array
     */
    public function montaHoraHtml($config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getHoraMaxima()),
            $this->attr('min', $config->getHoraMinima())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param FormInputNumber $config
     * @return array
     */
    public function montaNumberHtml($config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'number'),
            $this->attr('size', $config->getLargura()),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getValorMaximo()),
            $this->attr('min', $config->getValorMinimo())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param FormInputFloat $config
     * @return array
     */
    public function montaFloatHtml($config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text')]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * 
     * @param FormEscolha $config
     * @param type $retornarArray
     * @return string
     */
    public function montaEscolhaHtml($config, $retornarArray)
    {
        return (new EscolhaHtml())->montaEscolha($config, $retornarArray);
    }

    public function montaButtonHtml($config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('name', $config->getNome()),
            $this->attr('id', $config->getId()),
            $this->attr('type', $config->getAcao()),
            $this->attr('formmethod', $config->getMetodo()),
            $this->attr('formaction', $config->getAction()),
            $this->attr('formtarget', $config->getTarget())]);

        $attr[] = $this->attr('valueButton', $config->getValor());

        return \vsprintf($this->prepareButton(\count($attr), $config), $attr);
    }

    public function montaLayoutHtml(FormLayout $config)
    {
        return $config->getConteudo();
    }

}
