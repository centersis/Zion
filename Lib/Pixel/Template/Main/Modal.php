<?php

namespace Pixel\Template\Main;

class Modal extends \Zion\Layout\Padrao
{

    public function getModal()
    {

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'modal-msg', 'class' => 'modal modal-alert fade modal-danger', 'aria-hidden' => 'false', 'style' => 'display:none;'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'modal-dialog'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'modal-content'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'modal-header'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-times-circle')) . $this->html->fechaTag('i');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'modal-titulo', 'class' => 'modal-title')) . ' Servidor muito ocupado! ' . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'modal-descricao', 'class' => 'modal-body')) . ' Não foi possível processar a sua requisição neste momento. Tente novamente mais tarde... ' . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'modal-footer'));
        $buffer .= $this->html->abreTagAberta('button', array('type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => 'modal')) . ' OK ' . $this->html->fechaTag('button');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

}
