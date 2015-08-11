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

class FormTag
{

    private $id;
    private $nome;
    private $action;
    private $autoComplete;
    private $enctype;
    private $method;
    private $novalidate;
    private $target;
    private $complemento;
    private $classCss;
    private $header;
    private $acaoSubmit;
    private $validacaoJS;

    public function setId($id)
    {
        $this->id = $id;
        $this->validacaoJS = true;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function getAutoComplete()
    {
        return $this->autoComplete;
    }

    public function setAutoComplete($autoComplete)
    {
        $this->autoComplete = $autoComplete;
        return $this;
    }

    public function getEnctype()
    {
        return $this->enctype;
    }

    public function setEnctype($enctype)
    {
        $this->enctype = $enctype;
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function getNovalidate()
    {
        return $this->novalidate;
    }

    public function setNovalidate($novalidate)
    {
        $this->novalidate = $novalidate;
        return $this;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    public function getComplemento()
    {
        return $this->complemento;
    }

    public function setClassCss($classCss)
    {
        $this->classCss = $classCss;
        return $this;
    }

    public function getClassCss()
    {
        return $this->classCss;
    }
    
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    public function getHeader()
    {
        return $this->header;
    }
    
    public function setAcaoSubmit($acaoSubmit)
    {
        $this->acaoSubmit = $acaoSubmit;
        return $this;
    }

    public function getAcaoSubmit()
    {
        return $this->acaoSubmit;
    }
    
    public function setValidacaoJS($validacaoJS)
    {
        $this->validacaoJS = $validacaoJS;
        return $this;
    }

    public function getValidacaoJS()
    {
        return $this->validacaoJS;
    }

}
