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

namespace Zion\Log;

use Zion\Banco\Conexao;

class LogSql
{

    protected $con;

    public function __construct()
    {
        $idBanco = null;

        $namespace = '\\' . \SIS_ID_NAMESPACE_PROJETO . '\\Config';

        $cBanco = $namespace::$SIS_CFG['bases']['padrao']['banco'];

        if (\array_key_exists('log', $namespace::$SIS_CFG['bases']) and $namespace::$SIS_CFG['bases']['log']['banco'] <> $cBanco) {
            $idBanco = 'log';
        }

        $this->con = Conexao::conectar($idBanco);
    }

    protected function getDadosModuloSql($con, $moduloNome)
    {
        $qb = $con->qb();
        
        $qb->select('*')
            ->from('_modulo', 'a')
            ->where('a.moduloNome = :moduloNome')
            ->setParameter('moduloNome', $moduloNome);

        return $qb;
    }

    protected function salvarLogSql($actParams, $sqlCompleta, $logHash)
    {
        /* @var $qb \Doctrine\DBAL\Query\QueryBuilder */
        $qb = $this->con->qb();

        $qb->insert('_log')
            ->values(['usuarioCod' => ':usuarioCod',
                'moduloCod' => ':moduloCod',
                'organogramaCod' => ':organogramaCod',
                'logHash' => ':logHash',
                'logId' => ':logId',
                'logAcao' => ':logAcao',
                'logDescricao' => ':logDescricao',
                'logTab' => ':logTab',
                'logSql' => ':logSql',
                'logDataHora' => $qb->expr()->literal(date('Y-m-d H:i:s'))
            ])
            ->setParameters(['usuarioCod' => $actParams['usuarioCod'],
                'moduloCod' => $actParams['moduloCod'],
                'organogramaCod' => $_SESSION['organogramaCod'],
                'logHash' => $logHash,
                'logId' => $actParams['id'],
                'logAcao' => $actParams['acao'],
                'logDescricao' => \array_key_exists('logDescricao',$actParams) ? $actParams['logDescricao'] : null,
                'logTab' => \array_key_exists('tab',$actParams) ? $actParams['tab'] : null,
                'logSql' => $sqlCompleta
                ], ['usuarioCod' => \PDO::PARAM_INT,
                'moduloCod' => \PDO::PARAM_INT,
                'organogramaCod' => \PDO::PARAM_INT,
                'logHash' => \PDO::PARAM_STR,
                'logId' => \PDO::PARAM_INT,
                'logAcao' => \PDO::PARAM_STR,
                'logDescricao' => \PDO::PARAM_STR,
                'logTab' => \PDO::PARAM_STR,
                'logSql' => \PDO::PARAM_STR
        ]);

        return $qb;
    }

}
