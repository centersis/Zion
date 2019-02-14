<?php

namespace Pixel\Form;

use Zion\Exception\ErrorException;
use Zion\Form\FormUpload as FormUploadZion;
use Pixel\Form\FormSetPixel;

class FormUpload extends FormUploadZion
{

    private $iconFA;
    private $toolTipMsg;
    private $emColunaDeTamanho;
    private $offsetColuna;
    private $processarJS;
    private $complementoExterno;
    private $codigoReferencia;
    private $dimensoes;
    private $extensoesPermitidas;
    private $extensoesNaoPermitidas;
    private $tamanhoMaximoEmBytes;
    private $minimoArquivos;
    private $maximoArquivos;
    private $nomeCampo;
    private $hashAjuda;
    private $formSetPixel;
    private $organogramaCod;
    private $crop;

    public function __construct($acao, $nome, $identifica, $tratarComo)
    {
        parent::__construct($acao, $nome, $identifica, $tratarComo);

        $this->formSetPixel = new FormSetPixel();

        $this->crop = false;

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

    public function setOffsetColuna($offsetColuna)
    {
        $this->offsetColuna = $this->formSetPixel->setOffsetColuna($offsetColuna);
        return $this;
    }

    public function getOffsetColuna()
    {
        return $this->offsetColuna ? $this->offsetColuna : 3;
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

    public function setComplementoExterno($complementoExterno)
    {
        $this->complementoExterno = $this->formSetPixel->setComplementoExterno($complementoExterno);
        return $this;
    }

    public function getComplementoExterno()
    {
        return $this->complementoExterno;
    }

    public function getCodigoReferencia()
    {
        return $this->codigoReferencia;
    }

    public function setCodigoReferencia($codigoReferencia)
    {
        if (!empty($codigoReferencia) and ! \is_numeric($codigoReferencia)) {
            throw new ErrorException("codigoReferencia: Valor não numérico." . $codigoReferencia);
        }

        $this->codigoReferencia = $codigoReferencia;

        return $this;
    }

    public function getDimensoes()
    {
        return $this->dimensoes;
    }

    /**
     * Informações sobre as dimensões das imagens em caso de "upload de imagens"
     * Por padrão o componente já grava o arquivo original na pasta "original"
     * mantendo as suas caracteristicas.
     * 
     * Ex. de Uso:
     * 
     * 1º Parâmetro: nome da pasta
     * 2º Parâmetro: Array com até 3 posições com altura, largura e qualidade
     * 
     * setDimensoes([
     *               '400x300'=>['largura'=>400,'altura'=>300,'qualidade'=>80],
     *               '800x600'=>['largura'=>800]
     *              ])
     * 
     * Obs: qualidade é um parâmetro opcional
     * Obs: altura e largura são opcionais, porem pelo menos um dos dois devem
     * ser informados, quando apelas lagura ou altura for informado a imagem
     * irá ser redimencionada proporcionalmente
     * 
     * @param array $dimensoes
     * @return \Pixel\Form\FormUpload
     * @throws ErrorException
     */
    public function setDimensoes($dimensoes)
    {
        if (\is_null($dimensoes) || \is_array($dimensoes)) {

            $this->dimensoes = $dimensoes;
        } else {
            throw new ErrorException("dimensoes: Valor não numérico.");
        }

        return $this;
    }

    public function getExtensoesPermitidas()
    {
        return $this->extensoesPermitidas;
    }

    public function setExtensoesPermitidas($extensoesPermitidas)
    {
        if (is_array($extensoesPermitidas)) {
            $fails = @array_intersect($extensoesPermitidas, $this->extensoesNaoPermitidas);
            if (isset($this->extensoesNaoPermitidas) and @ count($fails) > 0) {
                throw new ErrorException("extensoesPermitidas: A extensão " . $fails[0] . " está na lista de extensões não permitidas.");
            }
            $this->extensoesPermitidas = $extensoesPermitidas;
            return $this;
        } else {
            throw new ErrorException("extensoesPermitidas: O valor informado deve ser um array.");
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
            if (isset($this->extensoesPermitidas) and @ count($fails) > 0) {
                throw new ErrorException("extensoesNaoPermitidas: A extensão " . $fails[0] . " está na lista de extensões permitidas.");
            }
            $this->extensoesNaoPermitidas = $extensoesNaoPermitidas;
            return $this;
        } else {
            throw new ErrorException("extensoesNaoPermitidas: O valor informado deve ser um array.");
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
            throw new ErrorException("tamanhoMaximoEmBytes: Valor não numérico.");
        }
    }

    public function getMinimoArquivos()
    {
        return $this->minimoArquivos;
    }

    public function setMinimoArquivos($minimoArquivos)
    {
        if (is_numeric($minimoArquivos)) {
            if (isset($this->minimoArquivos) and $this->minimoArquivos < $minimoArquivos) {
                throw new ErrorException("minimoArquivos não pode ser maior que maximoArquivos.");
            }
            $this->minimoArquivos = $minimoArquivos;
            return $this;
        } else {
            throw new ErrorException("minimoArquivos: Valor não numérico.");
        }
    }

    public function getMaximoArquivos()
    {
        return $this->maximoArquivos;
    }

    public function setMaximoArquivos($maximoArquivos)
    {
        if (is_numeric($maximoArquivos)) {
            if (isset($this->maximoArquivos) and $this->maximoArquivos > $maximoArquivos) {
                throw new ErrorException("maximoArquivos não pode ser menor que minimoArquivos.");
            }
            $this->maximoArquivos = $maximoArquivos;
            return $this;
        } else {
            throw new ErrorException("maximoArquivos: Valor não numérico.");
        }
    }

    public function getNomeCampo()
    {
        return $this->nomeCampo;
    }

    public function setNomeCampo($nomeCampo)
    {
        $this->nomeCampo = $nomeCampo;

        return $this;
    }

    public function setTipoFiltro($tipoFiltro)
    {
        $this->tipoFiltro = $this->formSetPixel->setTipoFiltro($tipoFiltro);
        return $this;
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

    public function getTipoFiltro()
    {
        return $this->tipoFiltro;
    }

    public function getOrganogramaCod()
    {
        return $this->organogramaCod;
    }

    public function setOrganogramaCod($organogramaCod)
    {
        $this->organogramaCod = $organogramaCod;

        return $this;
    }
    
    public function getCrop()
    {
        return $this->crop;
    }

    public function setCrop($crop)
    {
        $this->crop = $crop;

        return $this;
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

    public function setModulo($modulo)
    {
        parent::setModulo($modulo);
        return $this;
    }

}
