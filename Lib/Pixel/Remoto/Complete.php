<?php

namespace Pixel\Remoto;
use Pixel\Exception\RemotoException;

class Complete
{

    /**
     * Complete::listar()
     * 
     * @return
     */
    public function listar()
    {

        $valida = \Zion\Validacao\Valida::instancia();

        //sleep(1);

        $tabela = $valida->texto()->trata(\filter_input(\INPUT_GET, 't'));
        $campoCod = $valida->texto()->trata(\filter_input(\INPUT_GET, 'cc'));
        $campoDesc = $valida->texto()->trata(\filter_input(\INPUT_GET, 'cd'));
        $campoBusca = $valida->texto()->trata(\filter_input(\INPUT_GET, 'cb'));
        $termoBusca = $valida->texto()->trata(\filter_input(\INPUT_GET, 'term'));
        $idConexao = $valida->texto()->trata(\filter_input(\INPUT_GET, 'idc'));

        $l = \filter_input(\INPUT_GET, 'l');
        $limite = (\is_numeric($l) and $l < 50) ? $l : 10;

        try {
            $con = \Zion\Banco\Conexao::conectar($idConexao);

            $ccod = $campoCod ? $campoCod : "'' as ";
            $cdes = $campoDesc ? $campoDesc : "''";
            $cbus = $campoBusca ? $campoBusca : $campoDesc;

            $qb = $con->qb();
            $sql = $qb->select($ccod . ' cod', $cdes . ' dsc')
                ->from($tabela, '')
                ->where($qb->expr()->like($cbus, $qb->expr()->literal('%' . $termoBusca . '%')))
                ->setMaxResults($limite);

            $rs = $con->executar($sql);

            $ret = [];
            while ($dados = $rs->fetch()) {
                $ret[] = array('id' => $dados['cod'], 'value' => $dados['dsc'], 'label' => $dados['dsc']);
            }

            return \json_encode($ret);
        } catch (\Exception $e) {
            return \json_encode(array(array('id' => '0', 'value' => 'erro', 'label' => $e->getMessage())));
        }
    }

}
