<?php

namespace Pixel\Template\BarraEsquerda;

class BlocoUsuario extends \Zion\Layout\Padrao
{

    public function getBlocoUsuario()
    {

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'menu-content-demo', 'class' => 'menu-content top'));

        $buffer .= $this->html->abreTagAberta('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'text-bg'));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'text-slim')) . $_SESSION['pessoaFisicaNome'] . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('img', array('src' => SIS_URL_BASE_STATIC . 'PixelAdmin/1.3.0/assets/demo/avatars/1.jpg'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'btn-group'));
        // envelope
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE . './Accounts/Message', 'class' => 'btn btn-xs btn-primary btn-outline dark'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-envelope')) . $this->html->fechaTag('i');
        $buffer .= $this->html->fechaTag('a');
        // perfil
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE . './Accounts/User', 'class' => 'btn btn-xs btn-primary btn-outline dark'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-user')) . $this->html->fechaTag('i');
        $buffer .= $this->html->fechaTag('a');
        // configuracoes
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE . './Accounts/Config', 'class' => 'btn btn-xs btn-primary btn-outline dark'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-cog')) . $this->html->fechaTag('i');
        $buffer .= $this->html->fechaTag('a');
        // sair
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE . './Accounts/Logoff', 'class' => 'btn btn-xs btn-danger btn-outline dark'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-power-off')) . $this->html->fechaTag('i');
        $buffer .= $this->html->fechaTag('a');

        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

        // end: menu-content-demo
        $buffer .= $this->html->fechaTag('div');
        return $buffer;
    }

}
