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

namespace Pixel\Filtro;

use Zion\Banco\Conexao;

class FiltroForm
{

    private $complementoOriginal;
    private $onSelectOriginal;
    private $nomeOriginal;
    private $idOriginal;

    public function __construct()
    {
        $this->complementoOriginal = [];
        $this->onSelectOriginal = [];
        $this->nomeOriginal = [];
        $this->idOriginal = [];
    }

    public function montaFiltro($objForm)
    {
        $moduloCod = 0;
        if (\defined('MODULO')) {

            $con = Conexao::conectar();

            $qbModulo = $con->qb();

            $qbModulo->select('moduloCod')
                    ->from('_modulo', '')
                    ->where($qbModulo->expr()->eq('moduloNome', $qbModulo->expr()->literal(\MODULO)));

            $moduloCod = $con->execRLinha($qbModulo);
        }

        return array('normal' => $this->getFiltroNormal($objForm),
            'operacaoE' => $this->getFiltroDuplo($objForm, 'e'),
            'moduloCod' => $moduloCod
        );
    }

    private function getFiltroNormal($objForm)
    {
        $objetos = $objForm->getObjetos();

        $prefixo = 'n';
        $this->atualizaCampos($objForm, $prefixo);

        $objeto = array();


        foreach ($objetos as $nomeObjeto => $objCampo) {

            $nomeCampo = $objCampo->getNome();

            $tipoFiltro = ($this->getTipoFiltro($objCampo->getTipoFiltro()));

            //Campo
            \array_push($objeto, [ 'campo' => $nomeCampo,
                'campoHtml' => $objForm->getFormHtml($nomeObjeto),
                'campoObjeto' => $objCampo,
                'campoJs' => $objForm->processarJSObjeto($objCampo),
                'tipoFiltro' => $tipoFiltro
            ]);
        }

        return $objeto;
    }

    private function getFiltroDuplo($objForm, $prefixo)
    {
        $objetos = $objForm->getObjetos();
        $objeto = [];

        foreach ($objetos as $nomeObjeto => $objCampo) {

            \array_push($objeto, $this->getCampoDuplo($objForm, $nomeObjeto, $objCampo, $prefixo, 'A'));
            \array_push($objeto, $this->getCampoDuplo($objForm, $nomeObjeto, $objCampo, $prefixo, 'B'));
        }

        return $objeto;
    }

    private function getCampoDuplo($objForm, $nomeCampo, $objCampo, $prefixo, $sufixo)
    {
        $this->atualizaCampo($nomeCampo, $objCampo, $prefixo, $sufixo);

        $tipoFiltro = \key($this->getTipoFiltro($objCampo->getTipoFiltro()));

        return array('campo' => $objCampo->getNome(),
            'campoHtml' => $objForm->getFormHtml($nomeCampo),
            'campoObjeto' => $objCampo,
            'campoJs' => $objForm->processarJSObjeto($objCampo),
            'tipoFiltro' => $tipoFiltro,
        );
    }

    private function atualizaCampos($objForm, $prefixo = '', $sufixo = '')
    {
        $obj = $objForm->getObjetos();

        foreach ($obj as $nomeObjeto => $objCampos) {

            $tipoBase = $objCampos->getTipoBase();

            $this->nomeOriginal[$nomeObjeto] = $objCampos->getNome();
            $this->idOriginal[$nomeObjeto] = $objCampos->getId();
            $this->complementoOriginal[$nomeObjeto] = $objCampos->getComplemento();

            $objCampos->setNome($prefixo . $this->nomeOriginal[$nomeObjeto] . $sufixo);
            $objCampos->setId($prefixo . $this->idOriginal[$nomeObjeto] . $sufixo);
            $objCampos->setComplemento($this->complementoOriginal[$nomeObjeto] . ' onChange="sisChangeFil(\'' . $prefixo . '\')"');

            if ($tipoBase == 'suggest') {
                $this->onSelectOriginal[$nomeObjeto] = $objCampos->getOnSelect();
                $objCampos->setOnSelect($this->onSelectOriginal[$nomeObjeto] . ' sisChangeFil(\'' . $prefixo . '\');');
            }
        }
    }

    private function atualizaCampo($nomeObjeto, $objCampo, $prefixo = '', $sufixo = '')
    {
        $tipoBase = $objCampo->getTipoBase();

        $objCampo->setNome($prefixo . $this->nomeOriginal[$nomeObjeto] . $sufixo);
        $objCampo->setId($prefixo . $this->idOriginal[$nomeObjeto] . $sufixo);
        $objCampo->setComplemento($this->complementoOriginal[$nomeObjeto] . ' onChange="sisChangeFil(\'' . $prefixo . '\')"');

        if ($tipoBase == 'suggest') {
            $objCampo->setOnSelect($this->onSelectOriginal[$nomeObjeto] . ' sisChangeFil(\'' . $prefixo . '\');');
        }
    }

    private function getTipoFiltro($tipoFiltro)
    {
        $igual = ['=' => 'Igual a'];
        $diferente = ['<>' => 'Diferente de'];
        $menor = ['<' => 'Menor que'];
        $menorIgual = ['<=' => 'Menor ou igual que'];
        $maior = ['>' => 'Maior que'];
        $maiorIgual = ['>=' => 'Maior ou igual que'];
        $coringa = ['*' => 'Coringa'];
        $coringaAntes = ['*A' => 'Coringa antes'];
        $coringaDepois = ['A*' => 'Coringa após'];

        switch (\strtolower($tipoFiltro)) {
            case "valorvariavel":
                return \array_merge($igual, $diferente, $menor, $menorIgual, $maior, $maiorIgual);

            case "texto":
                return \array_merge($igual, $diferente, $coringa, $coringaAntes, $coringaDepois);

            case "valorfixo":
                return \array_merge($igual, $diferente);

            case "igual":
                return $igual;

            case "diferente":
                return $diferente;

            default: return [];
        }
    }

}
