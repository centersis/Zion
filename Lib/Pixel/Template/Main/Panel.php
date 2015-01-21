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

namespace Pixel\Template\Main;

class Panel extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Panel
     */
    public function getPanel($panelId = '', $panelTitle = '', $panelBody = '', $opcoes = '')
    {	

        $titleVisible = \key_exists('titleVisible',$opcoes) ? $opcoes['titleVisible'] : '';
        $startVisible = \key_exists('startVisible',$opcoes) ? $opcoes['startVisible'] : '';        
        //$iconTitle    = \key_exists('iconTitle',$opcoes) and !empty($opcoes['iconTitle']) ? $opcoes['iconTitle'] : '';

        $titleHidden = (!$titleVisible) ? ' showHidden ' : false;
        $bodyHidden  = ($titleVisible) ? ' showHidden ' : false;

        $titleStartVisible  = '';
        $bodyStartVisible   = '';

        if(!$startVisible and !$titleVisible) {
            $titleStartVisible = ' hidden ';
        } elseif(!$startVisible and $titleVisible) {
            $bodyStartVisible = ' hidden ';
        }

        $buffer  = '';
        $style = \key_exists('style',$opcoes) ? $opcoes['style'] : '';
        
        $buffer .= $this->html->abreTagAberta('div', ['id' => $panelId, 'class' => 'panel panel-default ' . $titleStartVisible . $titleHidden, 'style' => $style]);
            //$buffer .= $this->html->abreTagAberta('div', ['class' => 'panel-heading hand', 'onclick' => 'showHiddenFilters()']);
/*
                $buffer .= $this->html->abreTagAberta('span', ['class' => 'panel-title']);
                $buffer .= $this->html->abreTagFechada('i', ['class' => 'panel-title-icon '.$iconTitle]);
                $buffer .= $panelTitle;
                $buffer .= $this->html->fechaTag('span');
*/
            //$buffer .= $this->html->abreTagAberta('div', ['class' => 'panel-heading-controls']); 
                //$buffer .= $this->html->abreTagAberta('div', ['class' => 'panel-heading-icon']);
                    //$buffer .= $this->html->abreTagFechada('i', ['class' => 'fa fa-close hand', 'onclick' => 'showHiddenFilters()', 'title' => 'Fechar']);
                //$buffer .= $this->html->fechaTag('div');
            //$buffer .= $this->html->fechaTag('div');

            //$buffer .= $this->html->fechaTag('div');
            $buffer .= $this->html->abreTagAberta('div', ['id' => $panelId.'-body', 'class' => 'panel-body' . $bodyHidden . $bodyStartVisible]); 
            $buffer .= $panelBody;
            $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        return $buffer;

	}

}