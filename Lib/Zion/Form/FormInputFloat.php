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

use Zion\Form\Exception\FormException;
use Zion\Validacao\Numero;
use Zion\Form\FormBasico;

class FormInputFloat extends FormBasico implements FilterableInput
{
    private $tipoBase;
    private $acao;
    private $largura;
    private $obrigatorio;
    private $valorMaximo;
    private $valorMinimo;
    private $prefixo;
    private $placeHolder;
    private $aliasSql;
    private $categoriaFiltro;
    private $filtroPadrao;

    private $numero;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'float';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
        $this->filtroPadrao = '=';
        $this->categoriaFiltro = FilterableInput::EQUAL;

        $this->numero = Numero::instancia();
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function setLargura($largura)
    {
        if (\preg_match('/^[0-9]{1,}[%]{1}$|^[0-9]{1,}[px]{2}$|^[0-9]{1,}$/', $largura)) {
            $this->largura = $largura;
            return $this;
        } else {
            throw new FormException("largura: O valor não está nos formatos aceitos: 10%; 10px; ou 10");
        }
    }

    public function getLargura()
    {
        return $this->largura;
    }

    public function setValorMaximo($valorMaximo)
    {
        if($this->numero->isFloat($valorMaximo) === true){

            if(isset($this->valorMinimo) and ($valorMaximo < $this->valorMinimo)) {
                throw new FormException("valorMaximo não pode ser menor que valorMinimo.");
            }

            $this->valorMaximo = $valorMaximo;
            return $this;
        } else {
            throw new FormException("valorMaximo: O valor informado não é float.");
        }
    }

    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }

    public function setValorMinimo($valorMinimo)
    {
        if($this->numero->isFloat($valorMinimo) === true){

            if(isset($this->valorMaximo) and ($valorMinimo > $this->valorMaximo)) {
                throw new FormException("valorMinimo não pode ser maior que valorMaximo.");
            }

            $this->valorMinimo = $valorMinimo;
            return $this;
        } else {
            throw new FormException("valorMinimo: O valor informado não é float.");
        }
    }

    public function getValorMinimo()
    {
        return $this->valorMinimo;
    }

    public function setPrefixo($prefixo)
    {
        if(!\is_null($prefixo)){
            $this->prefixo = $prefixo;
            return $this;
        } else {
            throw new FormException("prefixo: Nenhum valor informado.");
        }
    }

    public function getPrefixo()
    {
        return $this->prefixo;
    }

    public function setObrigarorio($obrigatorio)
    {
        if (\is_bool($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
            return $this;
        } else {
            throw new FormException("obrigatorio: Valor não booleano");
        }
    }

    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    public function setPlaceHolder($placeHolder)
    {
        if (!empty($placeHolder)) {
            $this->placeHolder = $placeHolder;
            return $this;
        } else {
            throw new FormException("placeHolder: Nenhum valor informado");
        }
    }

    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }

    public function setAliasSql($aliasSql)
    {
        if (!\is_null($aliasSql)) {
            $this->aliasSql = $aliasSql;
            return $this;
        } else {
            throw new FormException("aliasSql: Nenhum valor informado");
        }
    }

    public function getAliasSql(){
        return $this->aliasSql;
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

    public function getValor()
    {
        $valor = parent::getValor();

        return $valor ? $this->numero->floatCliente($valor) : $valor;
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

    public function setCategoriaFiltro($tipo)
    {
        $this->categoriaFiltro = $tipo;

        return $this;
    }

    public function getCategoriaFiltro()
    {
        return $this->categoriaFiltro;
    }
    
    public function setFiltroPadrao($filtroPadrao)
    {
        $this->filtroPadrao = $filtroPadrao;

        return $this;
    }

    public function getFiltroPadrao()
    {
        return $this->filtroPadrao;
    }
}
