<?php
namespace Zion\Form;

use \Zion\Form\Exception\FormException as FormException;

class FormUpload extends FormBasico
{

    private $tipoBase;
    private $acao;
    private $multiple;
    private $form;
    private $tratarComo;
    private $modulo;

    public function __construct($acao, $nome, $identifica, $tratarComo)
    {
        $this->tipoBase = 'upload';        
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setTratarComo($tratarComo);
    }


    public function getTipoBase()
    {
        return $this->tipoBase;
    }

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
        if (\is_bool($multiple)) {
            $this->multiple = $multiple;
            return $this;
        } else {
            throw new FormException("multiple: Valor não booleano.");
        }
    }
    
    public function getForm()
    {
        return $this->form;
    }

    public function setForm($form)
    {
        if (!\is_null($form)) {
            $this->form = $form;
            return $this;
        } else {
            throw new FormException("form: Nenhum valor informado");
        }
    }
    
    public function getTratarComo()
    {
        return \strtoupper($this->tratarComo);
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
    
    public function getModulo()
    {
        return $this->modulo;
    }

    public function setModulo($modulo)
    {
        $this->modulo = $modulo;            
        
        return $this;
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
