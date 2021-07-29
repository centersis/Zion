<?php

namespace Centersis\Zion\Pixel\Filtro;

use Zion\Banco\Conexao;

class FiltroForm {

    protected $complementoOriginal;
    protected $onSelectOriginal;
    protected $nomeOriginal;
    protected $idOriginal;

    public function __construct() {
        $this->complementoOriginal = [];
        $this->onSelectOriginal = [];
        $this->nomeOriginal = [];
        $this->idOriginal = [];
    }

    public function montaFiltro($objForm = null, $container = '') {
        if (!is_object($objForm)) {
            return ['normal' => null,
                'moduloCod' => null
            ];
        }

        $moduloCod = 0;

        if (defined('MODULO')) {

            $con = Conexao::conectar();

            $qbModulo = $con->qb();

            $qbModulo->select('modulo_cod')
                    ->from('_modulo', '')
                    ->where($qbModulo->expr()->eq('modulo_nome', $qbModulo->expr()->literal(MODULO)));

            $moduloCod = $con->execRLinha($qbModulo);
        }

        return ['normal' => $this->getFiltroNormal($objForm),
            'moduloCod' => $moduloCod,
            'container' => $container
        ];
    }

    protected function getFiltroNormal($objForm) {
        $objetos = $objForm->getObjetos();

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

    protected function getTipoFiltro($tipoFiltro) {
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
