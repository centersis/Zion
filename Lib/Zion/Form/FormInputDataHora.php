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
use Zion\Form\Exception\FormException as FormException;
use Zion\Validacao\Data;

class FormInputDataHora extends \Zion\Form\FormBasico implements FilterableInput
{
    private $tipoBase;
    private $acao;
    private $obrigatorio;
    private $dataHoraMinima;
    private $dataHoraMaxima;
    private $placeHolder;
    private $aliasSql;
    private $categoriaFiltro;

    private $data;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'dataHora';
        $this->acao = $acao;
        $this->mostrarSegundos = false;

        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
        $this->categoriaFiltro = FilterableInput::GREATER_THAN;

        $this->data = Data::instancia();
    }

    /**
     * FormInputData::getTipoBase()
     *
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    /**
     * FormInputData::getAcao()
     *
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * FormInputData::setDataHoraMinima()
     *
     * @return
     */
    public function setDataHoraMinima($dataHoraMinima)
    {
        if($this->data->validaData($dataHoraMinima) === true){

            if(isset($this->dataHoraMaxima) and $this->data->verificaDiferencaDataHora($this->dataHoraMaxima, $dataHoraMinima) == 1) {
                throw new FormException("dataHoraMinima não pode ser maior que dataHoraMaxima.");
            }

            $this->dataHoraMinima = $dataHoraMinima;
            return $this;

        } else {
            throw new FormException("dataHoraMinima: O valor informado não é uma data/hora válida.");
        }
    }

    /**
     * FormInputData::getDataHoraMinima()
     *
     * @return
     */
    public function getDataHoraMinima()
    {
        return $this->dataHoraMinima;
    }

    /**
     * FormInputData::setDataHoraMaxima()
     *
     * @return
     */
    public function setDataHoraMaxima($dataHoraMaxima)
    {
        if($this->data->validaData($dataHoraMaxima)){

            if(isset($this->dataHoraMinima) and $this->data->verificaDiferencaDataHora($this->dataHoraMinima, $dataHoraMaxima) == -1) {
                throw new FormException("dataHoraMinima não pode ser maior que dataHoraMaxima.");
            }

            $this->dataHoraMaxima = $dataHoraMaxima;
            return $this;

        } else {
            throw new FormException("dataHoraMaxima: O valor informado não é uma data/hora válida.");
        }
    }

    /**
     * FormInputData::getDataHoraMaxima()
     *
     * @return
     */
    public function getDataHoraMaxima()
    {
        return $this->dataHoraMaxima;
    }

    /**
     * FormInputData::setPlaceHolder()
     *
     * @return
     */
    public function setPlaceHolder($placeHolder)
    {
        if (!empty($placeHolder)) {
            $this->placeHolder = $placeHolder;
            return $this;
        } else {
            throw new FormException("placeHolder: Nenhum valor informado");
        }
    }

    /**
     * FormInputData::getPlaceHolder()
     *
     * @return
     */
    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }

    /**
     * FormInputData::setObrigarorio()
     *
     * @return
     */
    public function setObrigarorio($obrigatorio)
    {
        if (is_bool($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
            return $this;
        } else {
            throw new FormException("obrigatorio: Valor não booleano");
        }
    }

    /**
     * FormInputData::getObrigatorio()
     *
     * @return
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    /**
     * FormInputData::getAliasSql()
     *
     * @return string
     */
    public function getAliasSql(){
        return $this->aliasSql;
    }

    /**
     * FormInputData::setAliasSql()
     *
     * @param string $aliasSql
     *
     */
    public function setAliasSql($aliasSql)
    {
        if (!is_null($aliasSql)) {
            $this->aliasSql = $aliasSql;
            return $this;
        } else {
            throw new FormException("aliasSql: Nenhum valor informado");
        }
    }

    /**
     * Sobrecarga de Metodos Básicos
     */

    /**
     * FormInputData::setId()
     *
     * @return
     */
    public function setId($id)
    {
        parent::setId($id);
        return $this;
    }

    /**
     * FormInputData::setNome()
     *
     * @return
     */
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }

    /**
     * FormInputData::setIdentifica()
     *
     * @return
     */
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }

    /**
     * FormInputData::setValor()
     *
     * @return
     */
    public function setValor($valor)
    {
        parent::setValor($valor);
        return $this;
    }

    public function getValor()
    {
        $valor = $this->data->converteDataHora(parent::getValor());

        return $valor;
    }

    /**
     * FormInputData::setValorPadrao()
     *
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }

    /**
     * FormInputData::setDisabled()
     *
     * @return
     */
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }

    /**
     * FormInputData::setComplemento()
     *
     * @return
     */
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    /**
     * FormInputData::setAtributos()
     *
     * @return
     */
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }

    /**
     * FormInputData::setClassCss()
     *
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

    /**
     * {@inheritdoc}
     *
     * @return self
     */
    public function setCategoriaFiltro($tipo)
    {
        $this->categoriaFiltro = $tipo;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getCategoriaFiltro()
    {
        return $this->categoriaFiltro;
    }
}
