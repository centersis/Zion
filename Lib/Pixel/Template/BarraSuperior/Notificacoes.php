<?php

namespace Pixel\Template\BarraSuperior;

use Pixel\Notificacao\Notificacao;
use Zion\Tratamento\Data;
use Pixel\Form\FormSocket;

class Notificacoes extends \Zion\Layout\Padrao
{

    private $notificacao;
    private $tData;

    public function __construct()
    {
        $this->notificacao = new Notificacao();
        $this->tData = Data::instancia();
        parent::__construct();
    }

    /**
     * 
     * @return Notificacoes
     */
    public function getNotificacoes()
    {

        $wsConfig = ['nome' => 'Notificacoes',
            'pesquisa' => '{"metodo"    : "getNotificacoes"}',
            'evento' => '$(document).ready(function(){',
            'callback' => "return sisAddNotificacao(retorno);"
        ];

        $buffer = (new FormSocket($wsConfig))->processar();
        $buffer .= $this->html->abreTagAberta('li', array('class' => 'nav-icon-btn nav-icon-btn-danger dropdown'));

        $buffer .= $this->html->abreTagAberta('a', array('href' => '#notifications', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'id' => 'notificationsMain'));

        $buffer .= $this->html->abreTagAberta('span', array('class' => 'label', 'id' => 'notificationsNumber'));
        $buffer .= "";
        $buffer .= $this->html->fechaTag('span');
        $buffer .= $this->html->abreTagFechada('i', array('class' => 'nav-icon fa fa-bullhorn'));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'small-screen-text'));
        $buffer .= "Notificações";
        $buffer .= $this->html->fechaTag('span');

        $buffer .= $this->html->fechaTag('a');

        $buffer .= $this->html->abreTagAberta('script');
        $buffer .= 'init.push(function () {$(\'#main-navbar-notifications\').slimScroll({ height: 200});}); var old = $(".slimScrollDiv").attr("style"); $(".slimScrollDiv").attr("style");';
        $buffer .= $this->html->fechaTag('script');

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'dropdown-menu widget-notifications no-padding', 'style' => 'width: 300px'));

        $buffer .= $this->html->abreTagAberta('div', array('id' => 'main-navbar-notifications', 'class' => 'notifications-list'));

        $buffer.= $this->getNotificacoesConteudo($_SESSION['usuarioCod']);

        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'class' => 'notifications-link')) . 'VER MAIS NOTIFICAÇÕES' . $this->html->fechaTag('a');

        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->fechaTag('li');

        return $buffer;

    }

    public function getNotificacoesConteudo($usuarioCod)
    {

        $notificacoes = $this->notificacao->getUltimasNotificacoes($usuarioCod);

        $buffer = '';

        foreach ($notificacoes as $notificacao) {

            $status = ($notificacao['notificacaolida'] === "S" ? '#F0F0F0' : '#FFFFFF');
            $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification', "onclick" => "acessaNotificacao(this.id, this.attributes['url']['value'])", 'style' => 'background-color: ' . $status, 'id' => $notificacao['notificacaocod'], 'url' => $notificacao['notificacaolink']));
            $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification-title text')) . $notificacao['notificacaotitulo'] . $this->html->fechaTag('div');
            $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification-description')) . '<strong>' . $notificacao['notificacaodesc'] . '</strong>' . $this->html->fechaTag('div');
            $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification-ago')) . $this->tData->getTimeAgo($notificacao['notificacaodatahora']) . $this->html->fechaTag('div');
            $buffer .= $this->html->abreTagAberta('div', array('class' => 'notification-icon fa fa-hdd-o bg')) . $this->html->fechaTag('div');
            $buffer .= $this->html->fechaTag('div');
        }

        return $buffer;
    }

}
