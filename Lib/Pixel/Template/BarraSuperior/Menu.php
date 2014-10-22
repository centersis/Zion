<?php

namespace Pixel\Template\BarraSuperior;

class Menu extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Menu
     */
    public function getMenu()
    {	

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('ul', array('class' => 'nav navbar-nav'));
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE . 'Dashboard'));
        $buffer .= "Início";
        $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');
        /*
          // inicio menu Administrativo
          $buffer .= $this->html->abreTagAberta('li', array('class' => 'dropdown'));
          $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'));
          $buffer .= "Gestão Administrativa";
          $buffer .= $this->html->fechaTag('a');
          $buffer .= $this->html->abreTagAberta('ul', array('class' => 'dropdown-menu'));
          $buffer .= $this->html->abreTagAberta('li');
          $buffer .= $this->html->abreTagAberta('a', array('href' => './?ref=navbar-nav-option-1'));
          $buffer .= "Cadastros";
          $buffer .= $this->html->fechaTag('a');
          $buffer .= $this->html->fechaTag('li');
          $buffer .= $this->html->fechaTag('ul');
          $buffer .= $this->html->fechaTag('li');
          // fim menu Administrativo
         */
        // end: nav navbar-nav
        $buffer .= $this->html->fechaTag('ul');

        return $buffer;

	}

}