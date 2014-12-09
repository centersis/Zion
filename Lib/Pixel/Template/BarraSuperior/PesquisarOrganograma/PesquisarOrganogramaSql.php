<?php

namespace Pixel\Template\BarraSuperior\PesquisarOrganograma;

class PesquisarOrganogramaSql
{

    public function getDadosOrganograma($cod)
    {
        return "SELECT *
                  FROM  organograma
                 WHERE  organogramaCod = ".$cod;
    }

    public function getDadosUsuario($cod)
    {
        return "SELECT *
                  FROM  _usuario
                 WHERE  usuarioCod = ".$cod;
    }    

}
