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
 * \Zion\Form\FormInputCep()
 *
 * @author The Sappiens Team
 * @copyright 2014
 * @version 2014
 * @access public
 */

namespace Zion\Form;

use \Zion\Form\Exception\FormException as FormException;

class FormInputCep extends FormBasico implements FilterableInput
{

    private $tipoBase;
    private $acao;
    private $obrigatorio;
    private $maximoCaracteres;
    private $minimoCaracteres;
    private $placeHolder;
    private $aliasSql;
    private $categoriaFiltro;

    /**
     * FormInputCep::__construct()
     *
     * @return
     */
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'cep';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
        $this->setMaximoCaracteres(10);
        $this->categoriaFiltro = FilterableInput::EQUAL;
    }

    /**
     * FormInputCep::getTipoBase()
     *
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    /**
     * FormInputCep::getAcao()
     *
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * FormInputCep::setMaximoCaracteres()
     *
     * @return
     */
    public function setMaximoCaracteres($maximoCaracteres)
    {
        if (is_numeric($maximoCaracteres)) {

            if (isset($this->minimoCaracteres) and ( $maximoCaracteres < $this->minimoCaracteres)) {
                throw new FormException("maximoCaracteres não pode ser menor que minimoCaracteres.");
            }

            $this->maximoCaracteres = $maximoCaracteres;
        } else {
            throw new FormException("maximoCaracteres: Valor não numerico.");
        }

        return $this;
    }

    /**
     * FormInputCep::getMaximoCaracteres()
     *
     * @return
     */
    public function getMaximoCaracteres()
    {
        return $this->maximoCaracteres;
    }

    /**
     * FormInputCep::setMinimoCaracteres()
     *
     * @return
     */
    public function setMinimoCaracteres($minimoCaracteres)
    {
        if (is_numeric($minimoCaracteres)) {

            if (isset($this->maximoCaracteres) and ( $minimoCaracteres > $this->maximoCaracteres)) {
                throw new FormException("minimoCaracteres não pode ser maior que maximoCaracteres.");
            }

            $this->minimoCaracteres = $minimoCaracteres;
        } else {
            throw new FormException("minimoCaracteres: Valor não numerico.");
        }

        return $this;
    }

    /**
     * FormInputCep::getMinimoCaracteres()
     *
     * @return
     */
    public function getMinimoCaracteres()
    {
        return $this->minimoCaracteres;
    }

    /**
     * FormInputCep::setObrigarorio()
     *
     * @return
     */
    public function setObrigarorio($obrigatorio)
    {
        if (is_bool($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
        } else {
            throw new FormException("obrigatorio: Valor não booleano");
        }

        return $this;
    }

    /**
     * FormInputCep::getObrigatorio()
     *
     * @return
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    /**
     * FormInputCep::setPlaceHolder()
     *
     * @return
     */
    public function setPlaceHolder($placeHolder)
    {
        if (!empty($placeHolder)) {
            $this->placeHolder = $placeHolder;
        } else {
            throw new FormException("placeHolder: Nenhum valor informado");
        }

        return $this;
    }

    /**
     * FormInputCep::getAliasSql()
     *
     * @return string
     */
    public function getAliasSql(){
        return $this->aliasSql;
    }

    /**
     * FormInputCep::setAliasSql()
     *
     * @param string $aliasSql
     *
     */
    public function setAliasSql($aliasSql)
    {
        if (!is_null($aliasSql)) {
            $this->aliasSql = $aliasSql;
        } else {
            throw new FormException("aliasSql: Nenhum valor informado");
        }

        return $this;
    }

    /**
     * FormInputCep::getPlaceHolder()
     *
     * @return
     */
    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }

    /**
     * Sobrecarga de Metodos Básicos
     */

    /**
     * FormInputCep::setId()
     *
     * @return
     */
    public function setId($id)
    {
        parent::setId($id);
        return $this;
    }

    /**
     * FormInputCep::setNome()
     *
     * @return
     */
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }

    /**
     * FormInputCep::setIdentifica()
     *
     * @return
     */
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }

    /**
     * FormInputCep::setValor()
     *
     * @return
     */
    public function setValor($valor)
    {
        parent::setValor($valor);
        return $this;
    }

    /**
     * FormInputCep::setValorPadrao()
     *
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }

    /**
     * FormInputCep::setDisabled()
     *
     * @return
     */
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }

    /**
     * FormInputCep::setComplemento()
     *
     * @return
     */
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    /**
     * FormInputCep::setAtributos()
     *
     * @return
     */
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }

    /**
     * FormInputCep::setClassCss()
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
    public function setCategoriaFiltro($categoria)
    {
        $this->categoriaFiltro = $categoria;

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
