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
 * \Zion\Form\FormInputTextArea()
 *
 * @author The Sappiens Team
 * @copyright 2014
 * @version 2014
 * @access public
 */

namespace Zion\Form;

use \Zion\Form\Exception\FormException as FormException;

class FormInputTextArea extends FormBasico implements FilterableInput
{

    private $tipoBase;
    private $acao;
    private $obrigatorio;
    private $maximoCaracteres;
    private $minimoCaracteres;
    private $placeHolder;
    private $aliasSql;
    private $readonly;
    private $colunas;
    private $linhas;
    private $form;
    private $categoriaFiltro;

    /**
     * FormInputTextArea::__construct()
     *
     * @param mixed $acao
     * @param mixed $nome
     * @param mixed $identifica
     * @param mixed $obrigatorio
     * @return
     */
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'textarea';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
        $this->categoriaFiltro = FilterableInput::LIKE;
    }

    /**
     * FormInputTextArea::getTipoBase()
     *
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    /**
     * FormInputTextArea::getAcao()
     *
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * FormInputTextArea::setMaximoCaracteres()
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
     * FormInputTextArea::getMaximoCaracteres()
     *
     * @return
     */
    public function getMaximoCaracteres()
    {
        return $this->maximoCaracteres;
    }

    /**
     * FormInputTextArea::setMinimoCaracteres()
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
     * FormInputTextArea::getMinimoCaracteres()
     *
     * @return
     */
    public function getMinimoCaracteres()
    {
        return $this->minimoCaracteres;
    }

    /**
     * FormInputTextArea::setObrigarorio()
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
     * FormInputTextArea::getObrigatorio()
     *
     * @return
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    /**
     * FormInputTextArea::setPlaceHolder()
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
     * FormInputTextArea::getPlaceHolder()
     *
     * @return
     */
    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }

    /**
     * FormInputTextArea::setAliasSql()
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
     * FormInputTextArea::getAliasSql()
     *
     * @return string
     */
    public function getAliasSql()
    {
        return $this->aliasSql;
    }

    public function getReadonly()
    {
        return $this->readonly;
    }

    public function setReadonly($readonly)
    {
        if (is_bool($readonly)) {
            $this->readonly = $readonly;
            return $this;
        } else {
            throw new FormException("readonly: Valor não booleano");
        }
    }

    public function getColunas()
    {
        return $this->colunas;
    }

    public function setColunas($colunas)
    {
        if (is_numeric($colunas)) {
            $this->colunas = $colunas;
            return $this;
        } else {
            throw new FormException("colunas: Valor não numerico.");
        }
    }

    public function getLinhas()
    {
        return $this->linhas;
    }

    public function setLinhas($linhas)
    {
        if (is_numeric($linhas)) {
            $this->linhas = $linhas;
            return $this;
        } else {
            throw new FormException("linhas: Valor não numérico.");
        }
    }

    public function getForm()
    {
        return $this->form;
    }

    public function setForm($form)
    {
        if (!is_null($form)) {
            $this->form = $form;
            return $this;
        } else {
            throw new FormException("form: Nenhum valor informado");
        }
    }

    /**
     * Sobrecarga de Metodos Básicos
     */

    /**
     * FormInputTextArea::setId()
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
     * FormInputTextArea::setNome()
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
     * FormInputTextArea::setIdentifica()
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
     * FormInputTextArea::setValor()
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
     * FormInputTextArea::setValorPadrao()
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
     * FormInputTextArea::setDisabled()
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
     * FormInputTextArea::setComplemento()
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
     * FormInputTextArea::setAtributos()
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
     * FormInputTextArea::setClassCss()
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

    public function setNomeForm($nomeForm)
    {
        parent::setNomeForm($nomeForm);
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
