<?php

namespace Pixel\Login;

class LoginClass extends LoginSql
{ 

    public function getAuth($l,$p)
    {
        
        $con = \Zion\Banco\Conexao::conectar();
        $sql = new \Pixel\Login\LoginSql();

        $getAuth = $con->execLinhaArray($sql->getAuth($l,$p));

        if(!empty($getAuth['usuariocod']) and !empty($getAuth['organogramacod'])) {

            unset($_SESSION['usuarioCod'], $_SESSION['organogramaCod']);

            $_SESSION['usuarioCod']     = $getAuth['usuariocod'];
            $_SESSION['organogramaCod'] = $getAuth['organogramacod'];

            return true;

        }

        return false;

    }    

}