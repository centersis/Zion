<?php

namespace Zion\Form;

class FormHtml extends \Zion\Form\FormAtributos
{

    public function __construct()
    {
        parent::__construct();
    }

    public function opcoesBasicas($config)
    {
        return array($this->attr('name', $config->getNome()),
            $this->attr('id', $config->getId() ? $config->getId() : $config->getNome()),
            $this->attr('value', $config->getValor()),
            $this->attr('complemento', $config->getComplemento()),
            $this->attr('disabled', $config->getDisabled()),
            $this->attr('classCss', $config->getClassCss()));
    }

    public function montaHidden(FormInputHidden $config)
    {
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'hidden')));

        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaTexto(FormInputTexto $config)
    {
        $type = 'text';
        
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', $type),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('size', $config->getLargura()),
            $this->attr('caixa', $config->getCaixa()),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('autocomplete', $config->getAutoComplete())));
        
        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaCpf(\Zion\Form\FormInputCpf $config)
    {
        
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())));
        
        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaCnpj(\Zion\Form\FormInputCnpj $config)
    {
        
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())));
        
        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaCep(\Zion\Form\FormInputCep $config)
    {

        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())));
        
        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaEmail(\Zion\Form\FormInputEmail $config)
    {
        
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())));
        
        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaTelefone(\Zion\Form\FormInputTelefone $config)
    {
        
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())));
        
        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaSenha(\Zion\Form\FormInputSenha $config)
    {
        
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'password'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())));
        
        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaTextArea(\Zion\Form\FormInputTextArea $config)
    {
        //AINDA NÃO IMPLEMENTADO!
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())));
        
        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaData(\Zion\Form\FormInputData $config)
    {        
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getDataMaxima()),
            $this->attr('min', $config->getDataMinima())));

        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaHora(\Zion\Form\FormInputHora $config)
    {        
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getHoraMaxima()),
            $this->attr('min', $config->getHoraMinima())));

        return vsprintf($this->prepareInput(count($attr)), $attr);
    }

    public function montaNumber(\Zion\Form\FormInputNumber $config)
    {
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'number'),
            $this->attr('size', $config->getLargura()),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getValorMaximo()),
            $this->attr('min', $config->getValorMinimo())));

        return vsprintf($this->prepareInput(count($attr),$config), $attr);
    }

    public function montaFloat(\Zion\Form\FormInputFloat $config)
    {
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text')));

        return vsprintf($this->prepareInput(count($attr),$config), $attr);
    }

    public function montaEscolha(\Zion\Form\FormEscolha $config)
    {
        return (new \Zion\Form\EscolhaHtml())->montaEscolha($config);        
    }

    public function montaButton($config)
    {
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('name', $config->getNome()),
            $this->attr('id', $config->getId()),
            $this->attr('type', $config->getAcao()),
            $this->attr('formmethod', $config->getMetodo()),
            $this->attr('formaction', $config->getAction()),
            $this->attr('formtarget', $config->getTarget())));

        $attr[] = $this->attr('valueButton', $config->getValor());

        return vsprintf($this->prepareButton(count($attr),$config), $attr);
    }
    
    public function montaLayout(FormLayout $config)
    {
        return $config->getConteudo();
    }

    public function abreForm(FormTag $config)
    {
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

        return vsprintf($this->prepareForm(count($attr),$config), $attr);
    }

    public function fechaForm()
    {
        return '</form>';
    }

}
