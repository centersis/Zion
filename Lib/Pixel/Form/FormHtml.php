<?php

namespace Lib\Pixel\Form;

class FormHtml extends \Zion\Form\FormHtml
{

    public function __construct()
    {
        parent::__construct();
    }

    public function montaSuggest(FormInputSuggest $config)
    {
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('size', $config->getLargura()),
            $this->attr('caixa', $config->getCaixa()),
            $this->attr('placeholder', $config->getPlaceHolder())));

        $ret = vsprintf($this->prepareInput(count($attr),$config), $attr);
        
        if($config->getHiddenValue()){
            
            $cofHidden = new \Zion\Form\FormInputHidden('hidden', $config->getHiddenValue());
            
            $ret.= $this->montaHidden($cofHidden);
        }
        
        return $ret;
    }

    public function montaTexto(FormInputTexto $config)
    {
        $classCss = $config->getClassCss().' form-control';
        $config->setClassCss($classCss);
        
        if($config->getToolTipMsg()){
            $complemento = $config->getComplemento().' title="'.$config->getToolTipMsg().'"';
            $config->setComplemento($complemento);
        }
        
        return $this->preparePixel($config, parent::montaTexto($config));
    }
    
    private function preparePixel(FormInputTexto $config, $campo)
    {
        $html = new \Zion\Layout\Html();
        
        $buffer = $html->abreTagAberta('div',array('class'=>'col-sm-'.$config->getEmColunaDeTamanho()));                    

            $buffer.= $html->abreTagAberta('div',array('class'=>'form-group no-margin-hr'));
            
                $buffer.= $html->abreTagAberta('label',array('class'=>'control-label'));
                $buffer.= $config->getIdentifica();
                $buffer .= $html->fechaTag('label');
                
                $buffer .= $campo;
                
                if($config->getIconFA()){
                    $buffer.= $html->abreTagAberta('span',array('class'=>'input-group-addon'));
                    $buffer.= $html->abreTagAberta('i',array('class'=>'fa '.$config->getIconFA()));
                    $buffer .= $html->fechaTag('i');
                    $buffer .= $html->fechaTag('span');
                }
                
                
            $buffer .= $html->fechaTag('div');
        $buffer .= $html->fechaTag('div');
        
        return $buffer;
    }

    public function montaDateTime(FormInputDateTime $config)
    {
        if($config->getAcao() == 'date')
        {
            $atualComplemento = $config->getComplemento();
            $novoComplemento = 'class="datepicker" data-dateformat="dd/mm/yy" data-mask="99/99/9999" data-mask-placeholder= "-"';
            $config->setComplemento($atualComplemento.$novoComplemento);
        }        

        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getDataMaxima()),
            $this->attr('min', $config->getDataMinima())));

        return vsprintf($this->prepareInput(count($attr),$config), $attr);
    }

    public function montaNumber(FormInputNumber $config)
    {
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'number'),
            $this->attr('max', $config->getValorMaximo()),
            $this->attr('min', $config->getValorMinimo())));

        return vsprintf($this->prepareInput(count($attr),$config), $attr);
    }

    public function montaFloat(FormInputFloat $config)
    {
        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text')));

        return vsprintf($this->prepareInput(count($attr),$config), $attr);
    }

    public function montaEscolha(FormEscolha $config)
    {
        return (new \Zion\Form\EscolhaHtml())->montaEscolha($config);        
    }

    public function montaButton(FormInputButton $config)
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
