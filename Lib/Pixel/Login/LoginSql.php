<?php

namespace Pixel\Login;

class LoginSql
{

    public function getAuth($l, $p)
    {

        $con = \Zion\Banco\Conexao::conectar();
        $qb = $con->link()->createQueryBuilder();

        $qb->select('*')
           ->from('_usuario', 'a')
           ->where('a.usuarioLogin = :usuarioLogin')
           ->andWhere('a.usuarioSenha = :usuarioSenha')
           ->setParameters([':usuarioLogin' => $l, ':usuarioSenha' => $p]);    

        return $qb;    
/*
        return "SELECT usuarioCod, organogramaCod
                  FROM _usuario
                 WHERE usuarioLogin = '" . $l . "' AND usuarioSenha = '" . $p . "' ";
*/                 
    }

    public function getDadosUsuario($cod)
    {

        $con = \Zion\Banco\Conexao::conectar();
        $qb = $con->link()->createQueryBuilder();

        $qb->select('*')
           ->from('_usuario', 'a')
           ->where('a.usuarioCod = :usuarioCod')
           ->setParameter(':usuarioCod', $cod);    

        return $qb;  
/*
        return "SELECT *
                  FROM  _usuario
                 WHERE  usuarioCod = ".$cod;
*/                 
    }    

}
