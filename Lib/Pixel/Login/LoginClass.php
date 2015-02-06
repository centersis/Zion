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

class LoginClass extends LoginSql
{
    protected $con;

    public function __construct() {
        $this->con  = \Zion\Banco\Conexao::conectar();
        parent::__construct();
    }
    
    public function getAuth($l,$p)
    {
        $loginSql   = new \Pixel\Login\LoginSql();

        $getAuth = $this->con->execLinhaArray(parent::getAuth($l, $this->getSenhaHash($p)));

        if(!empty($getAuth['usuariocod']) and !empty($getAuth['organogramacod']) and !empty($getAuth['perfilcod'])) {

            unset($_SESSION['usuarioCod'], $_SESSION['organogramaCod'], $_SESSION['perfilCod']);
            $this->registraAcesso($getAuth['usuariocod'], $getAuth['numeroacessos']);
            
            $_SESSION['usuarioCod']     = $getAuth['usuariocod'];
            $_SESSION['organogramaCod'] = $getAuth['organogramacod'];
            $_SESSION['perfilCod']      = $getAuth['perfilcod'];
            $_SESSION['urlLogin']       = SIS_URL_BASE;

            return true;

        }

        if(!empty($getAuth['usuariocod']) and (empty($getAuth['organogramacod']) or empty($getAuth['perfilcod']))){
            throw new \Exception("Login desativado ou com inconsistência nas permissões!");
        }

        return false;
    }
    
    public function getSenhaHash($password)
    {
        return crypt($password, SIS_STRING_CRYPT);
    }
    
    public function registraAcesso($usuarioCod, $numeroAcessos)
    {
        $qb = $this->con->link()->createQueryBuilder();
        $sql = parent::registraAcessoSql($usuarioCod, ($numeroAcessos + 1));
        return $sql->execute();
    }
    
    public function recovery($l)
    {
        $dadosUsuario = $this->con->execLinha($this->getDadosRecoverySql($l));
        if(!empty($dadosUsuario['usuariocod']) and !empty($dadosUsuario['usuariologin'])){
            
            if($dadosUsuario['usuariostatus'] === "I"){
                throw new \Exception("Este login está desativado.");
            }

            $this->enviaEmailRecuperacao($dadosUsuario);
            return true;
            
        } else {
            throw new \Exception("Não localizamos seu email em nosso cadastro de usuários.");
        }
    }
    
    private function enviaEmailRecuperacao($dadosUsuario)
    {
        $mailer = new \Zion\Email\Email();
        $hash   = $this->getHashRecovery($dadosUsuario['usuariocod'], $dadosUsuario['usuariologin']);

        $urlRecover = 'http:'. SIS_URL_BASE .'Accounts/Login/?acao=recoverPass&email='. $dadosUsuario['usuariologin'] .'&id='. uniqid() .'&hash='. $hash;

        $msg = 'Olá '. $dadosUsuario['usuariologin'] .',
                <br />
                <br />
                Você solicitou uma nova senha para login no sistema '. SIS_DESCRICAO . '. Para continuar clique no link abaixo.
                <br />
                <br />
                <a href="'. $urlRecover.'" target="_blank">'. $urlRecover .'</a>
                <br />
                <br />        
                Se o link não funcionar, copie-o e cole-o no seu browser.
                <br />
                <br />
                Atenciosamente,
                <br />
                <br />
                '. SIS_AUTOR .'
                <br />
                <br />
                <hr />
                Saiba mais sobre o Sappiens Framework em <a href="http://www.sappiens.com.br/">www.sappiens.com.br</a>
                <br />
                Acesse nossa wiki em <a href="http://dev.sappiens.com.br/">dev.sappiens.com.br</a>
                <br />
                <br />
                Mais mãos, fazem um software melhor.
                <br />';

        $mailer->enviarEmail($dadosUsuario['usuariologin'], "Recuperação de senha do ". SIS_DESCRICAO, $msg, "noreply");
       
        return true;
    }
    
    private function getHashRecovery($usuarioCod, $l)
    {
        $hash = bin2hex(openssl_random_pseudo_bytes(20));
        $sql  = parent::getHashRecoverySql($usuarioCod, $hash);

        if($sql->execute()){
            return $hash;
        } else {
            throw new \Exception("Houve um erro ao enviar sua solicitação!<br>Tente novamente mais tarde.");
        }
    }
    
    public function validaHash($email, $hash)
    {
        $dadosUsuario   = $this->con->execLinha($this->getDadosRecoverySql($email));

        if(empty($dadosUsuario['usuariocod'])){
            return false;
        }

        $sql            = parent::validaHashSql($dadosUsuario['usuariocod'], $hash);
        $dadosHash      = $this->con->execLinha($sql);

        if(!empty($dadosHash['usuariorecoverystatus']) and $dadosHash['usuariorecoverystatus'] === "A"){

            $dI = \DateTime::createFromFormat('Y-m-d H:i:s', $dadosHash['usuariorecoverydatahora']);
            $dF = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $diff = (array) $dI->diff($dF);

            if($diff['h'] < 2 and $diff['d'] == 0 and $diff['m'] == 0 and $diff['y'] == 0){
                return true;
            } else {
                $this->invalidaHash($dadosUsuario['usuariocod'], $hash);
                return false;
            }

        } else {
            return false;
        }
    }
    
    public function invalidaHash($usuarioCod, $hash)
    {
        $sql  = parent::invalidaHashSql($usuarioCod, $hash);
        $sql->execute();
        return true;
    }
    
    public function setNewPass($email, $hash, $senha, $senhaB)
    {
        $dadosUsuario   = $this->con->execLinha($this->getDadosRecoverySql($email));
        
        if($this->validaSenhas($senha, $senhaB) === true) {

            if($dadosUsuario['usuariosenha'] === $this->getSenhaHash($senha)){
                $this->invalidaHash($dadosUsuario['usuariocod'], $hash);
                return true;
            }

            $sql = parent::setNewPassSql($dadosUsuario['usuariocod'], $this->getSenhaHash($senha));
           
            if($sql->execute()){
                $this->invalidaHash($dadosUsuario['usuariocod'], $hash);
                return true;
            } else {
                throw new \Exception("Houve um erro ao enviar sua solicitação! Tente novamente mais tarde.");
            }

        } else {
            throw new \Exception("Senha inválida.");
        }
    }
    
    private function validaSenhas($senha, $senhaB)
    {
        if($senha === $senhaB){
            
            if(strlen($senha) >= 6){
                return true;
            } else {
                throw new \Exception("Sua senha deve conter no mínimo 6 caracteres.");
            }

        } else {
            throw new \Exception("As senhas informadas não conferem.");
        }
        
        return false;
    }

}