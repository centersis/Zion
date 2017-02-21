<?php

namespace Pixel\Template;

class Rodape extends \Zion\Layout\Padrao
{

    public function getRodape($template)
    {

        $buffer = '';
        $buffer .= $template->getConteudoFooter();
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/bootstrap.min.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . 'libs/jquery/plugins/jquery-maskMoney/3.0.2/jquery.maskMoney.min.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/pixel-admin.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('type' => 'text/javascript')) . 'window.PixelAdmin.start(init);' . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . 'libs/jquery/plugins/bootstrap-tags/bootstrap-tagsinput.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_FM_BASE . 'Pixel/PixelPadrao.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_FM_BASE . 'Ckeditor/ckeditor.js')) . $this->html->fechaTag('script');
        $buffer .= $template->getTooltipForm();
        $buffer .= $template->getScripts();
        $buffer .= $this->rodape();
        $buffer .= $this->html->abreComentario() . 'Sappiens Framework: good by!' . $this->html->fechaComentario();
        return $buffer;
    }

}
