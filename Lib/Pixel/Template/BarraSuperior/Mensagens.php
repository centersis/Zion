<?php

namespace Pixel\Template\BarraSuperior;

class Mensagens extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Notificacoes
     */
    public function getMensagens()
    {	

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('li', array('class' => 'nav-icon-btn nav-icon-btn-success dropdown'));

        $buffer .= $this->html->abreTagAberta('a', array('href' => '#messages', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'));

        $buffer .= $this->html->abreTagAberta('span', array('class' => 'label'));
        $buffer .= "13";
        $buffer .= $this->html->fechaTag('span');
        $buffer .= $this->html->abreTagFechada('i', array('class' => 'nav-icon fa fa-envelope'));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'small-screen-text')) . "Mensagens" . $this->html->fechaTag('span');

        $buffer .= $this->html->fechaTag('a');

        $buffer .= $this->html->abreTagAberta('script');
        $buffer .= 'init.push(function () {$(\'#main-navbar-messages\').slimScroll({ height: 250 });});';
        $buffer .= $this->html->fechaTag('script');

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'dropdown-menu widget-messages-alt no-padding', 'style' => 'width: 300px'));

        $buffer .= $this->html->abreTagAberta('div', array('id' => 'main-navbar-messages', 'class' => 'messages-list'));

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'message'));

        $buffer .= $this->html->abreTagAberta('img', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/demo/avatars/3.jpg', 'class' => 'message-avatar'));
        $buffer .= $this->html->abreTagAberta('a', array('class' => 'message-subject')) . 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' . $this->html->fechaTag('a');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'message-description')) . 'from ' . $this->html->abreTagAberta('a', array('href' => '#')) . 'Vinícius Pozzebon' . $this->html->fechaTag('a') . ' há 2h' . $this->html->fechaTag('div');

        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'class' => 'messages-link')) . 'VER MAIS MENSAGENS' . $this->html->fechaTag('a');

        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->fechaTag('li');

        return $buffer;

	}

}