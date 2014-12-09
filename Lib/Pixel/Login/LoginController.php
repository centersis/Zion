<?php

namespace Pixel\Login;

class LoginController extends \Zion\Core\Controller
{

    private $loginClass;
    private $loginForm;

    public function __construct()
    {
        $this->loginClass = new \Pixel\Login\LoginClass();
        $this->loginForm = new \Pixel\Login\LoginForm();
    }  

    protected function iniciar()
    {

        $retorno = '';

        try {

            $html = new \Zion\Layout\Html();
            $template = new \Pixel\Template\Template();

            //$template->setConteudoHeader($html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/login.css', 'rel' => 'stylesheet', 'type' => 'text/css')));
            $template->setConteudoBody('theme-default page-signin');
//            $template->setConteudoScripts($html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/login-prescripts.js')) . $html->fechaTag('script'));
            $template->setConteudoScripts($html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/login-postscripts.js')) . $html->fechaTag('script'));    
            //$template->setConteudoFooter();              

            define('DEFAULT_GRUPO_NOME', 'Accounts');
            define('DEFAULT_MODULO_NOME', 'Login');
            define('DEFAULT_MODULO_URL', 'Login');

            $retorno .= $template->getTemplate('cabecalho');
            $retorno .= $template->getTemplate('inicioCorpo');
            $retorno .= $template->setConteudoLogin($this->loginForm->getLogin());
            $retorno .= $template->getTemplate('fimCorpo');
            $retorno .= $template->getTemplate('rodape');

        } catch (\Exception $ex) {
            
            $retorno = $ex;
        }

        return $retorno;
        
    }

    protected function getAuth()
    {

        $l = \filter_input(INPUT_POST, 'signin_username');
        $p = \filter_input(INPUT_POST, 'signin_password');

        if($this->loginClass->getAuth($l,$p)) {

            header('location: ' . SIS_URL_BASE . 'Dashboard');

        } else {

            header('location: ./?err=Oops! Dados incorretos...');

        }

    }

}
