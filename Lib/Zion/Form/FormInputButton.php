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

class FormInputButton extends FormBasico
{
    private $tipoBase;
    private $acao;
    private $metodo;
    private $action;
    private $target;
    private $label;
    
    public function __construct($acao, $nome, $identifica)
    {
        $this->tipoBase = 'button';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setValor($identifica);
    }
    
    public function getTipoBase()
    {
        return $this->tipoBase;
    }
    
    public function getAcao()
    {
        return $this->acao;
    }
    
    public function setMetodo($metodo)
    {
        if(!is_null($metodo)){
            $this->metodo = $metodo;        
            return $this;
        } else {
            throw new FormException("metodo: Nenhum valor informado.");
        }
    }
    
    public function getMetodo()
    {
        return $this->metodo;
    }
    
    public function setAction($action)
    {
        if(!is_null($action)){
            $this->action = $action;
            return $this;
        } else {
            throw new FormException("action: Nenhum valor informado.");
        }

    }
    
    public function getAction()
    {
        return $this->action;
    }
    
    public function setTarget($target)
    {
        if(!is_null($target)){
            $this->target = $target;
            return $this;
        } else {
            throw new FormException("target: Nenhum valor informado.");
        }
    }
    
    public function getTarget()
    {
        return $this->target;
    }

    public function setLabel($label)
    {
        if(!is_null($label)){
            $this->label = $label;
            return $this;
        } else {
            throw new FormException("label: Nenhum valor informado.");
        }

    }
    
    public function getLabel()
    {
        return $this->label;
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
}