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

namespace Pixel\Form;

use \Zion\FormFormException\FormException as FormException;

class FormMasterDetail
{

    private $acao;
    private $tipoBase;
    private $nome;
    private $identifica;
    private $addMax;
    private $addMin;
    private $addTexto;
    private $tabela;
    private $campos;
    private $botaoRemover;
    private $totalItensInicio;

    /**
     * Construtor
     * @param string $nome
     */
    public function __construct($nome, $identifica)
    {
        $this->tipoBase = 'masterDetail';
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
    
    public function getIdentifica()
    {
        return $this->identifica;
    }

    public function getAddMax()
    {
        return $this->addMax;
    }

    public function getAddMin()
    {
        return $this->addMin;
    }

    public function getAddTexto()
    {
        return $this->addTexto;
    }

    public function getTabela()
    {
        return $this->tabela;
    }

    public function getCampos()
    {
        return $this->campos;
    }

    public function getBotaoRemover()
    {
        return $this->botaoRemover;
    }

    public function getTotalItensInicio()
    {
        return $this->totalItensInicio;
    }

    /**
     * Nome do componente
     * @param string $nome
     * @return \Pixel\Form\FormMasterDetail
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
    
    /**
     * Identificador do componente
     * @param string $identifica
     * @return \Pixel\Form\FormMasterDetail
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

    /**
     * Número máximo de itens que podem ser adicionados, por padrão o valor 
     * inicial deste atributo é 20, se for informado 0 (Zero) o componente irá 
     * entender que podem ser adicionados infinitos itens.
     * @param int $addMax
     * @return \Pixel\Form\FormMasterDetail
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

    /**
     * Texto do botão de adicionar itens, aceita HTML
     * @param string $addTexto
     * @return \Pixel\Form\FormMasterDetail
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

    /**
     * Tabela do banco de dados
     * @param string $tabela
     * @return \Pixel\Form\FormMasterDetail
     * @throws FormException
     */
    public function setTabela($tabela)
    {
        if (!\is_string($tabela) or empty($tabela)) {
            throw new FormException('setTabela: Informe a tabela de referencia corretamente!');
        }

        $this->tabela = $tabela;
        return $this;
    }

    /**
     * Deve ser informado um array com a seguinte estrutura:
     * A chave do array deve conter a coluna da tabela informada em setTabela()
     * O valor da chave, deve ser um objeto do tipo Form configurado de acordo
     * com as nescessidades
     * @param array $campos
     * @return \Pixel\Form\FormMasterDetail
     * @throws FormException
     */
    public function setCampos($campos)
    {
        if (!\is_array($campos) or empty($campos)) {
            throw new FormException('setCampos: Informe a configuração de campos corretamente!');
        }

        $this->campos = $campos;
        return $this;
    }

    /**
     * Indica se o botão remover deve existir
     * @param boolean $botaoRemover
     * @return \Pixel\Form\FormMasterDetail
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

    /**
     * Indica o número de itens que devem existir inicialemnte
     * @param int $totalItensInicio
     * @return \Pixel\Form\FormMasterDetail
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

}
