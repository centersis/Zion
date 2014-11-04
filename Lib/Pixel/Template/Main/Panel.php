<?php

namespace Pixel\Template\Main;

class Panel extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Panel
     */
    public function getPanel($panelId = '', $panelTitle = '', $panelBody = '', $opcoes = '')
    {	

        $titleVisible = @$opcoes['titleVisible'];
        $startVisible = @$opcoes['startVisible'];
        $iconTitle    = empty($opcoes['iconTitle']) ? '' : $opcoes['iconTitle'];

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
        $buffer .= $this->html->abreTagAberta('div', ['id' => $panelId, 'class' => 'panel panel-default ' . $titleStartVisible . $titleHidden, 'style' => @$opcoes['style']]);
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