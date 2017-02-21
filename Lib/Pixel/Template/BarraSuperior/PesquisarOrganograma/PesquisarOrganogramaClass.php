<?php

namespace Pixel\Template\BarraSuperior\PesquisarOrganograma;

class PesquisarOrganogramaClass extends PesquisarOrganogramaSql
{

    public function getDadosOrganograma($cod)
    {

        $con = \Zion\Banco\Conexao::conectar();
        return $con->execLinhaArray(parent::getDadosOrganograma($cod));
    }

    public function getDadosUsuario()
    {

        $con = \Zion\Banco\Conexao::conectar();
        return $con->execLinhaArray(parent::getDadosUsuario());
    }

}
