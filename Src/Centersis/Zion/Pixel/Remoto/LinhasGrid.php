<?php

namespace Centersis\Zion\Pixel\Remoto;

use Centersis\Zion\Banco\Conexao;
use Centersis\Zion\Exception\ValidationException;
use Centersis\Zion\Exception\ErrorException;

class LinhasGrid
{

    public function alterarLinhas($nLinhas, $moduloCod)
    {
        try {
            $con = Conexao::conectar();

            if ($nLinhas < 1 or $nLinhas > 200) {
                throw new ValidationException('Número de linhas inválido!');
            }

            if (!is_numeric($moduloCod)) {
                throw new ErrorException('Módulo inválido!');
            }

            $qbAtual = $con->qb();

            $qbAtual->select('usuario_paginacao_cod')
                    ->from('_usuario_paginacao', '')
                    ->where($qbAtual->expr()->eq('organograma_cod', ':organograma_cod'))
                    ->andWhere($qbAtual->expr()->eq('usuario_cod', ':usuario_cod'))
                    ->andWhere($qbAtual->expr()->eq('modulo_cod', ':modulo_cod'))
                    ->setParameter('organograma_cod', $_SESSION['organograma_cod'])
                    ->setParameter('usuario_cod', $_SESSION['usuario_cod'])
                    ->setParameter('modulo_cod', $moduloCod);

            $dadosAtual = $con->execLinha($qbAtual);

            if (!empty($dadosAtual)) {

                $qbUpdate = $con->qb();

                $qbUpdate->update('_usuario_paginacao')
                        ->set('usuario_paginacao_total', '?')
                        ->where($qbAtual->expr()->eq('organograma_cod', '?'))
                        ->andWhere($qbAtual->expr()->eq('usuario_cod', '?'))
                        ->andWhere($qbAtual->expr()->eq('modulo_cod', '?'))
                        ->setParameter(0, $nLinhas)
                        ->setParameter(1, $_SESSION['organograma_cod'])
                        ->setParameter(2, $_SESSION['usuario_cod'])
                        ->setParameter(3, $moduloCod)
                        ->execute();
            } else {
                $qbInsert = $con->qb();

                $qbInsert->insert('_usuario_paginacao')
                        ->setValue('organograma_cod', '?')
                        ->setValue('usuario_cod', '?')
                        ->setValue('modulo_cod', '?')
                        ->setValue('usuario_paginacao_total', '?')
                        ->setParameter(0, $_SESSION['organograma_cod'])
                        ->setParameter(1, $_SESSION['usuario_cod'])
                        ->setParameter(2, $moduloCod)
                        ->setParameter(3, $nLinhas)
                        ->execute();
            }

            return json_encode(array('sucesso' => 'true'));
        } catch (\Exception $e) {
            return json_encode(array('sucesso' => 'false', 'retorno' => $e->getMessage()));
        }
    }

}
