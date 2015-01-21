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