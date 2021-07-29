<?php

namespace Centersis\Zion\Pixel\Form;

use Zion\Form\Exception\ErrorException;

class FormSetPixel
{

    /**
     * Deve Ser Informada uma String ou NULL
     * Ex: fa fa-home
     * @param string | null $iconFA
     * @return string | null
     * @throws ErrorException
     */
    public function setIconFA($iconFA)
    {
        if (\is_null($iconFA) or \is_string($iconFA)) {
            return $iconFA;
        } else {
            throw new ErrorException("iconFA: valor informado é inválido, use null ou uma string");
        }
    }

    /**
     * Deve Ser Informada uma String ou NULL
     * Ex: clique aqui para atualizar o formulário
     * @param string | null $toolTipMsg
     * @return string | null
     * @throws ErrorException
     */
    public function setToolTipMsg($toolTipMsg)
    {
        if (\is_null($toolTipMsg) or \is_string($toolTipMsg)) {
            return $toolTipMsg;
        } else {
            throw new ErrorException("toolTipMsg: valor informado é inválido, use null ou uma string");
        }
    }

    /**
     * Seta o número de colunas que um campo deve ocupar na tela, 
     * os valores válidos são de 1 a 12
     * @param int | null $emColunaDeTamanho
     * @return int | null
     * @throws ErrorException
     */
    public function setEmColunaDeTamanho($emColunaDeTamanho)
    {
        if (\is_null($emColunaDeTamanho) or \in_array($emColunaDeTamanho, \range(1, 12))) {
            return $emColunaDeTamanho;
        } else {
            throw new ErrorException("emColunaDeTamanho: Use variação de 1 a 12");
        }
    }

    /**
     * Seta o número de colunas que a descrição do campo deve ter 
     * os valores válidos são de 1 a 11
     * @param int | null $offsetColuna
     * @return int | null
     * @throws ErrorException
     */
    public function setOffsetColuna($offsetColuna)
    {
        if (\is_null($offsetColuna) or \in_array($offsetColuna, \range(1, 11))) {
            return $offsetColuna;
        } else {
            throw new ErrorException("offsetColuna: Use variação de 1 a 11");
        }
    }

    /**
     * Deve Ser Informada uma String ou NULL, este campo é baseado no plugin
     * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
     * 
     * Ex: '99/99/9999'
     * @param string | null $mascara
     * @return string | null
     * @throws ErrorException
     */
    public function setMascara($mascara)
    {
        if (\is_null($mascara) or \is_string($mascara)) {
            return $mascara;
        } else {
            throw new ErrorException("mascara: valor informado é inválido, use null ou uma string");
        }
    }

    /**
     * Deve Ser Informada uma String ou NULL
     * É usado para colocar uma informação de texto que irá preceder o campo
     * @param string | null $labelAntes
     * @return string | null
     * @throws ErrorException
     */
    public function setLabelAntes($labelAntes)
    {
        if (\is_null($labelAntes) or \is_string($labelAntes)) {
            return $labelAntes;
        } else {
            throw new ErrorException("labelantes: valor informado é inválido, use null ou uma string");
        }
    }

    /**
     * Deve Ser Informada uma String ou NULL
     * É usado para colocar uma informação de texto que irá suceder o campo
     * @param string | null $labelDepois
     * @return string | null
     * @throws ErrorException
     */
    public function setLabelDepois($labelDepois)
    {
        if (\is_null($labelDepois) or \is_string($labelDepois)) {
            return $labelDepois;
        } else {
            throw new ErrorException("labeldepois: valor informado é inválido, use null ou uma string");
        }
    }

    /**
     * Se sor passado false o campo irá ignorar o processamento de JavaScript
     * que poderia ser gerado pelo campo
     * @param bool | null $processarJS
     * @return bool | null
     * @throws ErrorException
     */
    public function setProcessarJS($processarJS)
    {
        if (\is_null($processarJS) or \is_bool($processarJS)) {
            return $processarJS;
        } else {
            throw new ErrorException("processarJS: valor informado é inválido, use null, true ou false");
        }
    }

    /**
     * Deve Ser Informada uma String ou NULL
     * Use:
     * 1 - valorvariavel - Usada para valores que podem varias Ex: Datas e Números
     * 2 - texto - Textos
     * 3 - valorfixo - Valores imutaveis como ENUN
     * 4 - igual - apenas filtro por igualdade
     * 5 - diferente - apenas filtro por diferença
     * @param string | null $tipoFiltro
     * @return string | null
     * @throws ErrorException
     */
    public function setTipoFiltro($tipoFiltro)
    {
        if (\is_null($tipoFiltro) or \is_string($tipoFiltro)) {
            return $tipoFiltro;
        } else {
            throw new ErrorException("tipoFiltro: valor informado é inválido, use null ou uma string");
        }
    }

    /**
     * String será adicionada no container externo que engloba o campo
     * Ex: style="display:none"
     * @param string | null $complementoExterno
     * @return string | null
     * @throws ErrorException
     */
    public function setComplementoExterno($complementoExterno)
    {
        if (\is_null($complementoExterno) or \is_string($complementoExterno)) {
            return $complementoExterno;
        } else {
            throw new ErrorException("complementoExterno: valor informado é inválido, use null ou uma string");
        }
    }
    
    /**
     * String que corresponde ao hash de ajuda previamente cadastrado no 
     * sistema
     * Ex: 'x7f5s18'
     * @param string | null $hashAjuda
     * @return string | null
     * @throws ErrorException
     */
    public function setHashAjuda($hashAjuda)
    {
        if (\is_null($hashAjuda) or \is_string($hashAjuda)) {
            return $hashAjuda;
        } else {
            throw new ErrorException("hashAjuda: valor informado é inválido, use null ou uma string");
        }
    }

}
