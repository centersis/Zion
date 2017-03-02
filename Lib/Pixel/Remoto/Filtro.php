<?php

namespace Pixel\Remoto;

use Zion\Banco\Conexao;
use App\Ext\Twig\Carregador;
use Zion\Exception\RuntimeException;
use Zion\Exception\InvalidArgumentException;

class Filtro
{

    public function carregarFiltrosSalvos($moduloCod)
    {
        try {
            $carregador = new Carregador();
            $con = Conexao::conectar();

            $qbFiltro = $con->qb();

            $qbFiltro->select('usuarioFiltroCod', 'usuarioFiltroNome', 'usuarioFiltroQueryString')
                ->from('_usuario_filtro', '')
                ->where($qbFiltro->expr()->eq('organogramaCod', ':organogramaCod'))
                ->andWhere($qbFiltro->expr()->eq('usuarioCod', ':usuarioCod'))
                ->andWhere($qbFiltro->expr()->eq('moduloCod', ':moduloCod'))
                ->setParameter('organogramaCod', $_SESSION['organogramaCod'])
                ->setParameter('usuarioCod', $_SESSION['usuarioCod'])
                ->setParameter('moduloCod', $moduloCod);

            $filtros = $con->paraArray($qbFiltro);

            if (empty($filtros)) {
                $retorno = '<i class="fa fa-exclamation-triangle"></i> nenhum filtro salvo foi encontrado!';
            } else {
                $retorno = $carregador->render('filtros_salvos.html.twig', [
                    'salvo' => $filtros,
                    'moduloCod' => $moduloCod
                ]);
            }

            return \json_encode(array('sucesso' => 'true', 'retorno' => $retorno));
        } catch (\Exception $e) {
            return \json_encode(array('sucesso' => 'false', 'retorno' => $e->getMessage()));
        }
    }

    public function removerFiltroSalvo($usuarioFiltroCod)
    {
        try {
            $con = Conexao::conectar();

            if (!\is_numeric($usuarioFiltroCod)) {
                throw new RuntimeException('Código de referência inválido!');
            }

            $qbDelete = $con->qb();

            $qbDelete->delete('_usuario_filtro')
                ->where($qbDelete->expr()->eq('organogramaCod', ':organogramaCod'))
                ->andWhere($qbDelete->expr()->eq('usuarioCod', ':usuarioCod'))
                ->andWhere($qbDelete->expr()->eq('usuarioFiltroCod', ':usuarioFiltroCod'))
                ->setParameter('organogramaCod', $_SESSION['organogramaCod'])
                ->setParameter('usuarioCod', $_SESSION['usuarioCod'])
                ->setParameter('usuarioFiltroCod', $usuarioFiltroCod)
                ->execute();

            return \json_encode(array('sucesso' => 'true'));
        } catch (\Exception $e) {
            return \json_encode(array('sucesso' => 'false', 'retorno' => $e->getMessage()));
        }
    }

    public function salvar($usuarioFiltroNome, $usuarioFiltroNomeRelatorio, $usuarioFiltroColunas, $usuarioFiltroQueryString, $moduloCod)
    {
        try {
            $con = Conexao::conectar();

            if (\strlen($usuarioFiltroNome) < 1 or \strlen($usuarioFiltroNome) > 100) {
                throw new InvalidArgumentException('Informe o nome do filtro corretamente!');
            }

            if (\strlen($usuarioFiltroNomeRelatorio) > 100) {
                throw new InvalidArgumentException('Informe o titulo para impressão corretamente!');
            }

            if (\strlen($usuarioFiltroColunas) < 1 or \strlen($usuarioFiltroColunas) > 1000) {
                throw new InvalidArgumentException('Nenhuma coluna encontrada!');
            }

            if ($usuarioFiltroQueryString) {
                $usuarioFiltroQueryString = \urldecode($usuarioFiltroQueryString);
            }

            if (!\is_numeric($moduloCod)) {
                throw new RuntimeException('Módulo inválido!');
            }

            $qbInsert = $con->qb();

            $qbInsert->insert('_usuario_filtro')
                ->setValue('organogramaCod', '?')
                ->setValue('usuarioCod', '?')
                ->setValue('moduloCod', '?')
                ->setValue('usuarioFiltroNome', '?')
                ->setValue('usuarioFiltroNomeRelatorio', '?')
                ->setValue('usuarioFiltroColunas', '?')
                ->setValue('usuarioFiltroQueryString', '?')
                ->setParameter(0, $_SESSION['organogramaCod'])
                ->setParameter(1, $_SESSION['usuarioCod'])
                ->setParameter(2, $moduloCod)
                ->setParameter(3, $usuarioFiltroNome, \PDO::PARAM_STR)
                ->setParameter(4, $usuarioFiltroNomeRelatorio, \PDO::PARAM_STR)
                ->setParameter(5, $usuarioFiltroColunas, \PDO::PARAM_STR)
                ->setParameter(6, $usuarioFiltroQueryString, \PDO::PARAM_STR)
                ->execute();

            return \json_encode(array('sucesso' => 'true'));
        } catch (\Exception $e) {
            return \json_encode(array('sucesso' => 'false', 'retorno' => $e->getMessage()));
        }
    }

}
