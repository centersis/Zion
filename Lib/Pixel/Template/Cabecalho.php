<?php

namespace Pixel\Template;

class Cabecalho extends \Zion\Layout\Padrao
{

    public function getCabecalho()
    {

        $buffer = '';
        $buffer .= $this->html->abreComentario() . 'Zion Framework: starting generic header' . $this->html->fechaComentario();
        $buffer .= $this->topo();
        $buffer .= $this->html->abreComentario() . 'Zion Framework: ending generic header' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Zion Framework: starting template header' . $this->html->fechaComentario();
        $buffer .= $this->html->abreTagAberta('link', array('href' => '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/bootstrap.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/pixel-admin.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/widgets.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/pages.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/rtl.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/themes.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/fine-tuning.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= '<script data-pace-options=\'{ "restartOnRequestAfter": true }\' src="' . SIS_URL_BASE_STATIC . 'libs/jquery/plugins/jquery-pace/0.5.6/pace.min.js"></script>' . "\n";
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . 'libs/jquery/plugins/jquery-pace/0.5.6/pace.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . 'libs/jquery/plugins/bootstrap-tags/bootstrap-tagsinput.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        //$buffer .= $this->conteudoHeader;
        $buffer .= $this->html->fechaTag('head');
        $buffer .= $this->html->abreComentario() . 'Zion Framework: ending template header' . $this->html->fechaComentario();
        return $buffer;

    }

}