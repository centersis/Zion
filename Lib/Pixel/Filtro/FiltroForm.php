<?php
/*

    Sappiens Framework
    Copyright (C) 2014, BRA Consultoria

    Website do autor: www.braconsultoria.com.br/sappiens
    Email do autor: sappiens@braconsultoria.com.br

    Website do projeto, equipe e documentação: www.sappiens.com.br
   
    Este programa é software livre; você pode redistribuí-lo e/ou
    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
    publicada pela Free Software Foundation, versão 2.

    Este programa é distribuído na expectativa de ser útil, mas SEM
    QUALQUER GARANTIA; sem mesmo a garantia implícita de
    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
    detalhes.
 
    Você deve ter recebido uma cópia da Licença Pública Geral GNU
    junto com este programa; se não, escreva para a Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
    02111-1307, USA.

    Cópias da licença disponíveis em /Sappiens/_doc/licenca

*/

namespace Pixel\Filtro;

class FiltroForm
{

    private $html;
    private $js;
    private $complementoOriginal;
    private $onSelectOriginal;
    private $nomeOriginal;
    private $idOriginal;

    public function __construct()
    {
        $this->html = new \Zion\Layout\Html();
        $this->js = [];

        $this->complementoOriginal = [];
        $this->onSelectOriginal = [];
        $this->nomeOriginal = [];
        $this->idOriginal = [];
    }

    public function montaFiltro($objForm)
    {
        $template = new \Pixel\Template\Template();
        $javascript = new \Zion\Layout\JavaScript();

        $tabFiltro = new \Pixel\Layout\Tab('tabFiltro', '12');

        $html = $objForm->abreFormFiltro();

        $tabFiltro->config('1')
                ->setAtiva(true)
                ->setOnClick('sisChangeFil(\'n\')')
                ->setTitulo('Filtros especiais' .
                        $template->getBadge(['id' => 'N', 'tipo' => 'danger'], 0))
                ->setConteudo($this->getFiltroNormal($objForm));

        $tabFiltro->config('2')
                ->setOnClick('sisChangeFil(\'e\')')
                ->setTitulo('Filtros de operação ' .
                        $template->getLabel(['id' => 'tabEQUE', 'tipo' => 'warning'], "E QUE") .
                        $template->getBadge(['id' => 'E', 'tipo' => 'danger'], 0))
                ->setConteudo($this->getFiltroDuplo($objForm, 'e'));

        $tabFiltro->config('3')
                ->setOnClick('sisChangeFil(\'o\')')
                ->setTitulo('Filtros de operação ' .
                        $template->getLabel(['id' => 'tabOUQUE', 'tipo' => 'warning'], "OU QUE") .
                        $template->getBadge(['id' => 'O', 'tipo' => 'danger'], 0))
                ->setConteudo($this->getFiltroDuplo($objForm, 'o'));

        $html .= $tabFiltro->criar();

        $html .= '</form>';

        $html.= $javascript->entreJS($javascript->abreLoadJQuery() . implode('', $this->js) . $javascript->fechaLoadJQuery());

        return $html;
    }

    private function getFiltroNormal($objForm)
    {
        $objetos = $objForm->getObjetos();

        $prefixo = 'n';
        $this->atualizaCampos($objForm, $prefixo);

        $buffer = $this->html->abreTagAberta('div', ['class' => 'form-group']);

        foreach ($objetos as $nomeObjeto => $objCampo) {

            $objCampo->setLayoutPixel(false);
            $nomeCampo = $objCampo->getNome();

            $tipoFiltro = key($this->getTipoFiltro($objCampo->getTipoFiltro()));
            $acao = $objCampo->getAcao();

            $buffer .= $this->html->abreTagAberta('div', ['class' => 'col-sm-6']);
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'input-group']);

            $buffer .= $this->html->abreTagAberta('div', ['class' => 'input-group-btn']);
            $buffer .= $this->html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn btn-default', 'tabindex' => '-1']);
            $buffer .= $objCampo->getIdentifica();
            $buffer .= $this->html->fechaTag('button');

            $buffer .= $this->html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn dropdown-toggle btn-warning', 'data-toggle' => 'dropdown']);
            $buffer .= $this->html->abreTagAberta('span', ['id' => 'sisIcFil' . $nomeCampo, 'class' => 'fa fa-caret-down']);
            $buffer .= $tipoFiltro;
            $buffer .= $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('button');

            $buffer .= $this->html->abreTagAberta('ul', ['class' => 'dropdown-menu']);

            $buffer.= $this->opcoesDeFiltro($objCampo->getTipoFiltro(), $nomeCampo, 'n');

            $buffer .= $this->html->fechaTag('ul');
            $buffer .= $this->html->fechaTag('div');

            //Campo
            $buffer .= $objForm->getFormHtml($nomeObjeto);

            //Hiddens - sisHiddenOperador = sho e sisHiddenAcao = sha
            $buffer .= $this->html->abreTagFechada('input', ['name' => 'sho' . $nomeCampo, 'id' => 'sho' . $nomeCampo, 'type' => 'hidden', 'value' => $tipoFiltro]);
            $buffer .= $this->html->abreTagFechada('input', ['name' => 'sha' . $nomeCampo, 'id' => 'sha' . $nomeCampo, 'type' => 'hidden', 'value' => $this->getAcao($acao)]);

            $buffer .= $this->html->fechaTag('div');
            $buffer .= $this->html->fechaTag('div');

            $this->js[] = $objForm->processarJSObjeto($objCampo);
        }
        $buffer .= $this->html->fechaTag('div');



        return $buffer;
    }

    private function getFiltroDuplo($objForm, $prefixo)
    {
        $this->html = new \Zion\Layout\Html();

        $objetos = $objForm->getObjetos();

        $buffer = $this->html->abreTagAberta('form', ['class' => 'form-horizontal']);
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'form-group']);

        $mascara = $prefixo === 'e' ? ' E QUE ' : ' OU QUE ';

        foreach ($objetos as $nomeObjeto => $objCampo) {

            // por questoes de alinhamento, o primeiro campo é col-sm-5 e o segundo é col-sm-6        
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'col-sm-5']);
            $buffer .= $this->getCampoDuplo($objForm, $nomeObjeto, $objCampo, $prefixo, 'A');
            $buffer .= $this->html->fechaTag('div');
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'col-sm-1']);
            $buffer .= $this->html->abreTagAberta('span', ['class' => 'label label-warning marE10px']) . $mascara . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('div');
            // por questoes de alinhamento, o primeiro campo é col-sm-5 e o segundo é col-sm-6
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'col-sm-6']);
            $buffer .= $this->getCampoDuplo($objForm, $nomeObjeto, $objCampo, $prefixo, 'B');
            $buffer .= $this->html->fechaTag('div');
        }

        $buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

    private function getCampoDuplo($objForm, $nomeCampo, $objCampo, $prefixo, $sufixo)
    {
        $this->atualizaCampo($nomeCampo, $objCampo, $prefixo, $sufixo);

        $objCampo->setLayoutPixel(false);

        $tipoFiltro = key($this->getTipoFiltro($objCampo->getTipoFiltro()));
        $acao = $objCampo->getAcao();

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'input-group']);
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'input-group-btn']);
        $buffer .= $this->html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn btn-default', 'tabindex' => '-1']);
        $buffer .= $objCampo->getIdentifica();
        $buffer .= $this->html->fechaTag('button');

        $buffer .= $this->html->abreTagAberta('button', ['type' => 'button', 'class' => 'btn dropdown-toggle btn-warning', 'data-toggle' => 'dropdown']);
        $buffer .= $this->html->abreTagAberta('span', ['id' => 'sisIcFil' . $objCampo->getNome(), 'class' => 'fa fa-caret-down']);
        $buffer .= $tipoFiltro;
        $buffer .= $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('button');

        $buffer .= $this->html->abreTagAberta('ul', ['class' => 'dropdown-menu']);

        $buffer.= $this->opcoesDeFiltro($objCampo->getTipoFiltro(), $objCampo->getNome(), $prefixo);

        $buffer .= $this->html->fechaTag('ul');
        $buffer .= $this->html->fechaTag('div');

        $buffer .= $objForm->getFormHtml($nomeCampo);
        //Hiddens - sisHiddenOperador = sho e sisHiddenAcao = sha
        $buffer .= $this->html->abreTagFechada('input', ['name' => 'sho' . $prefixo . $nomeCampo . $sufixo, 'id' => 'sho' . $prefixo . $nomeCampo . $sufixo, 'type' => 'hidden', 'value' => $tipoFiltro]);
        $buffer .= $this->html->abreTagFechada('input', ['name' => 'sha' . $prefixo . $nomeCampo . $sufixo, 'id' => 'sha' . $prefixo . $nomeCampo . $sufixo, 'type' => 'hidden', 'value' => $this->getAcao($acao)]);

        $buffer .= $this->html->fechaTag('div');

        $this->js[] = $objForm->processarJSObjeto($objCampo);

        return $buffer;
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

    private function opcoesDeFiltro($tipoFiltro, $nomeCampo, $prefixo)
    {
        $buffer = '';
        $tipos = $this->getTipoFiltro($tipoFiltro);

        foreach ($tipos as $tipo => $descricao) {

            $buffer .= $this->html->abreTagAberta('li');
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'onclick' => 'sisOpFiltro(\'' . $nomeCampo . '\',\'' . $tipo . '\',\'' . $prefixo . '\');'));
            $buffer .= $this->html->abreTagAberta('span', array('class' => 'label label-warning')) . $tipo . $this->html->fechaTag('span');
            $buffer .= $this->html->abreTagAberta('span', array('class' => 'recE20px italico')) . $descricao . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
            $buffer .= $this->html->fechaTag('li');
        }

        return $buffer;
    }

    private function getAcao($acao)
    {
        switch ($acao) {
            case 'escolha':
                $novaAcao = 'texto';
                break;
            case 'number': case 'float':
                $novaAcao = 'number';
            default : $novaAcao = 'texto';
        }

        return $novaAcao;
    }

    private function getTipoFiltro($tipoFiltro)
    {
        $igual = ['=' => 'Igual a'];
        $diferente = ['≠' => 'Diferente de'];
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
