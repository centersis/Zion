<?php
/*

    Sappiens Framework
    Copyright (C) 2014, BRA Consultoria

    Website do autor: www.braconsultoria.com.br/sappiens
    Email do autor: sappiens@braconsultoria.com.br

    Website do projeto, equipe e documentação: www.sappiens.com.br
   
    Este programa é software livre; você pode redistribuí-lo e/ou
    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
    publicada pela Free Software Foundation, versão 2.

    Este programa é distribuído na expectativa de ser útil, mas SEM
    QUALQUER GARANTIA; sem mesmo a garantia implícita de
    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
    detalhes.
 
    Você deve ter recebido uma cópia da Licença Pública Geral GNU
    junto com este programa; se não, escreva para a Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
    02111-1307, USA.

    Cópias da licença disponíveis em /Sappiens/_doc/licenca

*/

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