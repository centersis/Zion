<?php

namespace Pixel\Form;

use \Zion\Form\Exception\FormException as FormException;

class FormInputTexto extends \Zion\Form\FormInputTexto
{
    private $iconFA;
    private $toolTipMsg;
    private $emColunaDeTamanho;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        parent::__construct($acao, $nome, $identifica, $obrigatorio); 
        
        $this->setEmColunaDeTamanho(12);
    }

    public function getTipoBase()
    {
        return parent::getTipoBase();
    }

    public function getAcao()
    {
        return parent::getAcao();
    }

    public function setLargura($largura)
    {
        parent::setLargura($largura);
        return $this;
    }

    public function getLargura()
    {
        return $this->largura;
    }

    public function setMaximoCaracteres($maximoCaracteres)
    {
        parent::setMaximoCaracteres($maximoCaracteres);
        return $this;
    }

    public function getMaximoCaracteres()
    {
        return parent::getMaximoCaracteres();
    }

    public function setMinimoCaracteres($minimoCaracteres)
    {
        parent::setMinimoCaracteres($minimoCaracteres);
        return $this;
    }

    public function getMinimoCaracteres()
    {
        return parent::getMinimoCaracteres();
    }

    public function setCaixa($caixa)
    {
        parent::setCaixa($caixa);
        return $this;
    }

    public function getCaixa()
    {
        return parent::getCaixa();
    }

    public function setValorMinimo($valorMinimo)
    {
        parent::setValorMinimo($valorMinimo);
        return $this;
    }

    public function getValorMinimo()
    {
        return parent::getValorMinimo();
    }

    public function setValorMaximo($valorMaximo)
    {
        parent::setValorMaximo($valorMaximo);
        return $this;
    }

    public function getValorMaximo()
    {
        return parent::getValorMaximo();
    }

    public function setMascara($mascara)
    {
        parent::setMascara($mascara);
        return $this;
    }

    public function getMascara()
    {
        return parent::getMascara();
    }

    public function setObrigarorio($obrigatorio)
    {
        parent::setObrigarorio($obrigatorio);
        return $this;
    }

    public function getObrigatorio()
    {
        return parent::getObrigatorio();
    }

    public function setConverterHtml($converterHtml)
    {
        parent::setConverterHtml($converterHtml);
        return $this;
    }

    public function getConverterHtml()
    {
        return parent::getConverterHtml();
    }

    public function setAutoTrim($autoTrim)
    {
        parent::setAutoTrim($autoTrim);
        return $this;
    }

    public function getAutoTrim()
    {
        return parent::getAutoTrim();
    }

    public function setPlaceHolder($placeHolder)
    {
        parent::setPlaceHolder($placeHolder);
        return $this;
    }

    public function getPlaceHolder()
    {
        return parent::getPlaceHolder();
    }

    public function setAutoComplete($autoComplete)
    {
        parent::setAutoComplete($autoComplete);
        return $this;
    }

    public function getAutoComplete()
    {
        return parent::getAutoComplete();
    }

    public function setDeveSerIgualA($deveSerIgualA)
    {
        parent::setDeveSerIgualA($deveSerIgualA);
        return $this;
    }

    public function getDeveSerIgualA()
    {
        return parent::getDeveSerIgualA();
    }

    public function setIconFA($iconFA)
    {
        if (!empty($iconFA)) {
            $this->iconFA = $iconFA;
            return $this;
        } else {
            throw new FormException("iconFA: Nenhum valor informado");
        }
    }

    public function getIconFA()
    {
        return $this->iconFA;
    }

    public function setToolTipMsg($toolTipMsg)
    {
        if (!empty($toolTipMsg)) {
            $this->toolTipMsg = $toolTipMsg;
            return $this;
        } else {
            throw new FormException("toolTipMsg: Nenhum valor informado");
        }
    }

    public function getToolTipMsg()
    {
        return $this->toolTipMsg;
    }

    public function setEmColunaDeTamanho($emColunaDeTamanho)
    {
        if (in_array($emColunaDeTamanho, range(1, 12))) {
            $this->emColunaDeTamanho = $emColunaDeTamanho;
            return $this;
        } else {
            throw new FormException("emColunaDeTamanho: Use variação de 1 a 12");
        }
    }

    public function getEmColunaDeTamanho()
    {
        return $this->emColunaDeTamanho ? $this->emColunaDeTamanho : 12;
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
