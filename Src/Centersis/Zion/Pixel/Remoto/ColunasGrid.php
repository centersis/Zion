<?php

namespace Centersis\Zion\Pixel\Remoto;

use Centersis\Zion\Banco\Conexao;
use Centersis\Zion\Exception\ErrorException;

class ColunasGrid
{

    public function configurarColunas($moduloCod)
    {
        try {
            $con = Conexao::conectar();

            $selecionados = filter_input(INPUT_GET, 'sisGridColunas', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            
            if (!is_array($selecionados) or count($selecionados) < 1) {
                throw new ErrorException('Nehuma coluna foi selecionada!');
            }

            if (!is_numeric($moduloCod)) {
                throw new ErrorException('Módulo inválido!');
            }
            
            $colunas = implode(',', $selecionados);

            $qbAtual = $con->qb();

            $qbAtual->select('usuario_grid_colunas')
                    ->from('_usuario_grid', '')
                    ->where($qbAtual->expr()->eq('organograma_cod', ':organograma_cod'))
                    ->andWhere($qbAtual->expr()->eq('usuario_cod', ':usuario_cod'))
                    ->andWhere($qbAtual->expr()->eq('modulo_cod', ':modulo_cod'))
                    ->setParameter('organograma_cod', $_SESSION['organograma_cod'])
                    ->setParameter('usuario_cod', $_SESSION['usuario_cod'])
                    ->setParameter('modulo_cod', $moduloCod);

            $dadosAtual = $con->execLinha($qbAtual);

            if (!empty($dadosAtual)) {

                $qbUpdate = $con->qb();

                $qbUpdate->update('_usuario_grid')
                        ->set('usuario_grid_colunas', '?')
                        ->where($qbAtual->expr()->eq('organograma_cod', '?'))
                        ->andWhere($qbAtual->expr()->eq('usuario_cod', '?'))
                        ->andWhere($qbAtual->expr()->eq('modulo_cod', '?'))
                        ->setParameter(0, $colunas)
                        ->setParameter(1, $_SESSION['organograma_cod'])
                        ->setParameter(2, $_SESSION['usuario_cod'])
                        ->setParameter(3, $moduloCod)
                        ->execute();
            } else {
                $qbInsert = $con->qb();

                $qbInsert->insert('_usuario_grid')
                        ->setValue('organograma_cod', '?')
                        ->setValue('usuario_cod', '?')
                        ->setValue('modulo_cod', '?')
                        ->setValue('usuario_grid_colunas', '?')
                        ->setParameter(0, $_SESSION['organograma_cod'])
                        ->setParameter(1, $_SESSION['usuario_cod'])
                        ->setParameter(2, $moduloCod)
                        ->setParameter(3, $colunas)
                        ->execute();
            }

            return json_encode(array('sucesso' => 'true'));
        } catch (\Exception $e) {
            return json_encode(array('sucesso' => 'false', 'retorno' => $e->getMessage()));
        }
    }

}
