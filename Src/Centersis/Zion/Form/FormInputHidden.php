<?php
 
namespace Centersis\Zion\Form;

use Centersis\Zion\Exception\ErrorException;

use Centersis\Zion\Form\FormBasico;

class FormInputHidden extends FormBasico
{

    private $tipoBase;
    private $acao;
    private $aliasSql;
    private $obrigatorio;

    public function __construct($acao, $nome)
    {
        $this->tipoBase = 'hidden';        
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    public function getAcao()
    {
        return $this->acao;
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

    public function getAliasSql(){
        return $this->aliasSql;
    }

    public function setObrigatorio($obrigatorio)
    {
        if (!\is_null($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
            return $this;
        } else {
            throw new ErrorException("obrigatorio: Nenhum valor informado");
        }
    }    

    public function getObrigatorio(){
        return $this->obrigatorio;
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

}
