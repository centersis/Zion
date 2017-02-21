<?php

namespace Pixel\Template\BarraSuperior;

class Menu extends \Zion\Layout\Padrao
{

    public function getMenu()
    {

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('ul', array('class' => 'nav navbar-nav'));
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE . 'Dashboard'));
        $buffer .= "Início";
        $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->fechaTag('ul');

        return $buffer;
    }

}
