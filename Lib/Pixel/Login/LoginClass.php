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