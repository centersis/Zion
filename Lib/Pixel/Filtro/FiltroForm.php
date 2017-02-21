<?php

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

    public function montaFiltro($objForm = null)
    {
        if (!is_object($objForm)) {
            return ['normal' => null,
                'operacaoE' => null,
                'moduloCod' => null
            ];
        }

        $moduloCod = 0;

        if (\defined('MODULO')) {

            $con = Conexao::conectar();

            $qbModulo = $con->qb();

            $qbModulo->select('moduloCod')
                ->from('_modulo', '')
                ->where($qbModulo->expr()->eq('moduloNome', $qbModulo->expr()->literal(\MODULO)));

            $moduloCod = $con->execRLinha($qbModulo);
        }

        $FiltrosE = [];
        if (\method_exists($objForm, 'getOperacaoE')) {
            $FiltrosE = $objForm->getOperacaoE();
        }

        return ['normal' => $this->getFiltroNormal($objForm),
            'operacaoE' => $this->getFiltroDuplo($objForm, 'e', $FiltrosE),
            'moduloCod' => $moduloCod
        ];
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
                'tipoFiltro' => $tipoFiltro,
                'filtroPadrao' => \method_exists($objCampo, 'getFiltroPadrao') ? $objCampo->getFiltroPadrao() : null
            ]);
        }

        return $objeto;
    }

    private function getFiltroDuplo($objForm, $prefixo, $selecionados)
    {
        $objetos = $objForm->getObjetos();
        $objeto = [];

        foreach ($objetos as $nomeObjeto => $objCampo) {

            if (!\in_array($nomeObjeto, $selecionados)) {
                continue;
            }

            \array_push($objeto, $this->getCampoDuplo($objForm, $nomeObjeto, $objCampo, $prefixo, 'A'));
            \array_push($objeto, $this->getCampoDuplo($objForm, $nomeObjeto, $objCampo, $prefixo, 'B'));
        }

        return $objeto;
    }

    private function getCampoDuplo($objForm, $nomeCampo, $objCampo, $prefixo, $sufixo)
    {
        $this->atualizaCampo($nomeCampo, $objCampo, $prefixo, $sufixo);

        $tipoFiltro = ($this->getTipoFiltro($objCampo->getTipoFiltro()));

        return array('campo' => $objCampo->getNome(),
            'campoHtml' => $objForm->getFormHtml($nomeCampo),
            'campoObjeto' => $objCampo,
            'campoJs' => $objForm->processarJSObjeto($objCampo),
            'tipoFiltro' => $tipoFiltro,
            'filtroPadrao' => \method_exists($objCampo, 'getFiltroPadrao') ? $objCampo->getFiltroPadrao() : null
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
            //$objCampos->setComplemento($this->complementoOriginal[$nomeObjeto] . ' onChange="sisChangeFil(\'' . $prefixo . '\')"');

            if ($tipoBase == 'suggest') {
                $this->onSelectOriginal[$nomeObjeto] = $objCampos->getOnSelect();
                //$objCampos->setOnSelect($this->onSelectOriginal[$nomeObjeto] . ' sisChangeFil(\'' . $prefixo . '\');');
            }
        }
    }

    private function atualizaCampo($nomeObjeto, $objCampo, $prefixo = '', $sufixo = '')
    {
        $tipoBase = $objCampo->getTipoBase();

        $objCampo->setNome($prefixo . $this->nomeOriginal[$nomeObjeto] . $sufixo);
        $objCampo->setId($prefixo . $this->idOriginal[$nomeObjeto] . $sufixo);
        //$objCampo->setComplemento($this->complementoOriginal[$nomeObjeto] . ' onChange="sisChangeFil(\'' . $prefixo . '\')"');

        if ($tipoBase == 'suggest') {
            //$objCampo->setOnSelect($this->onSelectOriginal[$nomeObjeto] . ' sisChangeFil(\'' . $prefixo . '\');');
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
