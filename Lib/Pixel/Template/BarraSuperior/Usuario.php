<?php

namespace Pixel\Template\BarraSuperior;

class Usuario extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Notificacoes
     */
    public function getUsuario()
    {	

        $buffer  = '';
        $buffer .= $this->html->abreTagAberta('li', ['class' => 'dropdown']);        
            $buffer .= $this->html->abreTagAberta('a', ['href' => '#user', 'class' => 'dropdown-toggle user-menu', 'data-toggle' => 'dropdown']);
                $buffer .= $this->html->abreTagAberta('img', ['src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/demo/avatars/1.jpg']);
                $buffer .= $this->html->abreTagAberta('span') . ' Vinícius Pozzebon ' . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
            $buffer .= $this->html->abreTagAberta('ul', ['class' => 'dropdown-menu']);  

                $buffer .= $this->html->abreTagAberta('li');  
                    $buffer .= $this->html->abreTagAberta('a', ['href' => '#']);
                    $buffer .= $this->html->abreTagAberta('span', ['class' => 'label label-warning pull-right']) . 'Label' . $this->html->fechaTag('span') . ' Perfil ';
                    $buffer .= $this->html->fechaTag('a');
                $buffer .= $this->html->fechaTag('li');

                $buffer .= $this->html->abreTagAberta('li');  
                    $buffer .= $this->html->abreTagAberta('a', ['href' => '#']);
                    $buffer .= $this->html->abreTagAberta('i', ['class' => 'dropdown-icon fa fa-cog recD10px']) . $this->html->fechaTag('i') . ' Configurações ';
                    $buffer .= $this->html->fechaTag('a');
                $buffer .= $this->html->fechaTag('li');

                $buffer .= $this->html->abreTagAberta('li', ['class' => 'divider']) . $this->html->fechaTag('li');

                $buffer .= $this->html->abreTagAberta('li');  
                    $buffer .= $this->html->abreTagAberta('a', ['href' => SIS_URL_BASE . 'Accounts/Logoff']);
                    $buffer .= $this->html->abreTagAberta('i', ['class' => 'dropdown-icon fa fa-power-off recD10px']) . $this->html->fechaTag('i') . 'Desconectar ';
                    $buffer .= $this->html->fechaTag('a');
                $buffer .= $this->html->fechaTag('li');

            $buffer .= $this->html->fechaTag('ul');
        $buffer .= $this->html->fechaTag('li');
        return $buffer;

	}

}