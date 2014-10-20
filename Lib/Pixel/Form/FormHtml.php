<?php

namespace Pixel\Form;

class FormHtml extends \Zion\Form\FormHtml
{

    public function __construct()
    {
        parent::__construct();
    }

    public function montaSuggest(FormInputSuggest $config)
    {
        $classCss = $config->getClassCss() . ' form-control';
        $config->setClassCss($classCss);
        
        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('size', $config->getLargura()),
            $this->attr('caixa', $config->getCaixa()),
            $this->attr('placeholder', $config->getPlaceHolder())));

        $ret = vsprintf($this->prepareInput(count($attr)), $attr);


        if ($config->getHiddenValue()) {

            $cofHidden = new \Zion\Form\FormInputHidden('hidden', $config->getHiddenValue());

            $ret.= $this->montaHidden($cofHidden);
        }

        return $this->prepareInputPixel($config, $ret);
    }

    public function montaTexto(FormInputTexto $config)
    {
        $classCss = $config->getClassCss() . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }
        
        if($config->getAcao() == 'cpf'){
            $config->setMascara('999.999.999-99');
        }
        
        if($config->getAcao() == 'cnpj'){
            $config->setMascara('99.999.999/9999-99');
        }
        
        if($config->getAcao() == 'cep'){
            $config->setMascara('99.999-99');
        }

        return $this->prepareInputPixel($config, parent::montaTexto($config));
    }

    public function montaData(FormInputData $config)
    {
        $classCss = $config->getClassCss() . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        return $this->prepareInputPixel($config, parent::montaData($config));
    }

    public function montaHora(FormInputHora $config)
    {
        $classCss = $config->getClassCss() . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        return $this->prepareInputPixel($config, parent::montaHora($config));
    }

    public function montaNumber(FormInputNumber $config)
    {
        $classCss = $config->getClassCss() . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        return $this->prepareInputPixel($config, parent::montaNumber($config));
    }

    public function montaFloat(FormInputFloat $config)
    {
        $classCss = $config->getClassCss() . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        return $this->prepareInputPixel($config, parent::montaFloat($config));
    }

    public function montaEscolha(FormEscolha $config)
    {
        return (new \Zion\Form\EscolhaHtml())->montaEscolha($config);
    }

    public function montaButton($config)
    {                                                     
        return parent::montaButton($config);
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

        return vsprintf($this->prepareForm(count($attr), $config), $attr);
    }

    public function fechaForm()
    {
        return '</form>';
    }

    private function prepareInputPixel($config, $campo)
    {
        if($config->getLayoutPixel() === false){
            return $campo;
        }
        
        $html = new \Zion\Layout\Html();

        $buffer = $html->abreTagAberta('div', array('class' => 'col-sm-' . $config->getEmColunaDeTamanho()));

        $buffer.= $html->abreTagAberta('div', array('class' => 'form-group'));

        $buffer.= $html->abreTagAberta('label', array('for' => $config->getId(),'class'=>'col-sm-3 control-label'));
        $buffer.= $config->getIdentifica();
        $buffer .= $html->fechaTag('label');
        
        $buffer.= $html->abreTagAberta('div', array('class' => 'col-sm-9 has-feedback'));

        $buffer .= $campo;

        if ($config->getIconFA()) {
            $buffer.= $html->abreTagAberta('span', array('class' => 'fa ' . $config->getIconFA().' form-control-feedback'));            
            $buffer .= $html->fechaTag('span');
        }

        $buffer .= $html->fechaTag('div');
        $buffer .= $html->fechaTag('div');
        $buffer .= $html->fechaTag('div');

        return $buffer;
    }

}
