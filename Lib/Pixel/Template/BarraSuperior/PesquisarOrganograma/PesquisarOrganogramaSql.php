<?php

namespace Pixel\Template\BarraSuperior\PesquisarOrganograma;

class PesquisarOrganogramaSql
{

    private $con;

    public function __construct()
    {

        $this->con = \Zion\Banco\Conexao::conectar();
    }

    public function getDadosOrganograma($cod)
    {

        $qb = $this->con->qb();

        $qb->select('*')
            ->from('organograma', 'a')
            ->where($qb->expr()->eq('a.organogramaCod', ':organogramaCod'))
            ->setParameter('organogramaCod', $cod);

        return $qb;
    }

    public function getDadosUsuario()
    {

        $qb = $this->con->qb();

        $qb->select('*')
            ->from('_usuario', 'a')
            ->where($qb->expr()->eq('a.usuarioCod', ':usuarioCod'))
            ->setParameter('usuarioCod', $_SESSION['usuarioCod']);

        return $qb;
    }

}
