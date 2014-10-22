<?php

namespace Pixel\Template\Main;

class BreadCrumb extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Menu
     */
    public function getBreadCrumb()
    {	

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('ul', array('class' => 'breadcrumb breadcrumb-page'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'breadcrumb-label text-light-gray')) . 'Você está aqui: ' . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('a', array('href' => '#')) . 'Início' . $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');

        if (defined('DEFAULT_GRUPO_NOME')) {

            $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#')) . DEFAULT_GRUPO_NOME . $this->html->fechaTag('a');
            $buffer .= $this->html->fechaTag('li');
        }

        $buffer .= $this->html->abreTagAberta('li', array('class' => 'active'));
        $buffer .= $this->html->abreTagAberta('a', array('href' => './?ref=' . DEFAULT_MODULO_NOME)) . DEFAULT_MODULO_NOME . $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->fechaTag('ul');

        return $buffer;

	}

}