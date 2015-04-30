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
/**
 * \Zion\Form\FormValida
 * @author Feliphe "O Retaliador" Bueno - feliphezion@gmail.com
 * @since 25/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Validação automatizada dos formulários
 *
 */

namespace Zion\Form;

use \Zion\Form\Exception\FormException as FormException;
use \Zion\Form\Exception\FormInvalidArgumentException as FormInvalidArgumeException;
use \Sappiens\Accounts\Login\LoginClass as LoginClass;

class FormValida
{

    /**
     * @var string $instance Recebe o nome da classe passada por parâmetro, para detecção automática dos atributos da classe.
     */
    private $instance;

    /**
     * @var string $instaceBasico Nome da classe básica extendida por todas as classes em \Zion\Form;
     */
    private $instanceBasico = 'Zion\Form\FormBasico';

    /**
     * @var string $instanceZion Nome da classe Zion extendida por todas as classes em \Pixel\Form;
     */
    private $instanceZion;

    /**
     * @var string $instanceParent Nome da classe parent extendida por todas as classes em \Zion\Form;
     */
    private $instanceParent;

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
     * FormValida::__construct()
     * Construtor
     * 
     * @return void
     */
    public function __construct()
    {
        $valida = \Zion\Validacao\Valida::instancia();

        $this->texto = $valida->texto();
        $this->numero = $valida->numero();
        $this->data = $valida->data();
        $this->geral = $valida->geral();

        $this->instanceBasico = \addslashes($this->instanceBasico);
    }

    /**
     * FormValida::validar()
     * Detecta o tipo de input a ser validado, seta informações básicas necessárias para a validação.
     * 
     * @param Zion\Form $form Instância de uma classe de formulário com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     * @throws \Zion\Form\Exception\InvalidArgumeException se $form não for uma instância válida de uma das classes de formulário.
     */
    public function validar($form)
    {
        if (\is_object($form) === false) {
            throw new FormInvalidArgumeException('O argumento informado não é uma instância de uma classe válida!');
        }

        $className = \get_class($form);

        $vendorName = \substr($className, 0, \strpos($className, '\\'));

        $this->instance = \addslashes($className);

        $this->instanceZion = \preg_replace('/[' . $vendorName . ']{' . \strlen($vendorName) . '}/', 'Zion', $this->instance);

        $this->instanceParent = \addslashes(\get_parent_class($form));

        return $this->validaFormInput($form);
    }

    /**
     * FormValida::validaFormInputTexto()
     * Valida input do tipo Texto
     * 
     * @param mixed $input Instância da classe \Zion\Form; ou \Pixel\Form; com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     * @throws \Zion\Form\Exception\FormException se algum erro for encontrado na validação do input.
     */
    protected function validaFormInput($input)
    {
        $attrs = $this->getAtributos($input);

        if (!\in_array('valor', $attrs)) {
            return true;
        }

        $userValue = $input->getValor();
        $identifica = $input->getIdentifica();
        $obrigatorio = (in_array('obrigatorio', $attrs) ? $input->getObrigatorio() : false);

        $acao = \strtoupper($input->getAcao());

        foreach (($attrs) as $value) {

            switch ($value) {

                case 'acao':

                    if (!empty($userValue) or $obrigatorio === true) {
                        switch ($acao) {
                            case 'CPF':
                                if ($this->geral->validaCPF($userValue) === false) {
                                    throw new FormException($identifica . ": O valor informado não é um CPF válido!");
                                }
                                break;

                            case 'CNPJ':
                                if ($this->geral->validaCNPJ($userValue) === false) {
                                    throw new FormException($identifica . ": O valor informado não é um CNPJ válido!");
                                }
                                break;

                            case 'CEP':
                                if ($this->geral->validaCEP($userValue) === false) {
                                    throw new FormException($identifica . ": O valor informado não é um CEP válido!");
                                }
                                break;

                            case 'FLOAT':
                                if ($this->numero->isFloat($userValue) === false) {
                                    throw new FormException($identifica . ": O valor informado não é um float válido!");
                                }
                                break;

                            case 'DATA':
                                if ($this->data->validaData($userValue) === false) {
                                    throw new FormException($identifica . ": O valor informado não é uma data válida!");
                                }
                                break;

                            case 'HORA':
                                if ($this->data->validaHora($userValue) === false) {
                                    throw new FormException($identifica . ": O valor informado não é uma hora válida!");
                                }
                                break;

                            case 'NUMBER':
                                if (\is_numeric($userValue) === false) {
                                    throw new FormException($identifica . ": O valor informado não é um numero válido!");
                                }
                                break;

//                            case 'ESCOLHA':
//                                if (empty($userValue) and $input->getObrigatorio() === true) {
//                                    throw new FormException($identifica . ": Você deve selecionar uma das opções!");
//                                }
//                                break;
                        }
                    }

                    if ($acao == 'CHOSEN' || $acao == 'ESCOLHA') {

                        $fv = new EscolhaHtml();
                        $valoresReais = $fv->dadosCampo($input);
                        $dependencia = $input->getCampoDependencia();

                        if (\is_array($userValue)) {

                            if (\count($userValue) < 1 and $input->getObrigatorio() === true) {
                                throw new FormException($identifica . ": Você deve selecionar uma ou mais opções!");
                            }

                            if (!$dependencia) {
                                foreach ($userValue as $valorSelecionado) {
                                    if (!\array_key_exists($valorSelecionado, $valoresReais)) {
                                        throw new FormException($identifica . ": O valor recuperado não corresponde a um valor válido!");
                                    }
                                }
                            }
                        } else {

                            if ($input->getMultiplo() === true) {
                                throw new FormException($identifica . ": A opção 'multiplo' está ativada, o valor submetido deve ser um array!");
                            }

                            if (empty($userValue) and $input->getObrigatorio() === true) {
                                throw new FormException($identifica . ": selecione uma ou mais opções!");
                            }

                            if (!$dependencia and $userValue and ! \array_key_exists($userValue, $valoresReais)) {
                                throw new FormException($identifica . ": O valor recuperado não corresponde a um valor válido!");
                            }
                        }
                    }

                    if ($acao == 'SENHA' and $input->getNome() == 'validaSenhaUser') {
                        $loginClass = new LoginClass();
                        if ($loginClass->validaSenhaUsuario($_SESSION['usuarioCod'], $userValue) === false) {
                            throw new FormException($identifica . ": Você deve informar corretamente sua <br />senha para concluir estas alterações!");
                        }
                    }

                    break;

                case 'selecaoMinima':
                    $val = $input->getSelecaoMinima();
                    if (!empty($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if (\count($userValue) < $val) {
                            throw new FormException($identifica . ": Você deve selecionar no mínimo " . $val . " # opções! ");
                        }
                    }
                    break;

                case 'selecaoMaxima':
                    $val = $input->getSelecaoMaxima();
                    if (!empty($val) and ( \is_array($userValue) or $obrigatorio === true)) {
                        if (\count($userValue) > $val) {
                            throw new FormException($identifica . ": Você deve selecionar no máximo " . $val . " opções!");
                        }
                    }
                    break;

                case 'obrigatorio':
                    if ($input->getObrigatorio() === true) {
                        if (empty($userValue) and $userValue <> 0) {
                            throw new FormException($identifica . ": Nenhum valor informado!");
                        }
                    }
                    break;

                case 'minimoCaracteres':
                    $val = $input->getMinimoCaracteres();
                    if (!empty($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if ($this->texto->verificaMinimoCaracteres($val, $userValue) === false) {
                            throw new FormException($identifica . ": O valor informado é menor que o tamanho mínimo solicitado de " . $val . " caracteres!");
                        }
                    }
                    break;

                case 'maximoCaracteres':
                    $val = $input->getMaximoCaracteres();
                    if (!empty($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if ($this->texto->verificaMaximoCaracteres($val, $userValue) === false) {
                            throw new FormException($identifica . ": O valor informado excede o tamanho máximo permitido de " . $val . " caracteres!");
                        }
                    }
                    break;

                case 'valorMinimo':
                    $val = $input->getValorMinimo();
                    if (\is_numeric($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if ($this->numero->verificaValorMinimo($val, $userValue) === false) {
                            throw new FormException($identifica . ": O valor informado não pode ser menor que " . $val . "!");
                        }
                    }
                    break;

                case 'valorMaximo':
                    $val = $input->getValorMaximo();
                    if (\is_numeric($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if ($this->numero->verificaValorMaximo($val, $userValue) === false) {
                            throw new FormException($identifica . ": O valor informado não pode ser maior que " . $val . "!");
                        }
                    }
                    break;

                case 'dataMinima':
                    $val = $input->getDataMinima();
                    if (isset($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if ($this->data->verificaDiferencaDataHora($userValue, $val) > 0) {
                            throw new FormException($identifica . ": O valor informado não pode ser menor que " . $val . "!");
                        }
                    }
                    break;

                case 'dataMaxima':
                    $val = $input->getDataMaxima();
                    if (isset($val) and ( !empty($userValue) or $obrigatorio === true)) {
                        if ($this->data->verificaDiferencaDataHora($userValue, $val) < 0) {
                            throw new FormException($identifica . ": O valor informado não pode ser maior que " . $val . "!");
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
     * @param \Zion\Form $input Instância de uma das classes de formulário com as configurações do input a ser validado.
     * @return array()
     */
    protected function getAtributos($input)
    {
        $attrs = array();

        $i = 0;
        foreach (\array_keys((array) $input) as $key) {

            $key = \preg_replace(array('/' . $this->instance . '/', '/' . $this->instanceZion . '/', '/' . $this->instanceBasico . '/', '/' . $this->instanceParent . '/', '/\W/'), array('', '', '', '', ''), $key);
            $attrs[$i++] = $key;
        }

        return $attrs;
    }

}
