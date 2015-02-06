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

class LoginForm extends \Zion\Layout\Padrao
{

    public function getLogin($erroLogin = false)
    {

        $template = new \Pixel\Template\Template();

        $recovery = \filter_input(\INPUT_POST, 'recovery');
        $email    = \filter_input(\INPUT_POST, 'email');
        
        if(!empty($_GET['err'])){
            $erro = $_GET['err'];
        } else {
            $erro = " Acesse a sua conta";
        }
        
        $msgErro = ($erroLogin ? $erroLogin : $erro);
       
        $buffer  = '';
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/login.css', 'rel' => 'stylesheet', 'type' => 'text/css')) . $this->html->fechaTag('link');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/login-prescripts.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'page-signin-bg'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'overlay')) . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('img', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/demo/signin-bg-1.jpg'));
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-container'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-info'));
        $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'class' => 'logo'));
        $buffer .= $this->html->abreTagAberta('img', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/demo/logo-big.png', 'style' => 'margin-top: -5px;')) . '&nbsp;' . SIS_ID_NAMESPACE_PROJETO;
        $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'slogan')) . SIS_SLOGAN . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('ul');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-sitemap signin-icon')) . $this->html->fechaTag('i') . 'Estrutura modular flexível';
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-file-text-o signin-icon')) . $this->html->fechaTag('i') . 'HTML5, Ajax, CSS3 e SCSS';
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-outdent signin-icon')) . $this->html->fechaTag('i') . 'Suporte técnico integrado';
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-heart signin-icon')) . $this->html->fechaTag('i') . 'Desenvolvido com humor';
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->fechaTag('ul');
        // end: signin-info
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-form'));
        $buffer .= $this->html->abreTagAberta('form', array('id' => 'signin-form_id', 'action' => './', 'method' => 'post'));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'hidden', 'id' => 'acao', 'name' => 'acao', 'value' => 'getAuth'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-text'));
        $buffer .= $this->html->abreTagAberta('span') . $msgErro . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'form-group w-icon'));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'text', 'id' => 'username_id', 'name' => 'email', 'class' => 'form-control input-lg', 'placeholder' => 'Email', 'value' => $email));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'fa fa-user signin-form-icon')) . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'form-group w-icon'));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'password', 'id' => 'password_id', 'name' => 'signin_password', 'class' => 'form-control input-lg', 'placeholder' => 'Senha'));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'fa fa-lock signin-form-icon')) . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'form-actions'));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'submit', 'id' => 'submit_id', 'value' => 'Acessar', 'class' => 'signin-btn bg-primary'));
        $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'class' => 'forgot-password', 'id' => 'forgot-password-link')) . ' Esqueceu sua senha? ' . $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-with'));
        $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'class' => 'signin-with-btn', 'style' => 'background:#4f6faa;background:rgba(79, 111, 170, .8);'));
        $buffer .= ' Acessar com ' . $this->html->abreTagAberta('span') . ' Facebook ' . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('form');

        //reset-form
        $visivel = ($recovery == "true" ? "block" : "none");
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'password-reset-form', 'class' => 'password-reset-form', 'style' => 'display:'. $visivel .';'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'header'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-text'));
        $buffer .= $this->html->abreTagAberta('span');
        $buffer .= ' Redefinir senha ';
        $buffer .= $this->html->fechaTag('span');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'close')) . '&times;' . $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

            
        // reset senha
        $buffer .= $this->html->abreTagAberta('form', array('id' => 'signin-form_id', 'action' => './', 'method' => 'post'));
        $buffer .= $this->html->abreTagAberta('span', ['class' => 'negrito italico', 'style' => 'display:'. $visivel .';']);
        $buffer .= $erroLogin . '<br /><br />';
        $buffer .= $this->html->fechaTag('span');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'form-group w-icon'));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'text', 'id' => 'p_email_id', 'name' => 'email', 'required' => 'required', 'value' => $email, 'class' => 'form-control input-lg', 'placeholder' => 'Informe o seu email'));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'fa fa-envelope signin-form-icon')) . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'form-actions'));
        $buffer .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'recovery', 'value' => 'true']);
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'hidden', 'id' => 'acao', 'name' => 'acao', 'value' => 'recovery'));
        $buffer .= $this->html->abreTagFechada('input', ['type' => 'submit', 'value' => 'Enviar nova senha', 'class' => 'signin-btn bg-primary']);
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('form');
     
       
        $buffer .= $this->html->fechaTag('div');

        // end: signin-form
        $buffer .= $this->html->fechaTag('div');

        // end: signin-container
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'not-a-member'));
        //$buffer .= ' Ainda não é usuário? ' . $this->html->abreTagAberta('a', array('href' => '../Cadastro')) . 'Cadastre-se' . $this->html->fechaTag('a') . ' agora mesmo!';
        $buffer .= $this->html->fechaTag('div');

        return $buffer;

    }
    
    public function formRecovery($erroRecover = NULL)
     {

        $template = new \Pixel\Template\Template();

        $hash   = \filter_input(\INPUT_GET, 'hash');
        $email  = \filter_input(\INPUT_GET, 'email');
            
        $buffer  = '';
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/login.css', 'rel' => 'stylesheet', 'type' => 'text/css')) . $this->html->fechaTag('link');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/login-prescripts.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'page-signin-bg'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'overlay')) . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('img', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/demo/signin-bg-1.jpg'));
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-container'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-info'));
        $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'class' => 'logo'));
        $buffer .= $this->html->abreTagAberta('img', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/demo/logo-big.png', 'style' => 'margin-top: -5px;')) . '&nbsp;' . SIS_ID_NAMESPACE_PROJETO;
        $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'slogan')) . SIS_SLOGAN . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('ul');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-sitemap signin-icon')) . $this->html->fechaTag('i') . 'Estrutura modular flexível';
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-file-text-o signin-icon')) . $this->html->fechaTag('i') . 'HTML5, Ajax, CSS3 e SCSS';
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-outdent signin-icon')) . $this->html->fechaTag('i') . 'Suporte técnico integrado';
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-heart signin-icon')) . $this->html->fechaTag('i') . 'Desenvolvido com humor';
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->fechaTag('ul');
        // end: signin-info
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-form'));
        $buffer .= $this->html->abreTagAberta('form', array('id' => 'signin-form_id', 'action' => './', 'method' => 'post'));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'hidden', 'id' => 'acao', 'name' => 'acao', 'value' => 'getAuth'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-text'));
        $buffer .= $this->html->abreTagAberta('span') . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'form-group w-icon'));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'text', 'id' => 'username_id', 'name' => 'email', 'class' => 'form-control input-lg', 'placeholder' => 'Email', 'value' => $email));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'fa fa-user signin-form-icon')) . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'form-group w-icon'));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'password', 'id' => 'password_id', 'name' => 'signin_password', 'class' => 'form-control input-lg', 'placeholder' => 'Senha'));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'fa fa-lock signin-form-icon')) . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'form-actions'));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'submit', 'id' => 'submit_id', 'value' => 'Acessar', 'class' => 'signin-btn bg-primary'));
        $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'class' => 'forgot-password', 'id' => 'forgot-password-link')) . ' Esqueceu sua senha? ' . $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-with'));
        $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'class' => 'signin-with-btn', 'style' => 'background:#4f6faa;background:rgba(79, 111, 170, .8);'));
        $buffer .= ' Acessar com ' . $this->html->abreTagAberta('span') . ' Facebook ' . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('form');

        //reset-form
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'password-reset-form', 'class' => 'password-reset-form', 'style' => 'display:block;'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'header'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-text'));
        $buffer .= $this->html->abreTagAberta('span');
        $buffer .= ' Redefinir senha ';
        $buffer .= $this->html->fechaTag('span');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'close')) . $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

        // reset senha
        $visivel = ($erroRecover ? 'block' : 'none');
        $buffer .= $this->html->abreTagAberta('form', array('id' => 'password-reset-form-id', 'action' => '#', 'method' => 'post'));
        $buffer .= $this->html->abreTagAberta('span', ['class' => 'negrito italico', 'style' => 'display:'. $visivel .';']);
        $buffer .= $erroRecover . '<br /><br />';
        $buffer .= $this->html->fechaTag('span');
        
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'form-group w-icon'));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'password', 'id' => 'new_password', 'name' => 'new_password', 'required' => 'required', 'class' => 'form-control input-lg', 'placeholder' => 'Nova Senha'));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'fa fa-lock signin-form-icon')) . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'form-group w-icon'));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'password', 'id' => 'new_password_b', 'required' => 'required', 'name' => 'new_password_b', 'class' => 'form-control input-lg', 'placeholder' => 'Repita a Senha'));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'fa fa-lock signin-form-icon')) . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('div');
        
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'form-actions'));
        $buffer .= $this->html->abreTagFechada('input', ['type' => 'hidden', 'name' => 'recovery', 'value' => 'true']);
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'hidden', 'id' => 'acao', 'name' => 'acao', 'value' => 'setNewPass'));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'hidden', 'id' => 'email', 'name' => 'email', 'value' => $email));
        $buffer .= $this->html->abreTagAberta('input', array('type' => 'hidden', 'id' => 'hash', 'name' => 'hash', 'value' => $hash));
        $buffer .= $this->html->abreTagFechada('input', ['type' => 'submit', 'value' => 'Cadastrar nova senha', 'class' => 'signin-btn bg-primary']);
        $buffer .= $this->html->fechaTag('div');
        
        $buffer .= $this->html->fechaTag('form');

        $buffer .= $this->html->fechaTag('div');

        // end: signin-form
        $buffer .= $this->html->fechaTag('div');

        // end: signin-container
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'not-a-member'));

        $buffer .= $this->html->fechaTag('div');
        return $buffer;

    }


    public function getFormLimpo($titulo, $msg)
    {

        $template = new \Pixel\Template\Template();
       
       
        $buffer  = '';
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/login.css', 'rel' => 'stylesheet', 'type' => 'text/css')) . $this->html->fechaTag('link');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/login-prescripts.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'page-signin-bg'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'overlay')) . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('img', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/demo/signin-bg-1.jpg'));
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-container'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-info'));
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE, 'class' => 'logo'));
        $buffer .= $this->html->abreTagAberta('img', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/demo/logo-big.png', 'style' => 'margin-top: -5px;')) . '&nbsp;' . SIS_ID_NAMESPACE_PROJETO;
        $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'slogan')) . SIS_SLOGAN . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('ul');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-sitemap signin-icon')) . $this->html->fechaTag('i') . 'Estrutura modular flexível';
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-file-text-o signin-icon')) . $this->html->fechaTag('i') . 'HTML5, Ajax, CSS3 e SCSS';
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-outdent signin-icon')) . $this->html->fechaTag('i') . 'Suporte técnico integrado';
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->abreTagAberta('li');
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-heart signin-icon')) . $this->html->fechaTag('i') . 'Desenvolvido com humor';
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->html->fechaTag('ul');
        // end: signin-info
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-form'));

        //reset-form
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'password-reset-form', 'class' => 'password-reset-form', 'style' => 'display:block;'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'header'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'signin-text'));
        $buffer .= $this->html->abreTagAberta('span');
        $buffer .= $titulo;
        $buffer .= $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

            
        $buffer .= $this->html->abreTagAberta('span');
        $buffer .= $msg;
        $buffer .= $this->html->fechaTag('span');

        $buffer .= $this->html->abreTagAberta('a', ['href' => 'http:'. SIS_URL_BASE .'Accounts/Login/']);
        $buffer .= $this->abreTagAberta("h3");
        $buffer .= "Login";
        $buffer .= $this->html->fechaTag('h3');
        $buffer .= $this->html->fechaTag('a');
       
        $buffer .= $this->html->fechaTag('div');

        // end: signin-form
        $buffer .= $this->html->fechaTag('div');

        // end: signin-container
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $this->html->abreTagAberta('div', array('class' => 'not-a-member'));

        $buffer .= $this->html->fechaTag('div');

        return $buffer;

    }
}