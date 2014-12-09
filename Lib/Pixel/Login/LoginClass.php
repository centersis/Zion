<?php

namespace Pixel\Login;

class LoginClass extends LoginSql
{ 

    public function getAuth($l,$p)
    {
        
        $con = \Zion\Banco\Conexao::conectar();
        $sql = new \Sappiens\Accounts\Login\LoginSql();

        $getAuth = $con->execLinhaArray($sql->getAuth($l,$p));

        if(!empty($getAuth['usuarioCod']) and !empty($getAuth['organogramaCod'])) {

            unset($_SESSION['usuarioCod'], $_SESSION['organogramaCod']);

            $_SESSION['usuarioCod']     = $getAuth['usuarioCod'];
            $_SESSION['organogramaCod'] = $getAuth['organogramaCod'];

            return true;

        }

        return false;

    }    

}