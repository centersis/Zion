<?php
/*

    Sappiens Framework
    Copyright (C) 2014, BRA Consultoria

    Website do autor: www.braconsultoria.com.br/sappiens
    Email do autor: sappiens@braconsultoria.com.br

    Website do projeto, equipe e documentação: www.sappiens.com.br
   
    Este programa é software livre; você pode redistribuí-lo e/ou
    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
    publicada pela Free Software Foundation, versão 2.

    Este programa é distribuído na expectativa de ser útil, mas SEM
    QUALQUER GARANTIA; sem mesmo a garantia implícita de
    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
    detalhes.
 
    Você deve ter recebido uma cópia da Licença Pública Geral GNU
    junto com este programa; se não, escreva para a Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
    02111-1307, USA.

    Cópias da licença disponíveis em /Sappiens/_doc/licenca

*/

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
