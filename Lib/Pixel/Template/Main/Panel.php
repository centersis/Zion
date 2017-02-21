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

        $titleVisible = \key_exists('titleVisible', $opcoes) ? $opcoes['titleVisible'] : '';
        $startVisible = \key_exists('startVisible', $opcoes) ? $opcoes['startVisible'] : '';

        $titleHidden = (!$titleVisible) ? ' showHidden ' : false;
        $bodyHidden = ($titleVisible) ? ' showHidden ' : false;

        $titleStartVisible = '';
        $bodyStartVisible = '';

        if (!$startVisible and ! $titleVisible) {
            $titleStartVisible = ' hidden ';
        } elseif (!$startVisible and $titleVisible) {
            $bodyStartVisible = ' hidden ';
        }

        $buffer = '';
        $style = \key_exists('style', $opcoes) ? $opcoes['style'] : '';

        $buffer .= $this->html->abreTagAberta('div', ['id' => $panelId, 'class' => 'panel panel-default ' . $titleStartVisible . $titleHidden, 'style' => $style]);
        $buffer .= $this->html->abreTagAberta('div', ['id' => $panelId . '-body', 'class' => 'panel-body' . $bodyHidden . $bodyStartVisible]);
        $buffer .= $panelBody;
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        return $buffer;
    }

}
