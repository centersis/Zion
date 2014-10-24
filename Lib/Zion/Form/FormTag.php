<?php

/**
 * \Zion\Form\FormTag()
 * 
 * @author The Sappiens Team
 * @copyright 2014
 * @version 2014
 * @access public
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

    /**
     * FormTag::setId()
     * 
     * @param mixed $id
     * @return
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * FormTag::getId()
     * 
     * @return
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * FormTag::setNome()
     * 
     * @param mixed $nome
     * @return
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * FormTag::getNome()
     * 
     * @return
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * FormTag::getAction()
     * 
     * @return
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * FormTag::setAction()
     * 
     * @param mixed $action
     * @return
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * FormTag::getAutoComplete()
     * 
     * @return
     */
    public function getAutoComplete()
    {
        return $this->autoComplete;
    }

    /**
     * FormTag::setAutoComplete()
     * 
     * @param mixed $autoComplete
     * @return
     */
    public function setAutoComplete($autoComplete)
    {
        $this->autoComplete = $autoComplete;
        return $this;
    }

    /**
     * FormTag::getEnctype()
     * 
     * @return
     */
    public function getEnctype()
    {
        return $this->enctype;
    }

    /**
     * FormTag::setEnctype()
     * 
     * @param mixed $enctype
     * @return
     */
    public function setEnctype($enctype)
    {
        $this->enctype = $enctype;
        return $this;
    }

    /**
     * FormTag::getMethod()
     * 
     * @return
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * FormTag::setMethod()
     * 
     * @param mixed $method
     * @return
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * FormTag::getNovalidate()
     * 
     * @return
     */
    public function getNovalidate()
    {
        return $this->novalidate;
    }

    /**
     * FormTag::setNovalidate()
     * 
     * @param mixed $novalidate
     * @return
     */
    public function setNovalidate($novalidate)
    {
        $this->novalidate = $novalidate;
        return $this;
    }

    /**
     * FormTag::getTarget()
     * 
     * @return
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * FormTag::setTarget()
     * 
     * @param mixed $target
     * @return
     */
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * FormTag::setComplemento()
     * 
     * @param mixed $complemento
     * @return
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    /**
     * FormTag::getComplemento()
     * 
     * @return
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * FormTag::setClassCss()
     * 
     * @param mixed $classCss
     * @return
     */
    public function setClassCss($classCss)
    {
        $this->classCss = $classCss;
        return $this;
    }

    /**
     * FormTag::getClassCss()
     * 
     * @return
     */
    public function getClassCss()
    {
        return $this->classCss;
    }
    
    /**
     * FormTag::setHeader()
     * 
     * @param mixed $header
     * @return
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * FormTag::getHeader()
     * 
     * @return
     */
    public function getHeader()
    {
        return $this->header;
    }

}
