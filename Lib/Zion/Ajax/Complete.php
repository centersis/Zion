<?php

namespace Zion\Ajax;

class Complete
{
    public function listar()
    {

        $valida = new \Zion\Validacao\Valida();

        sleep(2);

        $tabela = $valida->texto()->trata(@$_GET['t']);
        $campoCod = $valida->texto()->trata(@$_GET['cc']);
        $campoDesc = $valida->texto()->trata(@$_GET['cd']);
        $campoBusca = $valida->texto()->trata(@$_GET['cb']);
        $termoBusca = $valida->texto()->trata(@$_GET['term']);
        $idConexao = $valida->texto()->trata(@$_GET['idc']);
        $condicao = $valida->texto()->trata(@$_GET['cnd']);
        $limite = (is_numeric(@$_GET['l']) and @ $_GET['l'] < 50) ? @$_GET['l'] : 10;

        //Converte Condicao
        if (!empty($condicao)) {
            $condicao = ' ' . $condicao;
            $condicao = str_replace(":", "'", $condicao);
            $condicao = str_replace(" e ", " AND ", $condicao);
            $condicao = str_replace(" ou ", " OR ", $condicao);
        }

        try {
            $con = \Zion\Banco\Conexao::conectar($idConexao);

            $ccod = $campoCod ? $campoCod : "'' as ";
            $cdes = $campoDesc ? $campoDesc : "''";
            $cbus = $campoBusca ? $campoBusca : $campoDesc;

            $sql = "SELECT $ccod cod, $cdes as dsc FROM $tabela WHERE $cbus LIKE '" . $termoBusca . "%' $condicao LIMIT $limite ";

            $rs = $con->executar($sql);

            $ret = [];
            while ($dados = $rs->fetch_array()) {
                $ret[] = array('id' => $dados['cod'], 'value' => $dados['dsc'], 'label' => $dados['dsc']);
            }

            return  json_encode($ret);
        } catch (Exception $e) {
            return json_encode(array(array('id' => '0', 'value' => 'erro', 'label' => $e->getMessage())));
        }
    }
}
