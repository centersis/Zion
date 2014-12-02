<?php

namespace Pixel\Template\BarraSuperior;

class FormPesquisar extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Form
     */
    public function getFormPesquisar()
    {	

	    //$formSmart = new \Sappiens\includes\SelecionarClienteForm();
	    //$form = $formSmart->getFormModulo();

	    $buffer  = '';
	    $buffer .= $this->html->abreTagAberta('li');
	    $buffer .= $this->html->abreTagAberta('form', array('class' => 'navbar-form'));
	    //$buffer .= $form->getFormHtml('v_cliente');
	    $buffer .= $this->html->fechaTag('form');
	    $buffer .= $this->html->fechaTag('li');

	    return $buffer;	

	}

}