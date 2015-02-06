<?php
/**
*
*    Sappiens Framework
*    Copyright (C) 2014, BRA Consultoria
*
*    Website do autor: www.braconsultoria.com.br/sappiens
*    Email do autor: sappiens@braconsultoria.com.br
*
*    Website do projeto, equipe e documentação: www.sappiens.com.br
*   
*    Este programa é software livre; você pode redistribuí-lo e/ou
*    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
*    publicada pela Free Software Foundation, versão 2.
*
*    Este programa é distribuído na expectativa de ser útil, mas SEM
*    QUALQUER GARANTIA; sem mesmo a garantia implícita de
*    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
*    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
*    detalhes.
* 
*    Você deve ter recebido uma cópia da Licença Pública Geral GNU
*    junto com este programa; se não, escreva para a Free Software
*    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
*    02111-1307, USA.
*
*    Cópias da licença disponíveis em /Sappiens/_doc/licenca
*
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

            $template->setConteudoBody('theme-default page-signin');
            $template->setConteudoScripts($html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/login-postscripts.js')) . $html->fechaTag('script'));    

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
        $l = \filter_input(INPUT_POST, 'email');
        $p = \filter_input(INPUT_POST, 'signin_password');

        $html = new \Zion\Layout\Html();
        $template = new \Pixel\Template\Template();

        $template->setConteudoBody('theme-default page-signin');
        $retorno  = $template->getTemplate('cabecalho');
        $retorno .= $template->getTemplate('inicioCorpo');
        $template->setConteudoScripts($html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/login-postscripts.js')) . $html->fechaTag('script'));

        try{

            if($this->loginClass->getAuth($l,$p)) {
    
                header('location: ' . SIS_URL_BASE . 'Dashboard');
    
            } else {

                $retorno .= $template->setConteudoLogin($this->loginForm->getLogin("Oops! Dados incorretos..."));
    
            }

        } catch(\Exception $e){
            $retorno .= $template->setConteudoLogin($this->loginForm->getLogin($e->getMessage()));
        }
        
        $retorno .= $template->getTemplate('fimCorpo');
        $retorno .= $template->getTemplate('rodape');

        return $retorno;
    }

    protected function recovery()
    {
        $l = \filter_input(INPUT_POST, 'email');
        $html = new \Zion\Layout\Html();
        $template = new \Pixel\Template\Template();

        $template->setConteudoBody('theme-default page-signin');
        $retorno  = $template->getTemplate('cabecalho');
        $retorno .= $template->getTemplate('inicioCorpo');
        $template->setConteudoScripts($html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/login-postscripts.js')) . $html->fechaTag('script'));

        try{

            if($this->loginClass->recovery($l) === true){
                $mensagem = 'Um email com instruções para a redefinição da sua senha foi enviado para <span class="negrito">'. $l .'</span>.<br /><br />';
                $retorno .= $template->setConteudoLogin($this->loginForm->getFormLimpo('Redefinir Senha', $mensagem));
            }

        } catch(\Exception $e){
            $retorno .= $template->setConteudoLogin($this->loginForm->getLogin($e->getMessage()));
        }

        $retorno .= $template->getTemplate('fimCorpo');
        $retorno .= $template->getTemplate('rodape');

        return $retorno;

    }
    
    protected function recoverPass()
    {

        $email  = \filter_input(INPUT_GET, 'email');
        $hash   = \filter_input(INPUT_GET, 'hash');

        $html = new \Zion\Layout\Html();
        $template = new \Pixel\Template\Template();

        $template->setConteudoBody('theme-default page-signin');
        $retorno  = $template->getTemplate('cabecalho');
        $retorno .= $template->getTemplate('inicioCorpo');
        $template->setConteudoScripts($html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/login-postscripts.js')) . $html->fechaTag('script'));

        try {

            if($this->loginClass->validaHash($email, $hash) === false){ print "ASD";
                header("location: ". SIS_URL_BASE .'');
            }

            $retorno .= $template->setConteudoLogin($this->loginForm->formRecovery());

        } catch(\Exception $e){
            $retorno .= $template->setConteudoLogin($this->loginForm->formRecovery($e->getMessage()));
        }
        
        $retorno .= $template->getTemplate('fimCorpo');
        $retorno .= $template->getTemplate('rodape');

        return $retorno;

    }

    protected function setNewPass()
    {

        $email  = \filter_input(INPUT_POST, 'email');
        $hash   = \filter_input(INPUT_POST, 'hash');
        
        $senha  = \filter_input(INPUT_POST, 'new_password');
        $senhaB = \filter_input(INPUT_POST, 'new_password_b');

        $html = new \Zion\Layout\Html();
        $template = new \Pixel\Template\Template();

        $template->setConteudoBody('theme-default page-signin');
        $retorno  = $template->getTemplate('cabecalho');
        $retorno .= $template->getTemplate('inicioCorpo');
        $template->setConteudoScripts($html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/login-postscripts.js')) . $html->fechaTag('script'));

        try {

            if($this->loginClass->validaHash($email, $hash) === false){
                header("location: ". SIS_URL_BASE .'');
            }

            if($this->loginClass->setNewPass($email, $hash, $senha, $senhaB) === true){
                $mensagem = 'Sua senha foi redefinida com sucesso. Para continuar, clique no link abaixo. <br /><br />';
                $retorno .= $template->setConteudoLogin($this->loginForm->getFormLimpo('Redefinir Senha', $mensagem));
            }

        } catch(\Exception $e){
            $retorno .= $template->setConteudoLogin($this->loginForm->formRecovery($e->getMessage()));
        }
        
        $retorno .= $template->getTemplate('fimCorpo');
        $retorno .= $template->getTemplate('rodape');

        return $retorno;

    }

}
