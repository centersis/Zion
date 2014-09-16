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
            'disabled' => ' disabled="%" ',
            'placeholder' => ' placeholder="%" ',
            'autocomplete' => ' autocomplete="%s" ',
            'caixa' => ' style="text-transform:%s;" ',
            'max' => ' max="%" ',
            'min' => ' min="%s" ',
            'option' => '<option value="%s">%s</option>',
            'formmethod' => ' formmethod="%s" ',
            'formaction' => ' formaction="%s" ',
            'formtarget' => ' formtarget="%s" ',
            'complemento' => ' %s '
        );
    }

    private function tipoEspecial($tipo, $valor)
    {
        $ret = $valor;
        
        if($ret == ''){
            return $ret;
        }
        
        switch ($tipo){
            case 'disabled': 
                $ret = $valor === true ? 'disabled' : ''; 
                break;
            case 'caixa':
                $ret = $valor == 'ALTA' ? 'uppercase' : 'lowercase'; 
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
        foreach ($args as $valor){
            
            $valor = $this->tipoEspecial($nome, $valor);
            
            $cont++;
            if($cont == 1 or ($nome != 'value' and $valor == '')){
                continue;
            }
            
            $pars[] = $valor;
        }
               
        return $pars ? vsprintf($this->atributos[$nome], $pars) : '';
    }

}
