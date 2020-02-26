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

    public function montaFiltro($objForm = null, $container = '')
    {
        if (!is_object($objForm)) {
            return ['normal' => null,
                'moduloCod' => null
            ];
        }

        $moduloCod = 0;

        if (defined('MODULO')) {

            $con = Conexao::conectar();

            $qbModulo = $con->qb();

            $qbModulo->select('moduloCod')
                ->from('_modulo', '')
                ->where($qbModulo->expr()->eq('moduloNome', $qbModulo->expr()->literal(MODULO)));

            $moduloCod = $con->execRLinha($qbModulo);
        }

        return ['normal' => $this->getFiltroNormal($objForm),
            'moduloCod' => $moduloCod,
            'container' => $container
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

            array_push($objeto, ['campo' => $nomeCampo,
                'campoHtml' => $objForm->getFormHtml($nomeObjeto),
                'campoObjeto' => $objCampo,
                'campoJs' => $objForm->processarJSObjeto($objCampo),
                'tipoFiltro' => $tipoFiltro,
                'filtroPadrao' => method_exists($objCampo, 'getFiltroPadrao') ? $objCampo->getFiltroPadrao() : null
            ]);
        }

        return $objeto;
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

            if ($tipoBase == 'suggest') {
                $this->onSelectOriginal[$nomeObjeto] = $objCampos->getOnSelect();
            }
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
        $coringaTodos = ['**' => 'Coringa todos'];
        $coringaAntes = ['*A' => 'Coringa antes'];
        $coringaDepois = ['A*' => 'Coringa após'];
        $entreValores = ['E' => 'Entre valores'];

        switch (strtolower($tipoFiltro)) {
            case "valorvariavel":
                return array_merge($igual, $diferente, $menor, $menorIgual, $maior, $maiorIgual, $entreValores);

            case "texto":
                return array_merge($igual, $diferente, $coringa, $coringaTodos, $coringaAntes, $coringaDepois);

            case "valorfixo":
                return array_merge($igual, $diferente);

            case "igual":
                return $igual;

            case "diferente":
                return $diferente;

            default: return [];
        }
    }

}
