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

namespace Pixel\Form\MasterVinculo;

use \Zion\Form\Exception\FormException;

class FormMasterVinculo
{

    private $acao;
    private $tipoBase;
    private $nome;
    private $identifica;
    private $addMax;
    private $addMin;
    private $gravar;
    private $addTexto;
    private $botaoRemover;
    private $totalItensInicio;
    private $valorItensDeInicio;
    private $objetoPai;
    private $codigoReferencia;
    private $objetoRemover;
    private $metodoRemover;

    /**
     * Construtor
     * @param string $nome
     */
    public function __construct($nome, $identifica)
    {
        $this->tipoBase = 'masterVinculo';
        $this->acao = $this->tipoBase;

        $this->nome = $nome;
        $this->identifica = $identifica;
        $this->botaoRemover = true;
        $this->addMax = 20;
        $this->addMin = 0;
        $this->addTexto = 'Novo Registro';
        $this->totalItensInicio = 1;
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Nome do componente
     * @param string $nome
     * @return \Pixel\Form\FormMasterVinculo
     * @throws FormException
     */
    public function setNome($nome)
    {
        if (!\is_string($nome) or empty($nome)) {
            throw new FormException('setNome: Informe o nome do componente corretamente!');
        }

        $this->nome = $nome;
        return $this;
    }

    public function getIdentifica()
    {
        return $this->identifica;
    }

    /**
     * Identificador do componente
     * @param string $identifica
     * @return \Pixel\Form\FormMasterVinculo
     * @throws FormException
     */
    public function setIdentifica($identifica)
    {
        if (!\is_string($identifica) or empty($identifica)) {
            throw new FormException('setIdentifica: Informe o identificador do componente corretamente!');
        }

        $this->identifica = $identifica;
        return $this;
    }

    public function getAddMax()
    {
        return $this->addMax;
    }

    /**
     * Número máximo de itens que podem ser adicionados, por padrão o valor 
     * inicial deste atributo é 20, se for informado 0 (Zero) o componente irá 
     * entender que podem ser adicionados infinitos itens.
     * @param int $addMax
     * @return \Pixel\Form\FormMasterVinculo
     * @throws FormException
     */
    public function setAddMax($addMax)
    {
        if (!\is_numeric($addMax) or $addMax < 0) {
            throw new FormException('setAddMax: Informe o identificador do componente corretamente!');
        }

        $this->addMax = $addMax;
        return $this;
    }

    public function getAddMin()
    {
        return $this->addMin;
    }

    /**
     * Número mínimo de itens que podem ser adicionados, por padrão o valor 
     * inicial deste atributo é 0, oque siguinifica que ele aceita 0 (Zero) ou
     * mais itens.
     * @param int $addMin
     * @throws FormException
     */
    public function setAddMin($addMin)
    {
        if (!\is_numeric($addMin) or $addMin < 0) {
            throw new FormException('setAddMin: Informe o identificador do componente corretamente!');
        }

        $this->addMin = $addMin;
        return $this;
    }

    public function getAddTexto()
    {
        return $this->addTexto;
    }

    /**
     * Texto do botão de adicionar itens, aceita HTML
     * @param string $addTexto
     * @return \Pixel\Form\FormMasterVinculo
     * @throws FormException
     */
    public function setAddTexto($addTexto)
    {
        if (!\is_string($addTexto) or empty($addTexto)) {
            throw new FormException('setAddTexto: Informe o texto de botão de adição corretamente!');
        }

        $this->addTexto = $addTexto;
        return $this;
    }

    public function getGravar($tabela = '')
    {
        if($tabela and \array_key_exists($tabela, $this->gravar)){
            return $this->gravar[$tabela];
        }
        
        return $this->gravar;
    }

    /**
     * Tabela do banco de dados
     * @param string $tabela
     * @param array $gravar
     * @return \Pixel\Form\FormMasterVinculo
     * @throws FormException
     */
    public function setGravar($tabela, array $gravar)
    {
        $this->gravar[$tabela] = $gravar;
        
        return $this;
    }

    public function getBotaoRemover()
    {
        return $this->botaoRemover;
    }

    /**
     * Indica se o botão remover deve existir
     * @param boolean $botaoRemover
     * @return \Pixel\Form\FormMasterVinculo
     * @throws FormException
     */
    public function setBotaoRemover($botaoRemover)
    {
        if (!\is_bool($botaoRemover)) {
            throw new FormException('setBotaoRemover: Informe um valor booleano!');
        }

        $this->botaoRemover = $botaoRemover;
        return $this;
    }

    public function getTotalItensInicio()
    {
        return $this->totalItensInicio;
    }

    /**
     * Indica o número de itens que devem existir inicialemnte
     * @param int $totalItensInicio
     * @return \Pixel\Form\FormMasterVinculo
     * @throws FormException
     */
    public function setTotalItensInicio($totalItensInicio)
    {
        if (!\is_numeric($totalItensInicio) or $totalItensInicio < 0) {
            throw new FormException('setTotalItensInicio: Informe um valor numérico maior que zero!');
        }

        $this->totalItensInicio = $totalItensInicio;
        return $this;
    }

    public function getValorItensDeInicio()
    {
        return $this->valorItensDeInicio;
    }

    public function setValorItensDeInicio($valorItensDeInicio)
    {
        if (!empty($valorItensDeInicio)) {

            if (!\is_array($valorItensDeInicio)) {
                throw new FormException('setValorItensDeInicio: Informe um array!');
            }

            $this->valorItensDeInicio = $valorItensDeInicio;
        }

        return $this;
    }

    public function getObjetoPai()
    {
        return $this->objetoPai;
    }

    public function setObjetoPai($objetoPai)
    {
        if (\is_object($objetoPai)) {
            $this->objetoPai = $objetoPai;
        } else {
            throw new FormException("objetoPai: Valor não é um objeto válido.");
        }

        return $this;
    }

    public function getCodigoReferencia()
    {
        return $this->codigoReferencia;
    }

    public function setCodigoReferencia($codigoReferencia)
    {
        if (empty($codigoReferencia)) {
            return $this;
        }

        if (\is_numeric($codigoReferencia)) {
            $this->codigoReferencia = $codigoReferencia;
        } else {
            throw new FormException("codigoReferencia: Valor não numérico.");
        }

        return $this;
    }

    public function getObjetoRemover()
    {
        return $this->objetoRemover;
    }

    public function setObjetoRemover($objetoRemover, $metodoRemover)
    {
        if (\is_object($objetoRemover)) {
            $this->objetoRemover = $objetoRemover;
        } else {
            throw new FormException("objetoRemover: Valor não é um objeto válido.");
        }

        $this->setMetodoRemover($metodoRemover);

        return $this;
    }

    public function getMetodoRemover()
    {
        return $this->metodoRemover;
    }

    private function setMetodoRemover($metodoRemover)
    {
        if (!empty($metodoRemover) and \is_string($metodoRemover)) {
            $this->metodoRemover = $metodoRemover;
        } else {
            throw new FormException("objetoRemover -> metodoRemover: Valor não é válido.");
        }

        return $this;
    }

}
