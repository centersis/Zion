<?php

namespace Zion\Form;

use Zion\Exception\ErrorException;

class FormInputTextArea extends FormBasico
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
    private $filtroPadrao;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'textarea';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigatorio($obrigatorio);
        $this->filtroPadrao = '=';
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    public function getAcao()
    {
        return $this->acao;
    }

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

    public function getMaximoCaracteres()
    {
        return $this->maximoCaracteres;
    }

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

    public function getMinimoCaracteres()
    {
        return $this->minimoCaracteres;
    }

    public function setObrigatorio($obrigatorio)
    {
        if (\is_bool($obrigatorio)) {
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

    public function setAliasSql($aliasSql)
    {
        if (!\is_null($aliasSql)) {
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

    public function getReadonly()
    {
        return $this->readonly;
    }

    public function setReadonly($readonly)
    {
        if (\is_bool($readonly)) {
            $this->readonly = $readonly;
            return $this;
        } else {
            throw new ErrorException("readonly: Valor não booleano");
        }
    }

    public function getColunas()
    {
        return $this->colunas;
    }

    public function setColunas($colunas)
    {
        if (\is_numeric($colunas)) {
            $this->colunas = $colunas;
            return $this;
        } else {
            throw new ErrorException("colunas: Valor não numerico.");
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
            throw new ErrorException("linhas: Valor não numérico.");
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
            throw new ErrorException("form: Nenhum valor informado");
        }
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

    public function setNomeForm($nomeForm)
    {
        parent::setNomeForm($nomeForm);
        return $this;
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
