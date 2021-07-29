<?php

namespace Zion\Boleto;

interface IBoletoClass
{

    public function montaDados($fnc_parcela_cod, $parametrizacao);

    public function getDadosCliente($clienteCod);
    
    public function geraBoleto($fnc_parcela_cod);
}
