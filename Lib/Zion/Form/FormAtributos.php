<?php

namespace Zion\Form;

class FormAtributos
{

    private $atributos;

    public function __construct()
    {
        $this->atributos = array(
            'name' => ' name="%s" ',
            'id' => ' id="%s" ',
            'type' => ' type="%s" ',
            'value' => ' value="%s" ',
            'size' => ' size="%s" ',
            'maxlength' => ' maxlength="%s" ',
            'disabled' => ' disabled="%s" ',
            'placeholder' => ' placeholder="%s" ',
            'autocomplete' => ' autocomplete="%s" ',
            'caixa' => ' style="text-transform:%s;" ',
            'max' => ' max="%s" ',
            'min' => ' min="%s" ',
            'option' => '<option value="%s">%s</option>',
            'formmethod' => ' formmethod="%s" ',
            'formaction' => ' formaction="%s" ',
            'formtarget' => ' formtarget="%s" ',
            'complemento' => ' %s ',
            'valueButton' => ' %s ',
            'action' => ' action="%s" ',
            'enctype' => ' enctype="%s" ',
            'method' => ' method="%s" ',
            'novalidate' => ' novalidate="%s" ',
            'target' => ' target="%s" '
        );
    }

    private function tipoEspecial($tipo, $valor)
    {
        if ($valor == '' and $valor !== false) {
            return '';
        }

        switch ($tipo) {
            case 'disabled':
                $ret = 'disabled';
                break;
            case 'caixa':
                $ret = $valor == 'ALTA' ? 'uppercase' : 'lowercase';
                break;
            case 'autocomplete':
                $ret = $valor === true ? 'on' : 'off';
                break;
            default : $ret = $valor;
        }

        return $ret;
    }

    protected function attr($nome)
    {
        $pars = array();

        $args = func_get_args();

        $cont = 0;
        foreach ($args as $valor) {

            $valor = $this->tipoEspecial($nome, $valor);

            $cont++;
            if ($cont == 1 or ( $nome != 'value' and $nome != 'id' and $valor == '')) {
                continue;
            }

            $pars[] = $valor;
        }

        return $pars ? vsprintf($this->atributos[$nome], $pars) : '';
    }

    protected function prepareButton($totalAtributos)
    {
        return "<button " . str_repeat('%s', $totalAtributos - 1) . ">%s</button>";
    }

    protected function prepareForm($totalAtributos)
    {
        return "<form " . str_repeat('%s', $totalAtributos) . ">";
    }
    
    protected function prepareInput($totalAtributos, $config)
    {
        $html = new \Zion\Layout\Html();
        
        $buffer = '';
        
        if($config->getemColunaDeTamanho()){
            $buffer .= $html->abreTagAberta('section', array('class'=>'col col-'.$config->getemColunaDeTamanho()));                    
        }
            if($config->getLabel()){
                $buffer .= $html->abreTagAberta('label',array('class'=>'label'));
                $buffer .= $config->getIdentifica();
                $buffer .= $html->fechaTag('label');
            }
            
            $labelAux = false;
            if($buffer || $config->getIconFA() || $config->getToolTipMsg()){ 
                $buffer .= $html->abreTagAberta('label',array('class'=>'input'));
                $labelAux = true;
            }

                if($config->getIconFA()){
                    $buffer .= $html->abreTagAberta('i',array('class'=>'icon-append fa '.$config->getIconFA()));
                    $buffer .= $html->fechaTag('i');
                }

                $buffer.= '<input ' . str_repeat('%s', $totalAtributos) . '/>';

                if($config->getToolTipMsg()){
                    $buffer .= $html->abreTagAberta('b',array('class'=>'tooltip tooltip-top-right'));
                        $buffer .= $html->abreTagAberta('i',array('class'=>'fa fa-warning txt-color-teal'));                        
                        $buffer .= $html->fechaTag('i');
                        $buffer .= $config->getToolTipMsg();
                    $buffer .= $html->fechaTag('b');
                }
            
            if($labelAux === true){
                $buffer .= $html->fechaTag('label');
            }
        
        if($config->getemColunaDeTamanho()){
            $buffer .= $html->fechaTag('section');
        }
        
        return $buffer;
    }
}
