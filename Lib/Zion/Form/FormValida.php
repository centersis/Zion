<?php

namespace Zion\Form;

use Zion\Exception\ErrorException;
use Zion\Exception\ValidationException;
use Zion\Validacao\Valida;

class FormValida
{

    /**
     * @var object $texto Instância da classe de validação de strings
     */
    private $texto;

    /**
     * @var object $numero Instância da classe de validação de valores monetários e numéricos
     */
    private $numero;

    /**
     * @var object $data Instância da classe de validação de data e hora
     */
    private $data;

    /**
     * @var object $geral Instância da classe de validação de tipos especiais
     */
    private $geral;

    /**
     * @var object $instances
     */
    private $instances = [];

    /**
     * FormValida::__construct()
     * Construtor
     *
     * @return void
     */
    public function __construct()
    {
        $valida = Valida::instancia();

        $this->texto = $valida->texto();
        $this->numero = $valida->numero();
        $this->data = $valida->data();
        $this->geral = $valida->geral();
    }

    /**
     * FormValida::configuraInstances()
     * Detecta o tipo de input a ser validado, seta informações básicas necessárias para a validação.
     *
     * @param Zion\Form $formInput Instância de uma classe de formulário com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     */
    private function configuraInstances($formInput)
    {
        if (\is_object($formInput) === false) {
            throw new ErrorException('O argumento informado não é uma instância de uma classe válida!');
        }

        $className = \get_class($formInput);

        $vendorName = \substr($className, 0, \strpos($className, '\\'));

        $this->setInstances([\addslashes($className),
            \preg_replace('/[' . $vendorName . ']{' . \strlen($vendorName) . '}/', 'Zion', \addslashes($className)),
            \addslashes(\get_parent_class($formInput)),
            \addslashes("Zion\Form\FormBasico"),
            '\W'
        ]);

        return $this;
    }

    /**
     * FormValida::validar()
     * Valida input do tipo Texto
     *
     * @param mixed $formInput Instância da classe \Zion\Form; ou \Pixel\Form; com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     */
    public function validar($formInput)
    {
        $attrs = $this->getAtributos($formInput);

        if (!\in_array('valor', $attrs)) {
            return true;
        }

        $userValue = $formInput->getValor();
        $identifica = $formInput->getIdentifica();
        $obrigatorio = (\in_array('obrigatorio', $attrs) ? $formInput->getObrigatorio() : false);
        $disabled = (\in_array('disabled', $attrs) ? $formInput->getDisabled() : false);

        $acao = \strtoupper($formInput->getAcao());

        if ($disabled === true) {
            return true;
        }

        foreach (($attrs) as $value) {

            switch ($value) {

                case 'acao':

                    if (!empty($userValue) or $obrigatorio === true) {
                        switch ($acao) {
                            case 'CPF':
                                if ($this->geral->validaCPF($userValue) === false) {
                                    throw new ValidationException($identifica . ": O valor informado não é um CPF válido!");
                                }
                                break;

                            case 'CNPJ':
                                if ($this->geral->validaCNPJ($userValue) === false) {
                                    throw new ValidationException($identifica . ": O valor informado não é um CNPJ válido!");
                                }
                                break;

                            case 'CEP':
                                if ($this->geral->validaCEP($userValue) === false) {
                                    throw new ValidationException($identifica . ": O valor informado não é um CEP válido!");
                                }
                                break;

                            case 'FLOAT':
                                if ($this->numero->isFloat($userValue) === false) {
                                    throw new ValidationException($identifica . ": O valor informado não é um float válido!");
                                }
                                break;

                            case 'DATA': case 'DATE':
                                if ($this->data->validaData($userValue) === false) {
                                    throw new ValidationException($identifica . ": O valor informado não é uma data válida!");
                                }
                                break;

                            case 'HORA':
                                if ($this->data->validaHora($userValue) === false) {
                                    throw new ValidationException($identifica . ": O valor informado não é uma hora válida!");
                                }
                                break;

                            case 'NUMBER':
                                if (\is_numeric($userValue) === false) {
                                    throw new ValidationException($identifica . ": O valor informado não é um numero válido!");
                                }
                                break;

//                            case 'ESCOLHA':
//                                if (empty($userValue) and $formInput->getObrigatorio() === true) {
//                                    throw new ValidationException($identifica . ": Você deve selecionar uma das opções!");
//                                }
//                                break;
                        }
                    }

                    if ($acao == 'CHOSEN' || $acao == 'ESCOLHA') {

                        $fv = new EscolhaHtml();
                        $valoresReais = $fv->dadosCampo($formInput);
                        $dependencia = $formInput->getCampoDependencia();

                        if ($formInput->getMultiplo() === true and !\is_array($userValue)){
                            $userValue = [];
                        }
                        
                        if (\is_array($userValue)) {

                            if (\count($userValue) < 1 and $formInput->getObrigatorio() === true) {
                                throw new ValidationException($identifica . ": Você deve selecionar uma ou mais opções!");
                            }

                            if (!$dependencia) {
                                foreach ($userValue as $valorSelecionado) {
                                    if (!\array_key_exists($valorSelecionado, $valoresReais)) {
                                        throw new ValidationException($identifica . ": O valor recuperado não corresponde a um valor válido!");
                                    }
                                }
                            }
                        } else {

                            if ($userValue == '' and $formInput->getObrigatorio() === true) {
                                throw new ValidationException($identifica . ": selecione uma ou mais opções!");
                            }

                            if (!$dependencia and $userValue and ! \array_key_exists($userValue, $valoresReais)) {
                                throw new ValidationException($identifica . ": O valor recuperado não corresponde a um valor válido!");
                            }
                        }
                    }

                    break;

                case 'selecaoMinima':
                    $val = $formInput->getSelecaoMinima();
                    if (!empty($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if (\count($userValue) < $val) {
                            throw new ValidationException($identifica . ": Você deve selecionar no mínimo " . $val . " # opções! ");
                        }
                    }
                    break;

                case 'selecaoMaxima':
                    $val = $formInput->getSelecaoMaxima();
                    if (!empty($val) and ( \is_array($userValue) or $obrigatorio === true)) {
                        if (\count(\is_array($userValue) ? $userValue : []) > $val) {
                            throw new ValidationException($identifica . ": Você deve selecionar no máximo " . $val . " opções!");
                        }
                    }
                    break;

                case 'obrigatorio':
                    if ($formInput->getObrigatorio() === true) {
                        if (empty($userValue) and $userValue <> 0) {
                            throw new ValidationException($identifica . ": Nenhum valor informado!");
                        }
                    }
                    break;

                case 'minimoCaracteres':
                    $val = $formInput->getMinimoCaracteres();
                    if (!empty($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if ($this->texto->verificaMinimoCaracteres($val, $userValue) === false) {
                            throw new ValidationException($identifica . ": O valor informado é menor que o tamanho mínimo solicitado de " . $val . " caracteres!");
                        }
                    }
                    break;

                case 'maximoCaracteres':
                    $val = $formInput->getMaximoCaracteres();
                    if (!empty($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if ($this->texto->verificaMaximoCaracteres($val, $userValue) === false) {
                            throw new ValidationException($identifica . ": O valor informado excede o tamanho máximo permitido de " . $val . " caracteres!");
                        }
                    }
                    break;

                case 'valorMinimo':
                    $val = $formInput->getValorMinimo();
                    if (\is_numeric($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if ($this->numero->verificaValorMinimo($val, $userValue) === false) {
                            throw new ValidationException($identifica . ": O valor informado não pode ser menor que " . $val . "!");
                        }
                    }
                    break;

                case 'valorMaximo':
                    $val = $formInput->getValorMaximo();
                    if (\is_numeric($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if ($this->numero->verificaValorMaximo($val, $userValue) === false) {
                            throw new ValidationException($identifica . ": O valor informado não pode ser maior que " . $val . "!");
                        }
                    }
                    break;

                case 'dataMinima':
                    $val = $formInput->getDataMinima();
                    if (isset($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if ($this->data->verificaDiferencaDataHora($userValue, $val) > 0) {
                            throw new ValidationException($identifica . ": O valor informado não pode ser menor que " . $val . "!");
                        }
                    }
                    break;

                case 'dataMaxima':
                    $val = $formInput->getDataMaxima();
                    if (isset($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if ($this->data->verificaDiferencaDataHora($userValue, $val) < 0) {
                            throw new ValidationException($identifica . ": O valor informado não pode ser maior que " . $val . "!");
                        }
                    }
                    break;
            }
        }

        return true;
    }

    /**
     * FormValida::getAtributos()
     * Detecta todos os atributos da classe e seus respectivos valores.
     *
     * @param \Zion\Form $formInput Instância de uma das classes de formulário com as configurações do input a ser validado.
     * @return array()
     */
    protected function getAtributos($formInput)
    {
        $this->configuraInstances($formInput);

        $instances = \array_map(function($val) {
            return '/' . $val . '/';
        }, $this->instances);

        $attrs = array();

        $i = 0;
        foreach (\array_keys((array) $formInput) as $key) {
            $key = \preg_replace($instances, '', $key);
            $attrs[$i++] = $key;
        }

        return $attrs;
    }

    protected function getInstances()
    {
        return $this->instances;
    }

    protected function setInstances($instance)
    {
        if (\is_array($instance)) {
            $this->instances = $instance;
        } else {
            \array_push($this->instances, $instance);
        }
        return $this;
    }

}
