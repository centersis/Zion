<?php

namespace Pixel\Form;
use \Zion\Form\Exception\FormException as FormException;

class FormSetPixel
{  
    
    public function setIconFA($iconFA)
    {
        if (!empty($iconFA)) {
            return $iconFA;
        } else {
            throw new FormException("iconFA: Nenhum valor informado");
        }
    }
    
    public function setToolTipMsg($toolTipMsg)
    {
        if (!empty($toolTipMsg)) {
            return $toolTipMsg;
        } else {
            throw new FormException("toolTipMsg: Nenhum valor informado");
        }
    }
   
    public function setEmColunaDeTamanho($emColunaDeTamanho)
    {        
        if (in_array($emColunaDeTamanho, range(1, 12))) {
            return $emColunaDeTamanho;
        } else {
            throw new FormException("emColunaDeTamanho: Use variação de 1 a 12");
        }
    }
    
    public function setMascara($mascara)
    {
        if (!empty($mascara)) {
            return $mascara;
        } else {
            throw new FormException("mascara: Nenhum valor informado");
        }
    }
}