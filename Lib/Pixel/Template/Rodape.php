<?php

namespace Pixel\Template;

class Rodape extends \Zion\Layout\Padrao
{

    public function getRodape($template)
    {

        $buffer = '';
        $buffer .= $template->getConteudoFooter();
        $buffer .= $this->html->abreComentario() . 'Zion Framework: starting scripts block' . $this->html->fechaComentario();
        //$buffer .= $this->html->abreTagAberta('script', array('src' => '//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . 'libs/jquery/2.0.3/jquery.min.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/bootstrap.min.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . 'libs/jquery/plugins/jquery-maskMoney/3.0.2/jquery.maskMoney.min.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/pixel-admin.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('type' => 'text/javascript')) . 'window.PixelAdmin.start(init);' . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . 'libs/jquery/plugins/bootstrap-tags/bootstrap-tagsinput.js')) . $this->html->fechaTag('script');
        //$buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . 'libs/jquery/plugins/bootstrap-tags/bootstrap-tagsinput.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->tooltipForm;
        $buffer .= $template->getScripts();
        $buffer .= $this->html->abreComentario() . 'Zion Framework: ending scripts block' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Zion Framework: starting generic footer' . $this->html->fechaComentario();
        $buffer .= $this->rodape();
        $buffer .= $this->html->abreComentario() . 'Zion Framework: ending generic footer' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Zion Framework: good by!' . $this->html->fechaComentario();
        return $buffer;

    }

}