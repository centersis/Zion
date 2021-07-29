<?php

namespace Zion\Log;

use Zion\Banco\Conexao;
use App\Servicos\Rede\RedeClass;

class LogSql {

    protected $con;

    public function __construct() {
        $idBanco = null;

        $namespace = '\\' . \SIS_ID_NAMESPACE_PROJETO . '\\Config';

        $cBanco = $namespace::$SIS_CFG['bases']['padrao']['banco'];

        if (array_key_exists('log', $namespace::$SIS_CFG['bases']) and $namespace::$SIS_CFG['bases']['log']['banco'] <> $cBanco) {
            $idBanco = 'log';
        }

        $this->con = Conexao::conectar($idBanco);
    }

    protected function getDadosModuloSql($con, $moduloNome) {
        $qb = $con->qb();

        $qb->select('*')
                ->from('_modulo', 'a')
                ->where('a.modulo_nome = :modulo_nome')
                ->setParameter('modulo_nome', $moduloNome, \PDO::PARAM_STR);

        return $qb;
    }

    protected function salvarLogSql($actParams, $sqlCompleta, $logHash) {

        if (isset($_SESSION['organograma_cod'])) {
            $organogramaCod = $_SESSION['organograma_cod'];
        } else {
            $organogramaCod = 1;
        }

        /* @var $qb \Doctrine\DBAL\Query\QueryBuilder */
        $qb = $this->con->qb();

        $qb->insert('_log')
                ->values(['usuario_cod' => ':usuario_cod',
                    'modulo_cod' => ':modulo_cod',
                    'organograma_cod' => ':organograma_cod',
                    'log_hash' => ':log_hash',
                    'log_id' => ':log_id',
                    'log_acao' => ':log_acao',
                    'log_descricao' => ':log_descricao',
                    'log_ip' => ':log_ip',
                    'log_sql' => ':log_sql',
                    'log_data_hora' => $qb->expr()->literal(date('Y-m-d H:i:s'))
                ])
                ->setParameters(['usuario_cod' => $actParams['usuario_cod'],
                    'modulo_cod' => $actParams['modulo_cod'],
                    'organograma_cod' => $organogramaCod,
                    'log_hash' => $logHash,
                    'log_id' => $actParams['id'],
                    'log_acao' => $actParams['acao'],
                    'log_descricao' => array_key_exists('log_descricao', $actParams) ? $actParams['log_descricao'] : null,
                    'log_ip' => (new RedeClass())->getIp(),
                    'log_sql' => $sqlCompleta
                        ], ['usuario_cod' => \PDO::PARAM_INT,
                    'modulo_cod' => \PDO::PARAM_INT,
                    'organograma_cod' => \PDO::PARAM_INT,
                    'log_hash' => \PDO::PARAM_STR,
                    'log_id' => \PDO::PARAM_INT,
                    'log_acao' => \PDO::PARAM_STR,
                    'log_descricao' => \PDO::PARAM_STR,
                    'log_ip' => \PDO::PARAM_STR,
                    'log_sql' => \PDO::PARAM_STR
        ]);

        return $qb;
    }

}
