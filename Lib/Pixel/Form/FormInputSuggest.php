<?php

namespace Pixel\Form;

use Zion\Exception\ErrorException;
use Zion\Form\FormBasico;
use Pixel\Form\FormSetPixel;

class FormInputSuggest extends FormBasico
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
    private $hidden;
    private $hiddenSql;
    private $onSelect;
    private $converterHtml;
    private $autoTrim;
    private $placeHolder;
    private $iconFA;
    private $toolTipMsg;
    private $emColunaDeTamanho;
    private $offsetColuna;
    private $maximoCaracteres;
    private $minimoCaracteres;
    private $aliasSql;
    private $processarJS;
    private $complementoExterno;
    private $tipoFiltro;
    private $hashAjuda;
    private $formSetPixel;
    private $method;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->formSetPixel = new FormSetPixel();

        $this->tipoBase = 'suggest';
        $this->acao = $acao;
        $this->autoTrim = true;
        $this->converterHtml = true;
        $this->tipoFiltro = 'texto';
        $this->setIconFA('fa-search');
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigatorio($obrigatorio);
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
            throw new ErrorException("largura: Nenhum valor informado.");
        }
    }

    public function getLargura()
    {
        return $this->largura;
    }

    /**
     * FormInputTexto::setMaximoCaracteres()
     * 
     * @param mixed $maximoCaracteres
     * @return
     */
    public function setMaximoCaracteres($maximoCaracteres)
    {
        if (\is_numeric($maximoCaracteres)) {

            if (isset($this->minimoCaracteres) and ( $maximoCaracteres < $this->minimoCaracteres)) {
                throw new ErrorException("maximoCaracteres não pode ser menor que minimoCaracteres.");
            }
            $this->maximoCaracteres = $maximoCaracteres;
            return $this;
        } else {
            throw new ErrorException("maximoCaracteres: Valor não numerico.");
        }
    }

    /**
     * FormInputTexto::getMaximoCaracteres()
     * 
     * @return
     */
    public function getMaximoCaracteres()
    {
        return $this->maximoCaracteres;
    }

    /**
     * FormInputTexto::setMinimoCaracteres()
     * 
     * @param mixed $minimoCaracteres
     * @return
     */
    public function setMinimoCaracteres($minimoCaracteres)
    {
        if (\is_numeric($minimoCaracteres)) {

            if (isset($this->maximoCaracteres) and ( $minimoCaracteres > $this->maximoCaracteres)) {
                throw new ErrorException("minimoCaracteres não pode ser maior que maximoCaracteres.");
            }

            $this->minimoCaracteres = $minimoCaracteres;
            return $this;
        } else {
            throw new ErrorException("minimoCaracteres: Valor não numérico.");
        }
    }

    /**
     * FormInputTexto::getMinimoCaracteres()
     * 
     * @return
     */
    public function getMinimoCaracteres()
    {
        return $this->minimoCaracteres;
    }

    public function setCaixa($caixa)
    {
        if (\strtoupper($caixa) == "ALTA" or \strtoupper($caixa) == "BAIXA") {
            $this->caixa = $caixa;
            return $this;
        } else {
            throw new ErrorException("caixa: Valor desconhecido: " . $caixa);
        }
    }

    public function setObrigatorio($obrigatorio)
    {
        if (is_bool($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
            return $this;
        } else {
            throw new ErrorException("obrigatorio: Valor não booleano");
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
            throw new ErrorException("idConexao: Nenhum Valor foi informado.");
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
            throw new ErrorException("tabela: Nenhum valor informado.");
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
            throw new ErrorException("campoCod: O valor informado não é um número valido.");
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
            throw new ErrorException("campoDesc: Nenhum valor informado.");
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
            throw new ErrorException("campoBusca: Nenhum valor informado.");
        }
    }

    public function getLimite()
    {
        return $this->limite;
    }

    public function setLimite($limite)
    {
        if (!empty($limite) and is_numeric($limite)) {
            $this->limite = $limite;
            return $this;
        } else {
            throw new ErrorException("limite: O valor informado não é um número valido.");
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
            throw new ErrorException("parametros: O valor informado é inválido.");
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
            throw new ErrorException("url: Nenhum valor informado.");
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
            throw new ErrorException("espera: Nenhum valor informado.");
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
            throw new ErrorException("tamanhoMinimo: Nenhum valor informado.");
        }
    }

    public function getHidden()
    {
        return $this->hidden;
    }

    public function setHidden($hidden)
    {
        if (\is_bool($hidden)) {
            $this->hidden = $hidden;
            return $this;
        } else {
            throw new ErrorException("hidden: Nenhum valor informado.");
        }
    }

    public function getHiddenSql()
    {
        return $this->hiddenSql;
    }

    public function setHiddenSql($hiddenSql)
    {
        $this->hiddenSql = $hiddenSql;

        return $this;
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
            throw new ErrorException("onSelect: Nenhum valor informado.");
        }
    }

    public function setConverterHtml($converterHtml)
    {
        if (is_bool($converterHtml)) {
            $this->converterHtml = $converterHtml;
            return $this;
        } else {
            throw new ErrorException("converterHtml: Valor não booleano");
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
            throw new ErrorException("autoTrim: Valor não booleano");
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
            throw new ErrorException("placeHolder: Nenhum valor informado");
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

    public function setOffsetColuna($offsetColuna)
    {
        $this->offsetColuna = $this->formSetPixel->setOffsetColuna($offsetColuna);
        return $this;
    }

    public function getOffsetColuna()
    {
        return $this->offsetColuna ? $this->offsetColuna : 3;
    }

    public function setAliasSql($aliasSql)
    {
        if (!is_null($aliasSql)) {
            $this->aliasSql = $aliasSql;
            return $this;
        } else {
            throw new ErrorException("aliasSql: Nenhum valor informado");
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

    public function setFiltroPadrao($filtroPadrao)
    {
        parent::setFiltroPadrao($filtroPadrao);
        return $this;
    }

    public function setComplementoExterno($complementoExterno)
    {
        $this->complementoExterno = $this->formSetPixel->setComplementoExterno($complementoExterno);
        return $this;
    }

    public function getComplementoExterno()
    {
        return $this->complementoExterno;
    }

    public function setHashAjuda($hashAjuda)
    {
        $this->hashAjuda = $this->formSetPixel->setHashAjuda($hashAjuda);
        return $this;
    }

    public function getHashAjuda()
    {
        return $this->hashAjuda;
    }

    public function setMethod($method)
    {
        $this->method = \strtoupper($method);
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sobrecarga de Methods Básicos
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
