<?php

namespace Pixel\Form;

use \Zion\Form\Exception\FormException as FormException;

class FormUpload extends \Zion\Form\FormUpload
{

    private $iconFA;
    private $toolTipMsg;
    private $emColunaDeTamanho;
    private $layoutPixel;
    private $processarJS;
    private $codigoReferencia;
    private $alturaMaxima;
    private $larguraMaxima;
    private $thumbnail;
    private $alturaMaximaTB;
    private $larguraMaximaTB;
    private $extensoesPermitidas;
    private $extensoesNaoPermitidas;
    private $tamanhoMaximoEmBytes;
    private $minimoArquivos;
    private $maximoArquivos;
    private $formSetPixel;

    public function __construct($acao, $nome, $identifica, $tratarComo)
    {
        parent::__construct($acao, $nome, $identifica, $tratarComo);

        $this->formSetPixel = new \Pixel\Form\FormSetPixel();

        $this->setIconFA('fa-file');
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

    public function getEmColunaDeTamanho()
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

    public function setProcessarJS($processarJS)
    {
        $this->processarJS = $this->formSetPixel->setProcessarJS($processarJS);
        return $this;
    }

    public function getProcessarJS()
    {
        return $this->processarJS;
    }
    
    public function getCodigoReferencia()
    {
        return $this->codigoReferencia;
    }

    public function setCodigoReferencia($codigoReferencia)
    {
        if (!empty($codigoReferencia) and is_numeric($codigoReferencia)) {
            $this->codigoReferencia = $codigoReferencia;
            return $this;
        } else {
            throw new FormException("codigoReferencia: Nenhum valor informado.");
        }
    }    
    
    public function getAlturaMaxima()
    {
        return $this->alturaMaxima;
    }

    public function setAlturaMaxima($alturaMaxima)
    {
        if (is_numeric($alturaMaxima)) {
            if(strtoupper($this->getTratarComo())!= "IMAGEM"){
                throw new FormException("alturaMaxima: Para utilizar recursos de imagem, informe 'IMAGEM' para o atributo tratarComo");
            }
            $this->alturaMaxima = $alturaMaxima;
            return $this;
        } else {
            throw new FormException("alturaMaxima: Valor nao numerico.");
        }
    }

    public function getLarguraMaxima()
    {
        return $this->larguraMaxima;
    }

    public function setLarguraMaxima($larguraMaxima)
    {
        if (is_numeric($larguraMaxima)) {
            if(strtoupper($this->getTratarComo())!= "IMAGEM"){
                throw new FormException("larguraMaxima: Para utilizar recursos de imagem, informe 'IMAGEM' para o atributo tratarComo");
            }
            $this->larguraMaxima = $larguraMaxima;
            return $this;
        } else {
            throw new FormException("larguraMaxima: Valor nao numerico.");
        }
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail)
    {
        if (is_bool($thumbnail)) {
            if(strtoupper($this->getTratarComo())!= "IMAGEM"){
                throw new FormException("thumbnail: Para utilizar recursos de imagem, informe 'IMAGEM' para o atributo tratarComo");
            }
            $this->thumbnail = $thumbnail;
            return $this;
        } else {
            throw new FormException("thumbnail: Valor nao booleano.");
        }
    }

    public function getAlturaMaximaTB()
    {
        return $this->alturaMaximaTB;
    }

    public function setAlturaMaximaTB($alturaMaximaTB)
    {
        if (is_numeric($alturaMaximaTB)) {
            if(strtoupper($this->getTratarComo())!= "IMAGEM"){
                throw new FormException("alturaMaximaTB: Para utilizar recursos de imagem, informe 'IMAGEM' para o atributo tratarComo");
            }
            $this->alturaMaximaTB = $alturaMaximaTB;
            return $this;
        } else {
            throw new FormException("alturaMaximaTB: Valor nao numerico.");
        }
    }

    public function getLarguraMaximaTB()
    {
        return $this->larguraMaximaTB;
    }

    public function setLarguraMaximaTB($larguraMaximaTB)
    {
        if (is_numeric($larguraMaximaTB)) {
            if(strtoupper($this->getTratarComo())!= "IMAGEM"){
                throw new FormException("larguraMaximaTB: Para utilizar recursos de imagem, informe 'IMAGEM' para o atributo tratarComo");
            }
            $this->larguraMaximaTB = $larguraMaximaTB;
            return $this;
        } else {
            throw new FormException("larguraMaximaTB: Valor nao numerico.");
        }
    }

    public function getExtensoesPermitidas()
    {
        return $this->extensoesPermitidas;
    }

    public function setExtensoesPermitidas($extensoesPermitidas)
    {
        if (is_array($extensoesPermitidas)) {
            $fails = @array_intersect($extensoesPermitidas, $this->extensoesNaoPermitidas);
            if(isset($this->extensoesNaoPermitidas) and @count($fails) > 0){
                throw new FormException("extensoesPermitidas: A extensao ". $fails[0] ." esta na lista de extensoes nao permitidas.");
            }
            $this->extensoesPermitidas = $extensoesPermitidas;
            return $this;
        } else {
            throw new FormException("extensoesPermitidas: O valor informado deve ser um array.");
        }
    }

    public function getExtensoesNaoPermitidas()
    {
        return $this->extensoesNaoPermitidas;
    }

    public function setExtensoesNaoPermitidas($extensoesNaoPermitidas)
    {
        if (is_array($extensoesNaoPermitidas)) {
            $fails = @array_intersect($extensoesNaoPermitidas, $this->extensoesPermitidas);
            if(isset($this->extensoesPermitidas) and @count($fails) > 0){
                throw new FormException("extensoesNaoPermitidas: A extensao ". $fails[0] ." esta na lista de extensoes permitidas.");
            }
            $this->extensoesNaoPermitidas = $extensoesNaoPermitidas;
            return $this;
        } else {
            throw new FormException("extensoesNaoPermitidas: O valor informado deve ser um array.");
        }
    }

    public function getTamanhoMaximoEmBytes()
    {
        return $this->tamanhoMaximoEmBytes;
    }

    public function setTamanhoMaximoEmBytes($tamanhoMaximoEmBytes)
    {
        if (is_numeric($tamanhoMaximoEmBytes)) {
            $this->tamanhoMaximoEmBytes = $tamanhoMaximoEmBytes;
            return $this;
        } else {
            throw new FormException("tamanhoMaximoEmBytes: Valor nao numerico.");
        }
    }

    public function getMinimoArquivos()
    {
        return $this->minimoArquivos;
    }

    public function setMinimoArquivos($minimoArquivos)
    {
        if (is_numeric($minimoArquivos)) {
            if(isset($this->maximoArquivos) and $this->maximoArquivo < $minimoArquivos){
                throw new FormException("minimoArquivos nao pode ser maior que maximoArquivos.");
            }
            $this->minimoArquivos = $minimoArquivos;
            return $this;
        } else {
            throw new FormException("minimoArquivos: Valor nao numerico.");
        }
    }

    public function getMaximoArquivos()
    {
        return $this->maximoArquivos;
    }

    public function setMaximoArquivos($maximoArquivos)
    {
        if (is_numeric($maximoArquivos)) {
            if(isset($this->minimoArquivos) and $this->minimoArquivos > $minimoArquivos){
                throw new FormException("maximoArquivos nao pode ser menor que minimoArquivos.");
            }
            $this->maximoArquivos = $maximoArquivos;
            return $this;
        } else {
            throw new FormException("maximoArquivos: Valor nao numerico.");
        }
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
