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

abstract class FormBasico
{

    private $id;
    private $nome;
    private $identifica;
    private $valor;
    private $valorPadrao;
    private $disabled;
    private $complemento;
    private $atributos;
    private $classCss = [];
    private $container;
    private $nomeForm;

    public function setId($id)
    {
        if (!empty($id)) {
            $this->id = \str_replace('[]', '', $id);
            return $this;
        } else {
            throw new FormException("id: Nenhum valor informado.");
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setNome($nome)
    {
        if (!empty($nome)) {
            $this->nome = $nome;
            return $this;
        } else {
            throw new FormException("nome: Nenhum valor informado.");
        }
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setIdentifica($identifica)
    {
        if (!empty($identifica)) {
            $this->identifica = $identifica;
            return $this;
        } else {
            throw new FormException("identifica: Nenhum valor informado.");
        }
    }

    public function getIdentifica()
    {
        return $this->identifica;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValorPadrao($valorPadrao)
    {
        if (!empty($valorPadrao)) {
            $this->valorPadrao = $valorPadrao;
        } else {
            //throw new FormException("valorPadrao: Nenhum valor informado.");
        }

        return $this;
    }

    public function getValorPadrao()
    {
        return $this->valorPadrao;
    }

    public function setDisabled($disabled)
    {
        if ($disabled === true) {
            $this->disabled = $disabled;
        } else {
            $this->disabled = false;
        }
    }

    public function getDisabled()
    {
        return $this->disabled;
    }

    public function setComplemento($complemento)
    {
        if (!empty($complemento)) {
            $this->complemento = $complemento;
            return $this;
        } else {
            throw new FormException("complemento: Nenhum valor informado.");
        }
    }

    public function getComplemento()
    {
        return $this->complemento;
    }

    public function setAtributos($atributos)
    {
        if (!empty($atributos)) {
            $this->atributos = $atributos;
            return $this;
        } else {
            throw new FormException("atributos: Nenhum valor informado.");
        }
    }

    public function getAtributos()
    {
        return $this->atributos;
    }

    public function setClassCss($classCss)
    {
        $class = \trim($classCss);

        if (!empty($class)) {
            $this->classCss[$class] = $class;
            return $this;
        } else {
            throw new FormException("classCss: Nenhum valor informado.");
        }
    }

    public function getClassCss()
    {
        return \implode(' ', $this->classCss);
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    public function getNomeForm()
    {
        return $this->nomeForm;
    }

    public function setNomeForm($nomeForm)
    {
        $this->nomeForm = $nomeForm;
        return $this;
    }

}
