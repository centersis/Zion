<?php

namespace Pixel\Remoto;

use Zion\Banco\Conexao;
use Pixel\Exception\RemotoException;

class LinhasGrid
{

    public function alterarLinhas($nLinhas, $moduloCod)
    {
        try {
            $con = Conexao::conectar();

            if ($nLinhas < 1 or $nLinhas > 200) {
                throw new RemotoException('Número de linhas inválido!');
            }

            if (!\is_numeric($moduloCod)) {
                throw new RemotoException('Módulo inválido!');
            }

            $qbAtual = $con->qb();

            $qbAtual->select('usuarioPaginacaoCod')
                    ->from('_usuario_paginacao', '')
                    ->where($qbAtual->expr()->eq('organogramaCod', ':organogramaCod'))
                    ->andWhere($qbAtual->expr()->eq('usuarioCod', ':usuarioCod'))
                    ->andWhere($qbAtual->expr()->eq('moduloCod', ':moduloCod'))
                    ->setParameter('organogramaCod', $_SESSION['organogramaCod'])
                    ->setParameter('usuarioCod', $_SESSION['usuarioCod'])
                    ->setParameter('moduloCod', $moduloCod);

            $dadosAtual = $con->execLinha($qbAtual);

            if (!empty($dadosAtual)) {

                $qbUpdate = $con->qb();

                $qbUpdate->update('_usuario_paginacao')
                        ->set('usuarioPaginacaoTotal', '?')
                        ->where($qbAtual->expr()->eq('organogramaCod', '?'))
                        ->andWhere($qbAtual->expr()->eq('usuarioCod', '?'))
                        ->andWhere($qbAtual->expr()->eq('moduloCod', '?'))
                        ->setParameter(0, $nLinhas)
                        ->setParameter(1, $_SESSION['organogramaCod'])
                        ->setParameter(2, $_SESSION['usuarioCod'])
                        ->setParameter(3, $moduloCod)
                        ->execute();
            } else {
                $qbInsert = $con->qb();

                $qbInsert->insert('_usuario_paginacao')
                        ->setValue('organogramaCod', '?')
                        ->setValue('usuarioCod', '?')
                        ->setValue('moduloCod', '?')
                        ->setValue('usuarioPaginacaoTotal', '?')
                        ->setParameter(0, $_SESSION['organogramaCod'])
                        ->setParameter(1, $_SESSION['usuarioCod'])
                        ->setParameter(2, $moduloCod)
                        ->setParameter(3, $nLinhas)
                        ->execute();
            }

            return \json_encode(array('sucesso' => 'true'));
        } catch (RemotoException $e) {
            return \json_encode(array('sucesso' => 'false', 'retorno' => $e->getMessage()));
        }
    }

}
