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

namespace Pixel\Notificacao;
use \Zion\Banco\Conexao;

class NotificacaoSql
{
    private $con;

    public function __construct() 
    {
        $this->con = Conexao::conectar();
    }

    public function getUltimasNotificacoesSql($usuarioCod)
    {

        $qb = $this->con->qb();
        $qb->select("*")
           ->from("_notificacao")
           ->where($qb->expr()->eq("notificacaoUsuarioCod", ":usuarioCod"))
           ->setMaxResults(10)
           ->setParameter("usuarioCod", $usuarioCod, \PDO::PARAM_INT);
        return $qb;

    }
    
    public function limpaNotificacaoSql($notificacaoCod, $usuarioCod)
    {
        $qb = $this->con->qb();

        $qb->update('_notificacao')
           ->set('notificacaoLida', $qb->expr()->literal('S'))
           ->set('notificacaoLidaDataHora', $qb->expr()->literal(date('Y-m-d H:i:s')))
           ->where($qb->expr()->eq("notificacaoCod", ":notificacaoCod"))
           ->andWhere($qb->expr()->eq("notificacaoUsuarioCod", ":usuarioCod"))
           ->setParameter("notificacaoCod", $notificacaoCod, \PDO::PARAM_INT)
           ->setParameter("usuarioCod", $usuarioCod, \PDO::PARAM_INT);

        return $qb;
    }

    public function getNumeroNotificacoesSql($usuarioCod)
    {

        $qb = $this->con->qb();
        $qb->select("notificacaoCod AS id")
           ->from("_notificacao")
           ->where($qb->expr()->eq("notificacaoUsuarioCod", ":usuarioCod"))
           ->andWhere($qb->expr()->eq("notificacaoLida", $qb->expr()->literal('N')))
           ->setParameter("usuarioCod", $usuarioCod, \PDO::PARAM_INT);
        return $qb;
    }

}
