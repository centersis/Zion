<?php

namespace Pixel\Login;

class LoginSql
{

    public function getAuth($l, $p)
    {
        return "SELECT usuarioCod, organogramaCod
                  FROM _usuario";
    }

    public function getDadosUsuario($cod)
    {
        return "SELECT *
                  FROM  _usuario
                 WHERE  usuarioCod = ".$cod;
    }    

}
