<?php

namespace Pixel\Remoto;

use Zion\Banco\Conexao;
use Pixel\Exception\ErrorException;

class ColunasGrid
{

    public function configurarColunas($moduloCod)
    {
        try {
            $con = Conexao::conectar();

            $selecionados = \filter_input(\INPUT_GET, 'sisGridColunas', \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);
            
            if (!\is_array($selecionados) or \count($selecionados) < 1) {
                throw new ErrorException('Nehuma coluna foi selecionada!');
            }

            if (!\is_numeric($moduloCod)) {
                throw new ErrorException('Módulo inválido!');
            }
            
            $colunas = \implode(',', $selecionados);

            $qbAtual = $con->qb();

            $qbAtual->select('usuarioGridColunas')
                    ->from('_usuario_grid', '')
                    ->where($qbAtual->expr()->eq('organogramaCod', ':organogramaCod'))
                    ->andWhere($qbAtual->expr()->eq('usuarioCod', ':usuarioCod'))
                    ->andWhere($qbAtual->expr()->eq('moduloCod', ':moduloCod'))
                    ->setParameter('organogramaCod', $_SESSION['organogramaCod'])
                    ->setParameter('usuarioCod', $_SESSION['usuarioCod'])
                    ->setParameter('moduloCod', $moduloCod);

            $dadosAtual = $con->execLinha($qbAtual);

            if (!empty($dadosAtual)) {

                $qbUpdate = $con->qb();

                $qbUpdate->update('_usuario_grid')
                        ->set('usuarioGridColunas', '?')
                        ->where($qbAtual->expr()->eq('organogramaCod', '?'))
                        ->andWhere($qbAtual->expr()->eq('usuarioCod', '?'))
                        ->andWhere($qbAtual->expr()->eq('moduloCod', '?'))
                        ->setParameter(0, $colunas)
                        ->setParameter(1, $_SESSION['organogramaCod'])
                        ->setParameter(2, $_SESSION['usuarioCod'])
                        ->setParameter(3, $moduloCod)
                        ->execute();
            } else {
                $qbInsert = $con->qb();

                $qbInsert->insert('_usuario_grid')
                        ->setValue('organogramaCod', '?')
                        ->setValue('usuarioCod', '?')
                        ->setValue('moduloCod', '?')
                        ->setValue('usuarioGridColunas', '?')
                        ->setParameter(0, $_SESSION['organogramaCod'])
                        ->setParameter(1, $_SESSION['usuarioCod'])
                        ->setParameter(2, $moduloCod)
                        ->setParameter(3, $colunas)
                        ->execute();
            }

            return \json_encode(array('sucesso' => 'true'));
        } catch (\Exception $e) {
            return \json_encode(array('sucesso' => 'false', 'retorno' => $e->getMessage()));
        }
    }

}
