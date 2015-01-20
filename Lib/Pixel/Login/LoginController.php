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
