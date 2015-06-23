<?php

/**
 *
 *    Sappiens Framework
 *    Copyright (C) 2014, BRA Consultoria
 *
 *    Website do autor: www.braconsultoria.com.br/sappiens
 *    Email do autor: sappiens@braconsultoria.com.br
 *
 *    Website do projeto, equipe e documentação: www.sappiens.com.br
 *   
 *    Este programa é software livre; você pode redistribuí-lo e/ou
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *    publicada pela Free Software Foundation, versão 2.
 *
 *    Este programa é distribuído na expectativa de ser útil, mas SEM
 *    QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *    detalhes.
 * 
 *    Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *    junto com este programa; se não, escreva para a Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *    02111-1307, USA.
 *
 *    Cópias da licença disponíveis em /Sappiens/_doc/licenca
 *
 */

namespace Pixel\Remoto;

use Zion\Banco\Conexao;

class LinhasGrid
{

    public function alterarLinhas($nLinhas, $moduloCod)
    {
        try {
            $con = Conexao::conectar();

            if ($nLinhas < 1 or $nLinhas > 200) {
                throw new \Exception('Número de linhas inválido!');
            }

            if (!\is_numeric($moduloCod)) {
                throw new \Exception('Módulo inválido!');
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
        } catch (\Exception $e) {
            return \json_encode(array('sucesso' => 'false', 'retorno' => $e->getMessage()));
        }
    }

}
