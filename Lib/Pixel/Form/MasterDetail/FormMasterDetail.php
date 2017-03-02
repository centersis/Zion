<?php

namespace Pixel\Form\MasterDetail;

use Zion\Exception\RuntimeException;
use Pixel\Form\FormSetPixel;

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
    private $codigo;
    private $campos;
    private $botaoRemover;
    private $botaoAdd;
    private $totalItensInicio;
    private $valorItensDeInicio;
    private $objetoPai;
    private $campoReferencia;
    private $codigoReferencia;
    private $objetoRemover;
    private $metodoRemover;
    private $view;
    private $parametrosView;
    private $namespace;
    private $crudExtra;
    private $iUpload;
    private $dados;
    private $gravar;
    private $naoRepetir;
    private $sqlBusca;
    private $complementoExterno;
    private $permitirIgnorar;

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
        $this->botaoAdd = true;
        $this->addMax = 20;
        $this->addMin = 0;
        $this->addTexto = 'Novo Registro';
        $this->totalItensInicio = 1;
        $this->view = 'master_detail.html.twig';
        $this->dados = [];
        $this->gravar = true;
        $this->naoRepetir = [];
        $this->permitirIgnorar = true;

        $this->formSetPixel = new FormSetPixel();
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    /**
     * Nome do componente
     * @param string $nome
     * @return \Pixel\Form\FormMasterDetail
     * @throws RuntimeException
     */
    public function setNome($nome)
    {
        if (!\is_string($nome) or empty($nome)) {
            throw new RuntimeException('setNome: Informe o nome do componente corretamente!');
        }

        $this->nome = $nome;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Identificador do componente
     * @param string $identifica
     * @return \Pixel\Form\FormMasterDetail
     * @throws RuntimeException
     */
    public function setIdentifica($identifica)
    {
        if (!\is_string($identifica) or empty($identifica)) {
            throw new RuntimeException('setIdentifica: Informe o identificador do componente corretamente!');
        }

        $this->identifica = $identifica;
        return $this;
    }

    public function getIdentifica()
    {
        return $this->identifica;
    }

    /**
     * Número máximo de itens que podem ser adicionados, por padrão o valor 
     * inicial deste atributo é 20, se for informado 0 (Zero) o componente irá 
     * entender que podem ser adicionados infinitos itens.
     * @param int $addMax
     * @return \Pixel\Form\FormMasterDetail
     * @throws RuntimeException
     */
    public function setAddMax($addMax)
    {
        if (!\is_numeric($addMax) or $addMax < 0) {
            throw new RuntimeException('setAddMax: Informe o identificador do componente corretamente!');
        }

        $this->addMax = $addMax;
        return $this;
    }

    public function getAddMax()
    {
        return $this->addMax;
    }

    /**
     * Número mínimo de itens que podem ser adicionados, por padrão o valor 
     * inicial deste atributo é 0, oque siguinifica que ele aceita 0 (Zero) ou
     * mais itens.
     * @param int $addMin
     * @throws RuntimeException
     */
    public function setAddMin($addMin)
    {
        if (!\is_numeric($addMin) or $addMin < 0) {
            throw new RuntimeException('setAddMin: Informe o identificador do componente corretamente!');
        }

        $this->addMin = $addMin;
        return $this;
    }

    public function getAddMin()
    {
        return $this->addMin;
    }

    /**
     * Texto do botão de adicionar itens, aceita HTML
     * @param string $addTexto
     * @return \Pixel\Form\FormMasterDetail
     * @throws RuntimeException
     */
    public function setAddTexto($addTexto)
    {
        if (!\is_string($addTexto) or empty($addTexto)) {
            throw new RuntimeException('setAddTexto: Informe o texto de botão de adição corretamente!');
        }

        $this->addTexto = $addTexto;
        return $this;
    }

    public function getAddTexto()
    {
        return $this->addTexto;
    }

    /**
     * Tabela do banco de dados
     * @param string $tabela
     * @return \Pixel\Form\FormMasterDetail
     * @throws RuntimeException
     */
    public function setTabela($tabela)
    {
        if (!\is_string($tabela) or empty($tabela)) {
            throw new RuntimeException('setTabela: Informe a tabela de referencia corretamente!');
        }

        $this->tabela = $tabela;
        return $this;
    }

    public function getTabela()
    {
        return $this->tabela;
    }

    public function setCodigo($codigo)
    {
        if (!\is_string($codigo) or empty($codigo)) {
            throw new RuntimeException('setCodigo: Informe o código da tabela corretamente!');
        }

        $this->codigo = $codigo;
        return $this;
    }

    public function getCodigo()
    {
        return \strtolower($this->codigo);
    }

    /**
     * Deve ser informado um array com a seguinte estrutura:
     * A chave do array deve conter a coluna da tabela informada em setTabela()
     * O valor da chave, deve ser um objeto do tipo Form configurado de acordo
     * com as nescessidades
     * @param array $campos
     * @return \Pixel\Form\FormMasterDetail
     * @throws RuntimeException
     */
    public function setCampos($campos)
    {
        if (!\is_array($campos) or empty($campos)) {
            throw new RuntimeException('setCampos: Informe a configuração de campos corretamente!');
        }

        $this->campos = $campos;
        return $this;
    }

    public function getCampos()
    {
        return $this->campos;
    }

    /**
     * Indica se o botão remover deve existir
     * @param boolean $botaoRemover
     * @return \Pixel\Form\FormMasterDetail
     * @throws RuntimeException
     */
    public function setBotaoRemover($botaoRemover)
    {
        if (!\is_bool($botaoRemover)) {
            throw new RuntimeException('setBotaoRemover: Informe um valor booleano!');
        }

        $this->botaoRemover = $botaoRemover;
        return $this;
    }

    public function getBotaoRemover()
    {
        return $this->botaoRemover;
    }

    /**
     * Indica se o botão Add deve existir
     * @param boolean $botaoAdd
     * @return \Pixel\Form\FormMasterDetail
     * @throws RuntimeException
     */
    public function setBotaoAdd($botaoAdd)
    {
        if (!\is_bool($botaoAdd)) {
            throw new RuntimeException('setBotaoAdd: Informe um valor booleano!');
        }

        $this->botaoAdd = $botaoAdd;
        return $this;
    }

    public function getBotaoAdd()
    {
        return $this->botaoAdd;
    }

    /**
     * Indica o número de itens que devem existir inicialemnte
     * @param int $totalItensInicio
     * @return \Pixel\Form\FormMasterDetail
     * @throws RuntimeException
     */
    public function setTotalItensInicio($totalItensInicio)
    {
        if (!\is_numeric($totalItensInicio) or $totalItensInicio < 0) {
            throw new RuntimeException('setTotalItensInicio: Informe um valor numérico maior que zero!');
        }

        $this->totalItensInicio = $totalItensInicio;
        return $this;
    }

    public function getTotalItensInicio()
    {
        return $this->totalItensInicio;
    }

    public function setValorItensDeInicio($valorItensDeInicio)
    {
        if (!empty($valorItensDeInicio)) {

            if (!\is_array($valorItensDeInicio)) {
                throw new RuntimeException('setValorItensDeInicio: Informe um array!');
            }

            $this->valorItensDeInicio = $valorItensDeInicio;
        }

        return $this;
    }

    public function getValorItensDeInicio()
    {
        return $this->valorItensDeInicio;
    }

    public function setObjetoPai($objetoPai)
    {
        if (\is_object($objetoPai)) {
            $this->objetoPai = $objetoPai;
        } else {
            throw new RuntimeException("objetoPai: Valor não é um objeto válido.");
        }

        return $this;
    }

    public function getObjetoPai()
    {
        return $this->objetoPai;
    }

    public function setCampoReferencia($campoReferencia)
    {
        if (!empty($campoReferencia) and \is_string($campoReferencia)) {
            $this->campoReferencia = $campoReferencia;
        } else {
            throw new RuntimeException("campoReferencia: Valor não é válido.");
        }

        return $this;
    }

    public function getCampoReferencia()
    {
        return $this->campoReferencia;
    }

    public function setCodigoReferencia($codigoReferencia)
    {
        if (empty($codigoReferencia)) {
            return $this;
        }

        $this->codigoReferencia = $codigoReferencia;

        return $this;
    }

    public function getCodigoReferencia()
    {
        return $this->codigoReferencia;
    }

    public function setObjetoRemover($objetoRemover, $metodoRemover)
    {
        if (\is_object($objetoRemover)) {
            $this->objetoRemover = $objetoRemover;
        } else {
            throw new RuntimeException("objetoRemover: Valor não é um objeto válido.");
        }

        $this->setMetodoRemover($metodoRemover);

        return $this;
    }

    public function getObjetoRemover()
    {
        return $this->objetoRemover;
    }

    private function setMetodoRemover($metodoRemover)
    {
        if (!empty($metodoRemover) and \is_string($metodoRemover)) {
            $this->metodoRemover = $metodoRemover;
        } else {
            throw new RuntimeException("objetoRemover -> metodoRemover: Valor não é válido.");
        }

        return $this;
    }

    public function getMetodoRemover()
    {
        return $this->metodoRemover;
    }

    public function setIUpload($iUpload)
    {
        $this->iUpload = $iUpload;

        return $this;
    }

    public function getIUpload()
    {
        return $this->iUpload;
    }

    public function setView($view, $namespace = '', $parametrosView = [])
    {
        $this->view = $view;

        if ($namespace) {
            $this->setNamespace($namespace);
        }

        if ($parametrosView) {
            $this->setParametrosView($parametrosView);
        }

        return $this;
    }

    public function getView()
    {
        return $this->view;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setParametrosView($parametrosView)
    {
        $this->parametrosView = $parametrosView;
    }

    public function getParametrosView()
    {
        return $this->parametrosView;
    }

    /**
     * 
     * @param type $nome - serve para identificação do campo, não influencia
     * no funcionamento do componente
     * @param array $crudExtra - deve conter um array com 3 posições
     * 1 - Nome do Campo na Tabela
     * 2 - Valor do Campo a ser inserido no banco de dados
     * 3 - Tipo do campo a ser inserido no banco de dados
     * Ex: ...setCrudExtra('organogramaCod', ['organogramaCod',$_SESSION['organogramaCod'],'Inteiro']);
     * @return \Pixel\Form\MasterDetail\FormMasterDetail
     */
    public function setCrudExtra($nome, array $crudExtra)
    {
        $this->crudExtra[$nome] = $crudExtra;

        return $this;
    }

    public function getCrudExtra($nome = '')
    {
        return $nome ? $this->crudExtra[$nome] : $this->crudExtra;
    }

    /**
     * 
     * @param array $dados - Dados recebidos pelo masterDetail devem ser
     * encapsulados em um array, estes dados são setados automaticamente 
     * pelo componente.
     * @return \Pixel\Form\MasterDetail\FormMasterDetail
     */
    public function setDados($dados)
    {
        $this->dados = $dados;

        return $this;
    }

    public function getDados()
    {
        return $this->dados;
    }

    public function setGravar($gravar)
    {
        if (\is_null($gravar) or \is_bool($gravar)) {
            $this->gravar = $gravar;
        } else {
            throw new RuntimeException("gravar: valor informado é inválido, use null, true ou false");
        }

        return $this;
    }

    public function getGravar()
    {
        return $this->gravar;
    }

    public function setNaoRepetir($naoRepetir)
    {
        if (\is_null($naoRepetir) or \is_array($naoRepetir)) {
            $this->naoRepetir = $naoRepetir;
        } else {
            throw new RuntimeException("naoRepetir: valor informado é inválido, use null ou array");
        }

        return $this;
    }

    public function getNaoRepetir()
    {
        return \is_array($this->naoRepetir) ? $this->naoRepetir : [];
    }

    public function setSqlBusca($sqlBusca)
    {
        if (\is_null($sqlBusca) or \is_object($sqlBusca)) {
            $this->sqlBusca = $sqlBusca;
        } else {
            throw new RuntimeException("sqlBusca: valor informado é inválido, use null ou array");
        }

        return $this;
    }

    public function getSqlBusca()
    {
        return $this->sqlBusca;
    }

    public function setComplementoExterno($complementoExterno)
    {
        $this->complementoExterno = $this->formSetPixel->setComplementoExterno($complementoExterno);
        return $this;
    }

    public function getComplementoExterno()
    {
        return $this->complementoExterno;
    }

    public function setPermitirIgnorar($permitirIgnorar)
    {
        $this->permitirIgnorar = $permitirIgnorar;
        return $this;
    }

    public function getPermitirIgnorar()
    {
        return $this->permitirIgnorar;
    }

}
