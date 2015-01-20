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

namespace Zion\Form;

use \Zion\Form\Exception\FormException as FormException;

class FormUpload extends FormBasico
{

    private $tipoBase;
    private $acao;
    private $multiple;
    private $form;
    private $tratarComo;

    /**
     * FormInputTexto::__construct()
     * 
     * @param mixed $acao
     * @param mixed $nome
     * @param mixed $identifica
     * @param mixed $tratarComo
     * @return
     */
    public function __construct($acao, $nome, $identifica, $tratarComo)
    {
        $this->tipoBase = 'upload';        
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setTratarComo($tratarComo);
    }

    /**
     * FormInputTexto::getTipoBase()
     * 
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    /**
     * FormInputTexto::getAcao()
     * 
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }

    public function getMultiple()
    {
        return $this->multiple;
    }
    
    public function setMultiple($multiple)
    {
        if (is_bool($multiple)) {
            $this->multiple = $multiple;
            return $this;
        } else {
            throw new FormException("multiple: Valor nao booleano.");
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
    
    public function getTratarComo()
    {
        return $this->tratarComo;
    }

    public function setTratarComo($tratarComo)
    {
        if (!empty($tratarComo)) {
            $this->tratarComo = $tratarComo;
            return $this;
        } else {
            throw new FormException("tratarComo: Valor desconhecido. Para utilizar recursos de imagem, informe 'IMAGEM' para este atributo.");
        }
    }

    /**
     * Sobrecarga de Metodos Básicos
     */

    /**
     * FormInputTexto::setId()
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
     * FormInputTexto::setNome()
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
     * FormInputTexto::setIdentifica()
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
     * FormInputTexto::setValor()
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
     * FormInputTexto::setValorPadrao()
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
     * FormInputTexto::setDisabled()
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
     * FormInputTexto::setComplemento()
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
     * FormInputTexto::setAtributos()
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
     * FormInputTexto::setClassCss()
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

}
