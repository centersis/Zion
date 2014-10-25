<?php

namespace Pixel\Template;

class Template extends \Zion\Layout\Padrao
{

    private $conteudoHeader;
    private $conteudoBody;
    private $conteudoBotoes;
    private $conteudoGrid;
    private $conteudoMain;
    private $conteudoContainerLogin;
    private $conteudoScripts;
    private $conteudoFooter;
    private $tooltipForm;

    public function setConteudoHeader($conteudo = '')
    {

        $this->conteudoHeader .= $conteudo;
    }

    public function setConteudoBody($conteudo = '')
    {

        $this->conteudoBody .= $conteudo;
    }

    public function setConteudoIdContainer($conteudo = '')
    {

        $this->idContainer .= $conteudo;
    }

    public function setConteudoBotoes($conteudo = '')
    {

        $this->conteudoBotoes .= $conteudo;
    }

    public function setConteudoGrid($conteudo = '')
    {

        $this->conteudoGrid .= $conteudo;
    }

    public function setConteudoMain($conteudo = '')
    {

        $this->conteudoMain .= $conteudo;
    }

    public function setConteudoContainerLogin($conteudo = '')
    {

        $this->conteudoContainerLogin .= $conteudo;
    }

    public function setConteudoScripts($conteudo = '')
    {

        $this->conteudoScripts .= $conteudo;
    }

    public function setConteudoFooter($conteudo = '')
    {

        $this->conteudoFooter .= $conteudo;
    }

    public function getConteudoFooter()
    {

        return $this->conteudoFooter;
    }

    public function setTooltipForm($Form = 'sisContainer')
    {
        return $this->tooltipForm = $Form;
    }      

    public function getTemplate($modo = '')
    {

        switch ($modo) {

            case 'cabecalho':

                return $this->getCabecalho();


            case 'inicioCorpo':

                return $this->getInicioCorpo();

            case 'inicioContainer':

                return $this->getInicioContainer();


            case 'barraSuperior':

                return $this->getBarraSuperior();


            case 'barraLateral':

                return $this->getBarraLateral();

            case 'main':

                return $this->getMain();


            case 'fimContainer':

                return $this->getFimContainer();


            case 'fimCorpo':

                return $this->getFimCorpo();


            case 'rodape':

                return $this->getRodape();


            case 'containerLogin':

                return $this->getContainerLogin();


            default:

                $buffer = '';
                $buffer .= $this->getEstatisticas('starts');
                $buffer .= $this->getCabecalho();
                $buffer .= $this->getInicioCorpo();
                $buffer .= $this->getInicioContainer();
                $buffer .= $this->getBarraSuperior();
                $buffer .= $this->getBarraLateral();
                $buffer .= $this->getMain();
                $buffer .= $this->getFimContainer();
                $buffer .= $this->getFimCorpo();
                $buffer .= $this->getRodape();
                $buffer .= $this->getEstatisticas('ends');

                return $buffer;
        }
    }

    private function getCabecalho()
    {

        $cabecalho = new \Pixel\Template\Cabecalho();
        return $cabecalho->getCabecalho();
    }

    private function getInicioCorpo()
    {

        $classCss = (!empty($this->conteudoBody)) ? $this->conteudoBody : 'theme-default main-menu-animated';

        $buffer = '';
        $buffer .= $this->html->abreComentario() . 'Zion Framework: starting body app' . $this->html->fechaComentario();
        $buffer .= $this->html->abreTagAberta('body', array('class' => $classCss));
        $buffer .= $this->html->entreTags('script', 'var init = [];');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/demo/demo.js')) . $this->html->fechaTag('script');
        //$buffer .= $this->html->abreTagAberta('div', array('id' => 'main-wrapper'));        

        return $buffer;
    }

    private function getInicioContainer()
    {

        $idContainer = (!empty($this->idContainer)) ? $this->idContainer : 'main-wrapper';

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('div', array('id' => $idContainer));
        return $buffer;
    }

    private function getBarraSuperior()
    {

        $menu = new \Pixel\Template\BarraSuperior\Menu();
        $formPesquisar = new \Pixel\Template\BarraSuperior\FormPesquisar();
        $notificacoes = new \Pixel\Template\BarraSuperior\Notificacoes();
        $mensagens = new \Pixel\Template\BarraSuperior\Mensagens();
        $usuario = new \Pixel\Template\BarraSuperior\Usuario();

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'main-navbar', 'class' => 'navbar navbar-inverse', 'role' => 'navigation'));

        $buffer .= $this->html->abreTagAberta('button', array('id' => 'main-menu-toggle'));
        $buffer .= $this->html->abreTagFechada('i', array('class' => 'navbar-icon fa fa-bars icon'));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'hide-menu-text')) . 'ESCONDER MENU' . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('button');

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'navbar-inner'));

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'navbar-header'));

        // carrega o logo do sistema na barra superior
        $buffer .= $this->getLogoSuperior();

        $buffer .= $this->html->abreTagAberta('button', array('type' => 'button', 'class' => 'navbar-toggle collapsed', 'data-toggle' => 'collapse', 'data-target' => '#main-navbar-collapse'));
        $buffer .= $this->html->abreTagFechada('i', array('class' => 'navbar-icon fa fa-bars'));
        // end: button
        $buffer .= $this->html->fechaTag('button');

        // end: navbar-header
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('div', array('id' => 'main-navbar-collapse', 'class' => 'collapse navbar-collapse main-navbar-collapse'));

        $buffer .= $this->html->abreTagAberta('div');

        // carrega o menu da barra superior
        $buffer .= $menu->getMenu();

        $buffer .= $this->html->abreTagAberta('div', array('id' => 'icone-notificacoes', 'class' => 'right clearfix'));

        $buffer .= $this->html->abreTagAberta('ul', array('class' => 'nav navbar-nav pull-right right-navbar-nav'));

            $buffer .= $formPesquisar->getFormPesquisar();
            $buffer .= $notificacoes->getNotificacoes();
            $buffer .= $mensagens->getMensagens();
            $buffer .= $usuario->getUsuario();

        // end: navbar-nav
        $buffer .= $this->html->fechaTag('ul');

        // end: icone-notificacoes
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->fechaTag('div');

        // end: main-navbar-collapse
        $buffer .= $this->html->fechaTag('div');

        // end: navbar-inner
        $buffer .= $this->html->fechaTag('div');

        // end: main-navbar
        $buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

    private function getLogoSuperior()
    {

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE . 'Dashboard', 'class' => 'navbar-brand'));

        $buffer .= $this->html->abreTagAberta('div');
        $buffer .= $this->html->abreTagAberta('img', array('alt' => 'Início', 'src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/images/pixel-admin/main-navbar-logo.png'));
        $buffer .= $this->html->fechaTag('div');
        $buffer .= SIS_ID_NAMESPACE_PROJETO;

        // end: navbar-brand
        $buffer .= $this->html->fechaTag('a');

        return $buffer;
    }

    private function getBarraLateral()
    {

        $blocoUsuario = new \Pixel\Template\BarraEsquerda\BlocoUsuario();
        $menu = new \Pixel\Template\BarraEsquerda\Menu();

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'main-menu', 'role' => 'navigation'));
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'main-menu-inner'));
        $buffer .= $blocoUsuario->getBlocoUsuario();
        $buffer .= $menu->getMenu();
        // end: main-menu-inner
        $buffer .= $this->html->fechaTag('div');
        // end: main-menu
        $buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

    private function getAlerts()
    {

        $buffer = '';
        $buffer .= $this->html->entreTags('script', 'init.push(function(){$(\'#page-alerts-dark-demo\').on(\'click\',\'button\',function(){var e=$(this);$(\'html,body\').animate({scrollTop:0},500);setTimeout(function(){if(e.hasClass(\'page-alerts-clear-btn\')){PixelAdmin.plugins.alerts.clear(true,\'pa_page_alerts_dark\')}else{var t={type:e.attr(\'data-type\'),namespace:\'pa_page_alerts_dark\',classes:\'alert-dark\'};if(e.hasClass(\'auto-close-alert\'))t[\'auto_close\']=5;PixelAdmin.plugins.alerts.add(e.attr(\'data-text\'),t)}},800)})});');
        return $buffer;
    }

    private function getMain()
    {

        $breadCrumb = new \Pixel\Template\Main\BreadCrumb();
        $modal = new \Pixel\Template\Main\Modal();

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'content-wrapper'));
        $buffer .= $breadCrumb->getBreadCrumb();
        $buffer .= $this->getPageHeader();
        $buffer .= $this->conteudoBotoes;
        $buffer .= $this->abreTagAberta('div', ['id' => 'sisContainerGrid']) . $this->conteudoGrid . $this->fechaTag('div');
        $buffer .= $this->conteudoMain;
        $buffer .= $modal->getModal();

        // end: content-wrapper
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'main-menu-bg')) . $this->html->fechaTag('div');
        // end: main-navbar
        $buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

    private function getContainerLogin()
    {

        $login = new \Pixel\Template\Login();
        return $login->getLogin();
    }

    private function getPageHeader()
    {

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'page-header'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'row'));

        $buffer .= $this->html->abreTagAberta('h1', array('class' => 'col-xs-12 col-sm-4 text-center text-left-sm'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-dashboard page-header-icon')) . $this->html->fechaTag('i') . '&nbsp;&nbsp;' . DEFAULT_MODULO_NOME;
        $buffer .= $this->html->fechaTag('h1');

        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

    private function getFimContainer()
    {

        $buffer = '';
        $buffer .= $this->html->fechaTag('div');
        return $buffer;
    }

    private function getFimCorpo()
    {

        $buffer = '';
        // end: main-wrapper
        //$buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreComentario() . 'Zion Framework: ending body app' . $this->html->fechaComentario();

        return $buffer;
    }

    public function getScripts()
    {

        $buffer = '';
        $buffer .= $this->html->abreComentario() . 'Zion Framework: starting runtime dynamic form scripts block' . $this->html->fechaComentario();
        //$buffer .= $this->html->abreTagAberta('script', array('type' => 'text/javascript'));
        $buffer .= $this->conteudoScripts;
        //$buffer .= $this->html->fechaTag('script');
        $buffer .= $this->html->abreComentario() . 'Zion Framework: ending runtime dynamic form scripts block' . $this->html->fechaComentario();
        //$buffer .= $this->conteudoScripts;

        return $buffer;
    }

    public function getTooltipForm()
    {

        $buffer  = '';
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/jquery-ui-extras.min.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->entreTags('script', 'var initTooltipsDemo=function(){if(window.JQUERY_UI_EXTRAS_LOADED){$(\'#' . $this->tooltipForm . '\').tooltip()}};init.push(initTooltipsDemo);');
        return $buffer;

    } 

    private function getRodape()
    {

        $rodape = new \Pixel\Template\Rodape();
        return $rodape->getRodape($this);
    }

    private function getEstatisticas($modo = '')
    {

        $buffer = '';

        switch ($modo) {
            case 'starts':

                list($usec, $sec) = explode(' ', microtime());
                $_SESSION['script_start'] = (float) $sec + (float) $usec;

                $buffer .= $this->html->abreComentario() . 'Zion Framework starting at [' . $_SESSION['script_start'] . '] miliseconds' . $this->html->fechaComentario();
                $buffer .= $this->html->abreComentario() . 'Zion Framework memory peak usage [' . round(((memory_get_peak_usage(true) / 1024) / 1024), 2) . '] Mb ' . $this->html->fechaComentario();

                break;

            case 'ends':

                list($usec, $sec) = explode(' ', microtime());
                $script_end = (float) $sec + (float) $usec;
                $elapsed_time = round($script_end - $_SESSION['script_start'], 5);

                $buffer .= $this->html->abreComentario() . 'Zion Framework ending at [' . $elapsed_time . '] miliseconds' . $this->html->fechaComentario();
                $buffer .= $this->html->abreComentario() . 'Zion Framework memory peak usage [' . round(((memory_get_peak_usage(true) / 1024) / 1024), 2) . '] Mb ' . $this->html->fechaComentario();

                break;
        }


        return $buffer;
    }

}
