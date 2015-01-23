<?php
/**
*
*    Sappiens Framework
*    Copyright (C) 2014, BRA Consultoria
*
*    Website do autor: www.braconsultoria.com.br/sappiens
*    Email do autor: sappiens@braconsultoria.com.br
*
*    Website do projeto, equipe e documentação: www.sappiens.com.br
*   
*    Este programa é software livre; você pode redistribuí-lo e/ou
*    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
*    publicada pela Free Software Foundation, versão 2.
*
*    Este programa é distribuído na expectativa de ser útil, mas SEM
*    QUALQUER GARANTIA; sem mesmo a garantia implícita de
*    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
*    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
*    detalhes.
* 
*    Você deve ter recebido uma cópia da Licença Pública Geral GNU
*    junto com este programa; se não, escreva para a Free Software
*    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
*    02111-1307, USA.
*
*    Cópias da licença disponíveis em /Sappiens/_doc/licenca
*
*/

namespace Pixel\Template\BarraSuperior;

class Notificacoes extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Notificacoes
     */
    public function getNotificacoes()
    {	

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('li', array('class' => 'nav-icon-btn nav-icon-btn-danger dropdown'));

        $buffer .= $this->html->abreTagAberta('a', array('href' => '#notifications', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'));

        $buffer .= $this->html->abreTagAberta('span', array('class' => 'label'));
        $buffer .= "6";
        $buffer .= $this->html->fechaTag('span');
        $buffer .= $this->html->abreTagFechada('i', array('class' => 'nav-icon fa fa-bullhorn'));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'small-screen-text'));
        $buffer .= "Notificações";
        $buffer .= $this->html->fechaTag('span');

        $buffer .= $this->html->fechaTag('a');

        $buffer .= $this->html->abreTagAberta('script');
        $buffer .= 'init.push(function () {$(\'#main-navbar-notifications\').slimScroll({ height: 250 });});';
        $buffer .= $this->html->fechaTag('script');

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'dropdown-menu widget-notifications no-padding', 'style' => 'width: 300px'));

        $buffer .= $this->html->abreTagAberta('div', array('id' => 'main-navbar-notifications', 'class' => 'notifications-list'));

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification'));

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification-title text-danger')) . 'SYSTEM' . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification-description')) . '<strong>Error 500</strong>: Syntax error in index.php at line <strong>461</strong>.' . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification-ago')) . '12h atrás' . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification-icon fa fa-hdd-o bg-danger')) . $this->html->fechaTag('div');

        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification'));

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification-title text-info')) . 'SYSTEM' . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification-description')) . '<strong>Error 500</strong>: Syntax error in index.php at line <strong>461</strong>.' . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification-ago')) . '12h atrás' . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification-icon fa fa-hdd-o bg-info')) . $this->html->fechaTag('div');

        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'class' => 'notifications-link')) . 'VER MAIS NOTIFICAÇÕES' . $this->html->fechaTag('a');

        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->fechaTag('li');

        return $buffer;

	}

}