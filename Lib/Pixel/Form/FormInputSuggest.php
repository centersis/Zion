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

namespace Pixel\Form;

use \Zion\Form\Exception\FormException as FormException;

class FormInputSuggest extends \Zion\Form\FormBasico
{

    private $tipoBase;
    private $acao;
    private $largura;
    private $caixa;
    private $obrigatorio;
    private $idConexao;
    private $tabela;
    private $campoCod;
    private $campoDesc;
    private $campoBusca;
    private $limite;
    private $parametros;
    private $url;
    private $espera;
    private $tamanhoMinimo;
    private $hiddenValue;
    private $onSelect;
    private $converterHtml;
    private $autoTrim;
    private $placeHolder;
    private $iconFA;
    private $toolTipMsg;
    private $emColunaDeTamanho;
    private $layoutPixel;
    private $aliasSql;
    private $processarJS;
    private $tipoFiltro;
    private $formSetPixel;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->formSetPixel = new \Pixel\Form\FormSetPixel();

        $this->tipoBase = 'suggest';
        $this->acao = $acao;
        $this->autoTrim = true;
        $this->converterHtml = true;
        $this->tipoFiltro = 'texto';
        $this->setIconFA('fa-search');
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function setLargura($largura)
    {
        if (!empty($largura)) {
            $this->largura = $largura;
            return $this;
        } else {
            throw new FormException("largura: Nenhum valor informado.");
        }
    }

    public function getLargura()
    {
        return $this->largura;
    }

    public function setCaixa($caixa)
    {
        if (strtoupper($caixa) == "ALTA" or strtoupper($caixa) == "BAIXA") {
            $this->caixa = $caixa;
            return $this;
        } else {
            throw new FormException("caixa: Valor desconhecido: " . $caixa);
        }
    }

    public function setObrigarorio($obrigatorio)
    {
        if (is_bool($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
            return $this;
        } else {
            throw new FormException("obrigatorio: Valor não booleano");
        }
    }

    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    public function getIdConexao()
    {
        return $this->idConexao;
    }

    public function setIdConexao($idConexao)
    {
        if (!is_null($idConexao)) {
            $this->idConexao = $idConexao;
            return $this;
        } else {
            throw new FormException("idConexao: Nenhum Valor foi informado.");
        }
    }

    public function getCaixa()
    {
        return $this->caixa;
    }

    public function getTabela()
    {
        return $this->tabela;
    }

    public function setTabela($tabela)
    {
        if (!empty($tabela)) {
            $this->tabela = $tabela;
            return $this;
        } else {
            throw new FormException("tabela: Nenhum valor informado.");
        }
    }

    public function getCampoCod()
    {
        return $this->campoCod;
    }

    public function setCampoCod($campoCod)
    {
        if (!empty($campoCod)) {
            $this->campoCod = $campoCod;
            return $this;
        } else {
            throw new FormException("campoCod: O valor informado não é um número valido.");
        }
    }

    public function getCampoDesc()
    {
        return $this->campoDesc;
    }

    public function setCampoDesc($campoDesc)
    {
        if (!empty($campoDesc)) {
            $this->campoDesc = $campoDesc;
            return $this;
        } else {
            throw new FormException("campoDesc: Nenhum valor informado.");
        }
    }

    public function getCampoBusca()
    {
        return $this->campoBusca;
    }

    public function setCampoBusca($campoBusca)
    {
        if (!empty($campoBusca)) {
            $this->campoBusca = $campoBusca;
            return $this;
        } else {
            throw new FormException("campoBusca: Nenhum valor informado.");
        }
    }

    public function getLimite()
    {
        return $this->limite;
    }

    public function setLimite($limite)
    {
        if (!empty($limite) and is_numeric($campoCod)) {
            $this->limite = $limite;
            return $this;
        } else {
            throw new FormException("limite: O valor informado não é um número valido.");
        }
    }

    public function getParametros()
    {
        return $this->parametros;
    }

    public function setParametros($parametros)
    {
        if (\is_array($parametros)) {

            $query = '';

            foreach ($parametros as $campo => $valor) {
                $query .= "&" . $campo . "=" . $valor;
            }

            $this->parametros = $query;
        } else {
            throw new FormException("parametros: O valor informado é inválido.");
        }

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        if (!empty($url)) {
            $this->url = $url;
            return $this;
        } else {
            throw new FormException("url: Nenhum valor informado.");
        }
    }

    public function getEspera()
    {
        return $this->espera;
    }

    public function setEspera($espera)
    {
        if (!empty($espera)) {
            $this->espera = $espera;
            return $this;
        } else {
            throw new FormException("espera: Nenhum valor informado.");
        }
    }

    public function getTamanhoMinimo()
    {
        return $this->tamanhoMinimo;
    }

    public function setTamanhoMinimo($tamanhoMinimo)
    {
        if (!empty($tamanhoMinimo)) {
            $this->tamanhoMinimo = $tamanhoMinimo;
            return $this;
        } else {
            throw new FormException("tamanhoMinimo: Nenhum valor informado.");
        }
    }

    public function getHiddenValue()
    {
        return $this->hiddenValue;
    }

    public function setHiddenValue($hiddenValue)
    {
        if (!empty($hiddenValue)) {
            $this->hiddenValue = $hiddenValue;
            return $this;
        } else {
            throw new FormException("hiddenValue: Nenhum valor informado.");
        }
    }

    public function getOnSelect()
    {
        return $this->onSelect;
    }

    public function setOnSelect($onSelect)
    {
        if (!empty($onSelect)) {
            $this->onSelect = $onSelect;
            return $this;
        } else {
            throw new FormException("onSelect: Nenhum valor informado.");
        }
    }

    public function setConverterHtml($converterHtml)
    {
        if (is_bool($converterHtml)) {
            $this->converterHtml = $converterHtml;
            return $this;
        } else {
            throw new FormException("converterHtml: Valor não booleano");
        }
    }

    public function getConverterHtml()
    {
        return $this->converterHtml;
    }

    public function setAutoTrim($autoTrim)
    {
        if (is_bool($autoTrim)) {
            $this->autoTrim = $autoTrim;
            return $this;
        } else {
            throw new FormException("autoTrim: Valor não booleano");
        }
    }

    public function getAutoTrim()
    {
        return $this->autoTrim;
    }

    public function setPlaceHolder($placeHolder)
    {
        if (!empty($placeHolder)) {
            $this->placeHolder = $placeHolder;
            return $this;
        } else {
            throw new FormException("placeHolder: Nenhum valor informado");
        }
    }

    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }

    public function setIconFA($iconFA)
    {
        $this->iconFA = $this->formSetPixel->setIconFA($iconFA);
        return $this;
    }

    public function getIconFA()
    {
        return $this->iconFA;
    }

    public function setToolTipMsg($toolTipMsg)
    {
        $this->toolTipMsg = $this->formSetPixel->setToolTipMsg($toolTipMsg);
        return $this;
    }

    public function getToolTipMsg()
    {
        return $this->toolTipMsg;
    }

    public function setEmColunaDeTamanho($emColunaDeTamanho)
    {
        $this->emColunaDeTamanho = $this->formSetPixel->setEmColunaDeTamanho($emColunaDeTamanho);
        return $this;
    }

    public function getemColunaDeTamanho()
    {
        return $this->emColunaDeTamanho ? $this->emColunaDeTamanho : 12;
    }

    public function setLayoutPixel($layoutPixel)
    {
        $this->layoutPixel = $this->formSetPixel->setLayoutPixel($layoutPixel);
        return $this;
    }

    public function getLayoutPixel()
    {
        return $this->layoutPixel;
    }

    public function setAliasSql($aliasSql)
    {
        if (!is_null($aliasSql)) {
            $this->aliasSql = $aliasSql;
            return $this;
        } else {
            throw new FormException("aliasSql: Nenhum valor informado");
        }
    }

    public function getAliasSql()
    {
        return $this->aliasSql;
    }

    public function setProcessarJS($processarJS)
    {
        $this->processarJS = $this->formSetPixel->setProcessarJS($processarJS);
        return $this;
    }

    public function getProcessarJS()
    {
        return $this->processarJS;
    }

    public function setTipoFiltro($tipoFiltro)
    {
        $this->tipoFiltro = $this->formSetPixel->setTipoFiltro($tipoFiltro);
        return $this;
    }

    public function getTipoFiltro()
    {
        return $this->tipoFiltro;
    }

    /**
     * Sobrecarga de Metodos Básicos
     */
    public function setId($id)
    {
        parent::setId($id);
        return $this;
    }

    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }

    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }

    public function setValor($valor)
    {
        parent::setValor($valor);
        return $this;
    }

    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }

    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }

    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }

    public function setClassCss($classCss)
    {
        parent::setClassCss($classCss);
        return $this;
    }

    public function setContainer($container)
    {
        parent::setContainer($container);
        return $this;
    }

}
