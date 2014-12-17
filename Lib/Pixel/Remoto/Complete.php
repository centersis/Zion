<?php

/**
 * \Pixel\Remoto\Complete()
 * 
 * @package The Sappiens Team
 * @author 2014
 * @copyright 2014
 * @version 1.0
 * @access public
 */

namespace Pixel\Remoto;

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
        //$condicao = $valida->texto()->trata(filter_input(INPUT_GET, 'cnd'));
        $condicao = \filter_input(\INPUT_GET, 'cnd');

        $l = \filter_input(\INPUT_GET, 'l');
        $limite = (\is_numeric($l) and $l < 50) ? $l : 10;

        //Converte Condicao
        $condicaoD = '';
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

            $qb = $con->link()->createQueryBuilder();
            $sql = $qb->select($ccod . ' as cod', $cdes.' as dsc')
                    ->from($tabela,'')                    
                    ->where($qb->expr()->like($cbus, $qb->expr()->literal('%'.$termoBusca.'%')))
                    //->setParameter($condicaoD,'')
                    ->setMaxResults($limite);

            //$sql = "SELECT $ccod cod, $cdes as dsc FROM $tabela WHERE $cbus LIKE '" . $termoBusca . "%' $condicaoD LIMIT $limite ";

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
