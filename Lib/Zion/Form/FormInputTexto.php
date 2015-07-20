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

/**
 * \Zion\Form\FormInputTexto()
 *
 * @author The Sappiens Team
 * @copyright 2014
 * @version 2014
 * @access public
 */

namespace Zion\Form;

use \Zion\Form\Exception\FormException as FormException;

class FormInputTexto extends FormBasico implements FilterableInput
{

    private $tipoBase;
    private $acao;
    private $largura;
    private $obrigatorio;
    private $maximoCaracteres;
    private $minimoCaracteres;
    private $caixa;
    private $converterHtml;
    private $autoTrim;
    private $placeHolder;
    private $autoComplete;
    private $deveSerIgualA;
    private $aliasSql;
    private $categoriaFiltro;

    /**
     * FormInputTexto::__construct()
     *
     * @param mixed $acao
     * @param mixed $nome
     * @param mixed $identifica
     * @param mixed $obrigatorio
     * @return
     */
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'texto';
        $this->acao = $acao;
        $this->autoTrim = true;
        $this->converterHtml = true;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
        $this->categoriaFiltro = FilterableInput::LIKE;
    }

    /**
     * FormInputTexto::getTipoBase()
     *
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    /**
     * FormInputTexto::getAcao()
     *
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * FormInputTexto::setLargura()
     *
     * @param mixed $largura
     * @return
     */
    public function setLargura($largura)
    {
        if (preg_match('/^[0-9]{1,}[%]{1}$|^[0-9]{1,}[px]{2}$|^[0-9]{1,}$/', $largura)) {
            $this->largura = $largura;
            return $this;
        } else {
            throw new FormException("largura: O valor não está nos formatos aceitos: 10%; 10px; ou 10");
        }
    }

    /**
     * FormInputTexto::getLargura()
     *
     * @return
     */
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
        if (is_numeric($maximoCaracteres)) {

            if (isset($this->minimoCaracteres) and ( $maximoCaracteres < $this->minimoCaracteres)) {
                throw new FormException("maximoCaracteres não pode ser menor que minimoCaracteres.");
            }
            $this->maximoCaracteres = $maximoCaracteres;
            return $this;

        } else {
            throw new FormException("maximoCaracteres: Valor não numerico.");
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
        if (is_numeric($minimoCaracteres)) {

            if (isset($this->maximoCaracteres) and ( $minimoCaracteres > $this->maximoCaracteres)) {
                throw new FormException("minimoCaracteres não pode ser maior que maximoCaracteres.");
            }

            $this->minimoCaracteres = $minimoCaracteres;
            return $this;
        } else {
            throw new FormException("minimoCaracteres: Valor não numérico.");
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

    /**
     * FormInputTexto::setCaixa()
     *
     * @param mixed $caixa
     * @return
     */
    public function setCaixa($caixa)
    {
        if (\strtoupper($caixa) == "ALTA" or \strtoupper($caixa) == "BAIXA") {
            $this->caixa = $caixa;
            return $this;
        } else {
            throw new FormException("caixa: Valor desconhecido: " . $caixa);
        }
    }

    /**
     * FormInputTexto::getCaixa()
     *
     * @return
     */
    public function getCaixa()
    {
        return $this->caixa;
    }

    /**
     * FormInputTexto::setObrigarorio()
     *
     * @param mixed $obrigatorio
     * @return
     */
    public function setObrigarorio($obrigatorio)
    {
        if (is_bool($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
            return $this;
        } else {
            throw new FormException("obrigatorio: Valor não booleano");
        }
    }

    /**
     * FormInputTexto::getObrigatorio()
     *
     * @return
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    /**
     * FormInputTexto::setConverterHtml()
     *
     * @param mixed $converterHtml
     * @return
     */
    public function setConverterHtml($converterHtml)
    {
        if (is_bool($converterHtml)) {
            $this->converterHtml = $converterHtml;
            return $this;
        } else {
            throw new FormException("converterHtml: Valor não booleano");
        }
    }

    /**
     * FormInputTexto::getConverterHtml()
     *
     * @return
     */
    public function getConverterHtml()
    {
        return $this->converterHtml;
    }

    /**
     * FormInputTexto::setAutoTrim()
     *
     * @param mixed $autoTrim
     * @return
     */
    public function setAutoTrim($autoTrim)
    {
        if (is_bool($autoTrim)) {
            $this->autoTrim = $autoTrim;
            return $this;
        } else {
            throw new FormException("autoTrim: Valor não booleano");
        }
    }

    /**
     * FormInputTexto::getAutoTrim()
     *
     * @return
     */
    public function getAutoTrim()
    {
        return $this->autoTrim;
    }

    /**
     * FormInputTexto::setPlaceHolder()
     *
     * @param mixed $placeHolder
     * @return
     */
    public function setPlaceHolder($placeHolder)
    {
        if (!empty($placeHolder)) {
            $this->placeHolder = $placeHolder;
            return $this;
        } else {
            throw new FormException("placeHolder: Nenhum valor informado");
        }
    }

    /**
     * FormInputTexto::getPlaceHolder()
     *
     * @return
     */
    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }

    /**
     * FormInputTexto::setAutoComplete()
     *
     * @param mixed $autoComplete
     * @return
     */
    public function setAutoComplete($autoComplete)
    {
        if (is_bool($autoComplete)) {
            $this->autoComplete = $autoComplete;
            return $this;
        } else {
            throw new FormException("autoComplete: O valor informado não é um booleano.");
        }
    }

    /**
     * FormInputTexto::getAutoComplete()
     *
     * @return
     */
    public function getAutoComplete()
    {
        return $this->autoComplete;
    }

    /**
     * FormInputTexto::setDeveSerIgualA()
     *
     * @param mixed $deveSerIgualA
     * @return
     */
    public function setDeveSerIgualA($deveSerIgualA)
    {
        if (!empty($deveSerIgualA)) {
            $this->deveSerIgualA = $deveSerIgualA;
            return $this;
        } else {
            throw new FormException("deveSerIgualA: O valor informado não é um valor válido.");
        }
    }

    /**
     * FormInputTexto::getDeveSerIgualA()
     *
     * @return
     */
    public function getDeveSerIgualA()
    {
        return $this->deveSerIgualA;
    }

    /**
     * FormInputTexto::setAliasSql()
     *
     * @param string $aliasSql
     *
     */
    public function setAliasSql($aliasSql)
    {
        if (!is_null($aliasSql)) {
            $this->aliasSql = $aliasSql;
            return $this;
        } else {
            throw new FormException("aliasSql: Nenhum valor informado");
        }
    }

    /**
     * FormInputTexto::getAliasSql()
     *
     * @return string
     */
    public function getAliasSql(){
        return $this->aliasSql;
    }

    /**
     * Sobrecarga de Metodos Básicos
     */

    /**
     * FormInputTexto::setId()
     *
     * @param mixed $id
     * @return
     */
    public function setId($id)
    {
        parent::setId($id);
        return $this;
    }

    /**
     * FormInputTexto::setNome()
     *
     * @param mixed $nome
     * @return
     */
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }

    /**
     * FormInputTexto::setIdentifica()
     *
     * @param mixed $identifica
     * @return
     */
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }

    /**
     * FormInputTexto::setValor()
     *
     * @param mixed $valor
     * @return
     */
    public function setValor($valor)
    {
        parent::setValor($valor);
        return $this;
    }

    /**
     * FormInputTexto::setValorPadrao()
     *
     * @param mixed $valorPadrao
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }

    /**
     * FormInputTexto::setDisabled()
     *
     * @param mixed $disabled
     * @return
     */
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }

    /**
     * FormInputTexto::setComplemento()
     *
     * @param mixed $complemento
     * @return
     */
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    /**
     * FormInputTexto::setAtributos()
     *
     * @param mixed $atributos
     * @return
     */
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }

    /**
     * FormInputTexto::setClassCss()
     *
     * @param mixed $classCss
     * @return
     */
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

    /**
     * {@inheritdoc}
     *
     * @return self
     */
    public function setCategoriaFiltro($tipo)
    {
        $this->categoriaFiltro = $tipo;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getCategoriaFiltro()
    {
        return $this->categoriaFiltro;
    }
}
