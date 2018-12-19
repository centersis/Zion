<?php

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

        if (isset($_SESSION['organogramaCod'])) {
            $organogramaCod = $_SESSION['organogramaCod'];
        } else {
            $organogramaCod = 1;
        }

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
                'organogramaCod' => $organogramaCod,
                'logHash' => $logHash,
                'logId' => $actParams['id'],
                'logAcao' => $actParams['acao'],
                'logDescricao' => \array_key_exists('logDescricao', $actParams) ? $actParams['logDescricao'] : null,
                'logTab' => \array_key_exists('tab', $actParams) ? $actParams['tab'] : null,
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
