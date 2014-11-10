<?php

/**
 * \Zion\Form\FormLayout()
 * 
 * @author The Sappiens Team
 * @copyright 2014
 * @version 2014
 * @access public
 */
 
namespace Zion\Form;

class FormLayout
{

    private $nome;
    private $tipoBase;
    private $acao;
    private $conteudo;

    /**
     * FormLayout::__construct()
     * 
     * @param mixed $nome
     * @param mixed $conteudo
     * @return
     */
    public function __construct($nome, $conteudo)
    {
        $this->tipoBase = 'layout';
        $this->setNome($nome);
        $this->setConteudo($conteudo);
    }
    
    /**
     * FormLayout::setNome()
     * 
     * @param mixed $nome
     * @return
     */
    public function setNome($nome)
    {
        if(!empty($nome)){
             $this->nome = $nome;
            return $this;
        } else {
            throw new FormException("nome: Nenhum valor informado.");
        }
    }

    /**
     * FormLayout::getNome()
     * 
     * @return
     */
    public function getNome()
    {
        return $this->nome;
    }
    
    /**
     * FormLayout::getTipoBase()
     * 
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }
    
    /**
     * FormLayout::getAcao()
     * 
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * FormLayout::setConteudo()
     * 
     * @param mixed $conteudo
     * @return
     */
    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
        return $this;
    }

    /**
     * FormLayout::getConteudo()
     * 
     * @return
     */
    public function getConteudo()
    {
        return $this->conteudo;
    }
    
    public function setContainer($container)
    {
        parent::setContainer($container);
        return $this;
    }
}
