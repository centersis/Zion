<?php
/**
 * \Zion\Form\FormValida
 * @author Feliphe Bueno - feliphezion@gmail.com
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

class FormValida
{
    private $instance;
    private $instaceBasico = 'Zion\Form\FormBasico';
    
    private $texto;
    private $numero;
    private $data;
    private $geral;

    /**
     * FormValida::__construct()
     * Construtor
     * 
     * @return void
     */
    public function __construct()
    {
        $valida = new \Zion\Validacao\Valida();

        $this->texto    = $valida->texto();
        $this->numero   = $valida->numero();
        $this->data     = $valida->data();
        $this->geral    = $valida->geral();
        
        $this->instaceBasico = addslashes($this->instaceBasico);
    }
    
    /**
     * FormValida::validar()
     * Detecta o tipo de input a ser validado, seta informações básicas necessárias para a validação.
     * 
     * @param Zion\Form $form Instância de uma classe de formulário com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     * @throws \Zion\Form\Exception\InvalidArgumeException se $form não for uma instância válida de uma das classes de formulário.
     */
    public function validar($form){

        if(is_object($form) === false or preg_match('/^[Zion\\Form\\\]{10}/', @get_class($form)) != true){
            throw new FormInvalidArgumeException('O argumento informado nao e uma instancia de uma classe de formulario valida!');
        }

        $this->instance = addslashes(get_class($form));
        return $this->validaFormInput($form);
    }

    /**
     * FormValida::validaFormInputTexto()
     * Valida input do tipo Texto
     * 
     * @param \Zion\Form\FormInputTexto $input Instância da classe \Zion\Form\FormInputTexto com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     * @throws \Zion\Form\Exception\FormException se algum erro for encontrado na validação do input.
     */
    protected function validaFormInput($input)
    {
        $attrs  = $this->getAtributos($input);

        $userValue  = $input->getValor();
        $identifica = $this->texto->removerAcentos($input->getIdentifica());

        foreach($attrs as $key=>$value){

            switch($value){
                
                case 'acao':
                    if(strtoupper($input->getAcao()) == 'CPF'){
                        if($this->geral->validaCPF($userValue) === false){
                            throw new FormException($identifica .": O valor informado nao e um CPF valido!");
                        }
                    } elseif(strtoupper($input->getAcao()) == 'CNPJ'){
                        if($this->geral->validaCNPJ($userValue) === false){
                            throw new FormException($identifica .": O valor informado nao e um CNPJ valido!");
                        }
                    } elseif(strtoupper($input->getAcao()) == 'CEP'){
                        if($this->geral->validaCEP($userValue) === false){
                            throw new FormException($identifica .": O valor informado nao e um CEP valido!");
                        }
                    } elseif(strtoupper($input->getAcao()) == 'FLOAT'){
                        if($this->numero->isFloat($userValue) === false){
                            throw new FormException($identifica .": O valor informado nao e um float valido!");
                        }
                    } elseif(strtoupper($input->getAcao()) == 'DATA'){
                        if($this->data->validaData($userValue) === false){
                            throw new FormException($identifica .": O valor informado nao e uma data valida!");
                        }
                    } elseif(strtoupper($input->getAcao()) == "HORA"){
                        if($this->data->validaHora($userValue) === false){
                            throw new FormException($identifica .": O valor informado nao e uma hora valida!");
                        }
                    } elseif(strtoupper($input->getAcao()) == 'NUMBER'){
                        if(is_numeric($userValue) === false){
                            throw new FormException($identifica .": O valor informado nao e um numero valido!");
                        }
                    }
                    break;

                case 'obrigatorio':
                    if($input->getObrigatorio() === true){
                        if(empty($userValue)){
                            throw new FormException($identifica .": Nenhum valor informado!");
                        }
                    }
                    break;

                case 'minimoCaracteres':
                    $val = $input->getMinimoCaracteres();
                    if(!empty($val)){
                        if($this->texto->verificaMinimoCaracteres($val, $userValue) === false){
                            throw new FormException($identifica .": O valor informado e menor que o tamanho minimo solicitado de ". $val ." caracteres!");
                        }
                    }
                    break;

                case 'maximoCaracteres':
                    $val = $input->getMaximoCaracteres();
                    if(!empty($val)){
                        if($this->texto->verificaMaximoCaracteres($val, $userValue) === false){
                            throw new FormException($identifica .": O valor informado excede o tamanho maximo permitido de ". $val ." caracteres!");
                        }
                    }
                    break;

                case 'valorMinimo':
                    $val = $input->getValorMinimo();
                    if(is_numeric($val)){
                        if($this->numero->verificaValorMinimo($val, $userValue) === false){
                            throw new FormException($identifica .": O valor informado nao pode ser menor que ". $val ."!");
                        }
                    }
                    break;

                case 'valorMaximo':
                    $val = $input->getValorMaximo();
                    if(is_numeric($val)){
                        if($this->numero->verificaValorMaximo($val, $userValue) === false){
                            throw new FormException($identifica .": O valor informado nao pode ser maior que ". $val ."!");
                        }
                    }
                    break;

                case 'dataMinima':
                    $val = $input->getDataMinima();
                    if(isset($val)){
                        if($this->data->verificaDiferencaDataHora($userValue, $val) > 0){
                            throw new FormException($identifica .": O valor informado nao pode ser menor que ". $val ."!");
                        }
                    }
                    break;

                case 'dataMaxima':
                    $val = $input->getDataMaxima();
                    if(isset($val)){
                        if($this->data->verificaDiferencaDataHora($userValue, $val) < 0){
                            throw new FormException($identifica .": O valor informado nao pode ser maior que ". $val ."!");
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
    private function getAtributos($input)
    {
        $attrs = array();
        $i = 0;

        foreach((array) $input as $key=>$val){

            $key            = preg_replace(array('/'. $this->instance .'/', '/'. $this->instaceBasico .'/', '/\W/'), array('', '', ''), $key);
            $attrs[$i++]    = $key;
        }

        return $attrs;
    }

}