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

namespace Pixel\Form;

class FormChosen extends \Zion\Form\FormEscolha
{

    private $toolTipMsg;
    private $emColunaDeTamanho;
    private $layoutPixel;
    private $processarJS;
    private $tipoFiltro;
    private $campoDependencia;
    private $metodoDependencia;
    private $classeDependencia;
   
    private $formSetPixel;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        parent::__construct($acao, $nome, $identifica, $obrigatorio);
        parent::setChosen(true);
        $this->setMultiplo(false);
        $this->tipoFiltro = 'ValorFixo';
        $this->formSetPixel = new \Pixel\Form\FormSetPixel();
    }

    public function setObrigarorio($obrigatorio)
    {
        parent::setObrigarorio($obrigatorio);
        return $this;
    }
    
    public function setSelecaoMaxima($selecaoMaxima)
    {
        parent::setSelecaoMaxima($selecaoMaxima);
        return $this;
    }
    
    public function setSelecaoMinima($selecaoMinima)
    {
        parent::setSelecaoMinima($selecaoMinima);
        return $this;
    }
    
    public function setMultiplo($multiplo)
    {
        parent::setMultiplo($multiplo);
        return $this;
    }

    public function setOrdena($ordena)
    {
        parent::setOrdena($ordena);
        return $this;
    }

    public function setArray($array)
    {
        parent::setArray($array);
        return $this;
    }

    public function setInicio($inicio)
    {
        parent::setInicio($inicio);
        return $this;
    }

    public function setTabela($tabela)
    {
        parent::setTabela($tabela);
        return $this;
    }

    public function setCampoCod($campoCod)
    {
        parent::setCampoCod($campoCod);
        return $this;
    }

    public function setCampoDesc($campoDesc)
    {
        parent::setCampoDesc($campoDesc);
        return $this;
    }

    public function setWhere($where)
    {
        parent::setWhere($where);
        return $this;
    }

    public function setSqlCompleto($sqlCompleto)
    {
        parent::setSqlCompleto($sqlCompleto);
        return $this;
    }

    public function setIdConexao($idConexao)
    {
        parent::setIdConexao($idConexao);
        return $this;
    }
    
    public function setToolTipMsg($toolTipMsg)
    {
        $this->toolTipMsg = $this->formSetPixel->setToolTipMsg($toolTipMsg);
        return $this;
    }

    public function getToolTipMsg()
    {
        return $this->toolTipMsg;
    }
    
    public function setEmColunaDeTamanho($emColunaDeTamanho)
    {        
        $this->emColunaDeTamanho = $this->formSetPixel->setEmColunaDeTamanho($emColunaDeTamanho);
        return $this;
    }

    public function getemColunaDeTamanho()
    {
        return $this->emColunaDeTamanho;
    }
    
    public function setLayoutPixel($layoutPixel)
    {
        $this->layoutPixel = $this->formSetPixel->setLayoutPixel($layoutPixel);
        return $this;
    }
    
    public function getLayoutPixel()
    {
        return $this->layoutPixel;
    }
    
    public function setProcessarJS($processarJS)
    {
        $this->processarJS = $this->formSetPixel->setProcessarJS($processarJS);
        return $this;
    }
    
    public function getProcessarJS()
    {
        return $this->processarJS;
    }
    
    public function setTipoFiltro($tipoFiltro)
    {
        $this->tipoFiltro = $this->formSetPixel->setTipoFiltro($tipoFiltro);
        return $this;
    }
    
    public function getTipoFiltro()
    {
        return $this->tipoFiltro;
    }

    public function setAliasSql($aliasSql){
        parent::setAliasSql($aliasSql);
        return $this;
    }
    
    public function setDependencia($campoDependencia, $metodoDependencia, $classeDependencia)
    {
        $this->campoDependencia = $campoDependencia;
        $this->metodoDependencia = $metodoDependencia;
        $this->classeDependencia = \str_replace('\\', '/', $classeDependencia);

        return $this;
    }

    public function getCampoDependencia()
    {
        return $this->campoDependencia;
    }

    public function getMetodoDependencia()
    {
        return $this->metodoDependencia;
    }
    
    public function getClasseDependencia()
    {
        return $this->classeDependencia;
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
