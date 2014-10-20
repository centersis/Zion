<?php

namespace Zion\Remoto;

class Complete
{

    public function listar()
    {

        $valida = new \Zion\Validacao\Valida();

        sleep(1);

        $tabela = $valida->texto()->trata(filter_input(INPUT_GET, 't'));
        $campoCod = $valida->texto()->trata(filter_input(INPUT_GET, 'cc'));
        $campoDesc = $valida->texto()->trata(filter_input(INPUT_GET, 'cd'));
        $campoBusca = $valida->texto()->trata(filter_input(INPUT_GET, 'cb'));
        $termoBusca = $valida->texto()->trata(filter_input(INPUT_GET, 'term'));
        $idConexao = $valida->texto()->trata(filter_input(INPUT_GET, 'idc'));
        $condicao = $valida->texto()->trata(filter_input(INPUT_GET, 'cnd'));

        $l = filter_input(INPUT_GET, 'l');
        $limite = (is_numeric($l) and $l < 50) ? $l : 10;

        //Converte Condicao
        if (!empty($condicao)) {
            $condicaoA = ' ' . $condicao;
            $condicaoB = str_replace(":", "'", $condicaoA);
            $condicaoC = str_replace(" e ", " AND ", $condicaoB);
            $condicaoD = str_replace(" ou ", " OR ", $condicaoC);
        }

        try {
            $con = \Zion\Banco\Conexao::conectar($idConexao);

            $ccod = $campoCod ? $campoCod : "'' as ";
            $cdes = $campoDesc ? $campoDesc : "''";
            $cbus = $campoBusca ? $campoBusca : $campoDesc;

            $sql = "SELECT $ccod cod, $cdes as dsc FROM $tabela WHERE $cbus LIKE '" . $termoBusca . "%' $condicaoD LIMIT $limite ";

            $rs = $con->executar($sql);

            $ret = [];
            while ($dados = $rs->fetch_array()) {
                $ret[] = array('id' => $dados['cod'], 'value' => $dados['dsc'], 'label' => $dados['dsc']);
            }

            return json_encode($ret);
        } catch (Exception $e) {
            return json_encode(array(array('id' => '0', 'value' => 'erro', 'label' => $e->getMessage())));
        }
    }

}
