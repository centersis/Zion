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

namespace Zion\Form;

use \Zion\Form\Exception\FormException as FormException;
use Zion\Form\FormBasico;

class FormInputNumber extends FormBasico implements FilterableInput
{

    private $tipoBase;
    private $acao;
    private $largura;
    private $obrigatorio;
    private $maximoCaracteres;
    private $minimoCaracteres;
    private $valorMaximo;
    private $valorMinimo;
    private $placeHolder;
    private $aliasSql;
    private $categoriaFiltro;
    private $filtroPadrao;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'number';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
        $this->filtroPadrao = '=';
        $this->categoriaFiltro = FilterableInput::EQUAL;
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
        if (\preg_match('/^[0-9]{1,}[%]{1}$|^[0-9]{1,}[px]{2}$|^[0-9]{1,}$/', $largura)) {
            $this->largura = $largura;
            return $this;
        } else {
            throw new FormException("largura: O valor não está nos formatos aceitos: 10%; 10px; ou 10");
        }
    }

    public function getLargura()
    {
        return $this->largura;
    }

    public function setMaximoCaracteres($maximoCaracteres)
    {
        if (\is_numeric($maximoCaracteres)) {

            if (isset($this->minimoCaracteres) and ( $maximoCaracteres < $this->minimoCaracteres)) {
                throw new FormException("maximoCaracteres não pode ser menor que minimoCaracteres.");
            }
            $this->maximoCaracteres = $maximoCaracteres;
            return $this;
        } else {
            throw new FormException("maximoCaracteres: Valor não numérico.");
        }
    }

    public function getMaximoCaracteres()
    {
        return $this->maximoCaracteres;
    }

    public function setMinimoCaracteres($minimoCaracteres)
    {
        if (\is_numeric($minimoCaracteres)) {

            if (isset($this->maximoCaracteres) and ( $minimoCaracteres > $this->maximoCaracteres)) {
                throw new FormException("minimoCaracteres não pode ser maior que maximoCaracteres.");
            }

            $this->minimoCaracteres = $minimoCaracteres;
            return $this;
        } else {
            throw new FormException("minimoCaracteres: Valor não numérico.");
        }
    }

    public function getMinimoCaracteres()
    {
        return $this->minimoCaracteres;
    }

    public function setValorMinimo($valorMinimo)
    {
        if (\is_numeric($valorMinimo)) {

            if (isset($this->valorMaximo) and ( $valorMinimo > $this->valorMaximo)) {
                throw new FormException("valorMinimo não pode ser maior que valorMaximo.");
            }

            $this->valorMinimo = $valorMinimo;
            return $this;
        } else {
            throw new FormException("valorMinimo: Valor não numérico");
        }
    }

    public function getValorMinimo()
    {
        return $this->valorMinimo;
    }

    public function setValorMaximo($valorMaximo)
    {
        if (\is_numeric($valorMaximo)) {

            if (isset($this->valorMinimo) and ( $valorMaximo < $this->valorMinimo)) {
                throw new FormException("valorMaximo não pode ser menor que valorMinimo.");
            }

            $this->valorMaximo = $valorMaximo;
            return $this;
        } else {
            throw new FormException("valorMaximo: Valor não numérico");
        }
    }

    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }

    public function setObrigarorio($obrigatorio)
    {
        if (\is_bool($obrigatorio)) {
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

    public function setAliasSql($aliasSql)
    {
        if (!\is_null($aliasSql)) {
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

    /**
     * Sobrecarga de Metodos Básicos
     */

    /**
     * FormInputNumber::setId()
     *
     * @return
     */
    public function setId($id)
    {
        parent::setId($id);
        return $this;
    }

    /**
     * FormInputNumber::setNome()
     *
     * @return
     */
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }

    /**
     * FormInputNumber::setIdentifica()
     *
     * @return
     */
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }

    /**
     * FormInputNumber::setValor()
     *
     * @return
     */
    public function setValor($valor)
    {
        parent::setValor($valor);
        return $this;
    }

    /**
     * FormInputNumber::setValorPadrao()
     *
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }

    /**
     * FormInputNumber::setDisabled()
     *
     * @return
     */
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }

    /**
     * FormInputNumber::setComplemento()
     *
     * @return
     */
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    /**
     * FormInputNumber::setAtributos()
     *
     * @return
     */
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }

    /**
     * FormInputNumber::setClassCss()
     *
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

    public function setFiltroPadrao($filtroPadrao)
    {
        $this->filtroPadrao = $filtroPadrao;

        return $this;
    }

    public function getFiltroPadrao()
    {
        return $this->filtroPadrao;
    }

}
