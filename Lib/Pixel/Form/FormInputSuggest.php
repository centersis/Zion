<?php

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
    private $condicao;
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

    private $formSetPixel;
    
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->formSetPixel = new \Pixel\Form\FormSetPixel();
        
        $this->tipoBase = 'suggest';
        $this->acao = $acao;
        $this->autoTrim = true;
        $this->converterHtml = true;
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
            throw new FormException("obrigatorio: Valor nao booleano");
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
        $this->tabela = $tabela;
        return $this;
    }

    public function getCampoCod()
    {
        return $this->campoCod;
    }

    public function setCampoCod($campoCod)
    {
        $this->campoCod = $campoCod;
        return $this;
    }

    public function getCampoDesc()
    {
        return $this->campoDesc;
    }

    public function setCampoDesc($campoDesc)
    {
        $this->campoDesc = $campoDesc;
        return $this;
    }

    public function getCampoBusca()
    {
        return $this->campoBusca;
    }

    public function setCampoBusca($campoBusca)
    {
        $this->campoBusca = $campoBusca;
        return $this;
    }

    public function getCondicao()
    {
        return $this->condicao;
    }

    public function setCondicao($condicao)
    {
        $this->condicao = $condicao;
        return $this;
    }

    public function getLimite()
    {
        return $this->limite;
    }

    public function setLimite($limite)
    {
        $this->limite = $limite;
        return $this;
    }

    public function getParametros()
    {
        return $this->parametros;
    }

    public function setParametros($parametros)
    {
        $this->parametros = $parametros;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getEspera()
    {
        return $this->espera;
    }

    public function setEspera($espera)
    {
        $this->espera = $espera;
        return $this;
    }

    public function getTamanhoMinimo()
    {
        return $this->tamanhoMinimo;
    }

    public function setTamanhoMinimo($tamanhoMinimo)
    {
        $this->tamanhoMinimo = $tamanhoMinimo;
        return $this;
    }

    public function getHiddenValue()
    {
        return $this->hiddenValue;
    }

    public function setHiddenValue($hiddenValue)
    {
        $this->hiddenValue = $hiddenValue;
        return $this;
    }

    public function getOnSelect()
    {
        return $this->onSelect;
    }

    public function setOnSelect($onSelect)
    {
        $this->onSelect = $onSelect;
        return $this;
    }

    public function setConverterHtml($converterHtml)
    {
        if (is_bool($converterHtml)) {
            $this->converterHtml = $converterHtml;
            return $this;
        } else {
            throw new FormException("converterHtml: Valor nao booleano");
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
            throw new FormException("autoTrim: Valor nao booleano");
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
        return $this->emColunaDeTamanho;
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

}
