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

        switch(get_class($form)){

            case 'Zion\Form\FormInputTexto':
                $this->instance = addslashes(get_class($form));
                return $this->validaFormInputTexto($form);
                break;

            case 'Zion\Form\FormInputFloat':
                $this->instance = addslashes(get_class($form));
                return $this->validaFormInputFloat($form);
                break;

            case 'Zion\Form\FormInputDateTime':
                $this->instance = addslashes(get_class($form));
                return $this->validaFormInputDateTime($form);
                break;

            case 'Zion\Form\FormInputNumber':
                $this->instance = addslashes(get_class($form));
                return $this->validaFormInputNumber($form);
                break;

            case 'Zion\Form\FormInputSuggest':
                $this->instance = addslashes(get_class($form));
                return $this->validaFormInputSuggest($form);
                break;

            case 'Zion\Form\FormInputHidden':
                $this->instance = addslashes(get_class($form));
                return $this->validaFormInputHidden($form);
                break;

            case 'Zion\Form\FormInputButton':
                $this->instance = addslashes(get_class($form));
                return $this->validaFormInputButton($form);
                break;

            default:
                $this->instance = addslashes(get_class($form));
                return $this->validaFormInputDefault($form);
                break;

        }

    }

    /**
     * FormValida::validaFormInputTexto()
     * Valida input do tipo Texto
     * 
     * @param \Zion\Form\FormInputTexto $input Instância da classe \Zion\Form\FormInputTexto com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     * @throws \Zion\Form\Exception\FormException se algum erro for encontrado na validação do input.
     */
    private function validaFormInputTexto(\Zion\Form\FormInputTexto $input)
    {
        $attrs  = $this->getAtributos($input);

        $valor = $attrs['valor'];

        foreach($attrs as $attr=>$value){

            switch($attr){
                
                case 'acao':
                    if($value == 'CPF'){
                        if($this->geral->validaCPF($valor) === false){
                            throw new FormException("O valor informado nao e um CPF valido!");
                        }
                    } elseif($value == 'CNPJ'){
                        if($this->geral->validaCNPJ($valor) === false){
                            throw new FormException("O valor informado nao e um CNPJ valido!");
                        }
                    } elseif($value == 'CEP'){
                        if($this->geral->validaCEP($valor) === false){
                            throw new FormException("O valor informado nao e um CEP valido!");
                        }
                    }
                    break;

                case 'maximoCaracteres':
                    if(!empty($value)){
                        if(strlen($valor) > $value){
                            throw new FormException("O valor informado excede o tamanho maximo permitido de ". $value ." caracteres!");
                        }
                    }
                    break;

                case 'minimoCaracteres':
                    if(!empty($value)){
                        if(strlen($valor) < $value){
                            throw new FormException("O valor informado e menor que o tamanho minimo solicitado de ". $value ." caracteres!");
                        }
                    }
                    break;

                case 'valorMinimo':
                    if(is_numeric($value)){
                        if($valor < $value){
                            throw new FormException("O valor informado nao pode ser menor que ". $value ."!");
                        }
                    }
                    break;

                case 'valorMaximo':
                    if(is_numeric($value)){
                        if($valor > $value){
                            throw new FormException("O valor informado nao pode ser maior que ". $value ."!");
                        }
                    }
                    break;

                case 'obrigatorio':
                    if($value === true){
                        if(empty($valor)){
                            throw new FormException("Nenhum valor informado!");
                        }
                    }
                    break;
      
            }

        }

        return true;
    }

    /**
     * FormValida::validaFormInputFloat()
     * Valida input do tipo Float
     * 
     * @param \Zion\Form\FormInputFloat $input Instância da classe \Zion\Form\FormInputFloat com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     * @throws \Zion\Form\Exception\FormException se algum erro for encontrado na validação do input.
     */
    private function validaFormInputFloat(\Zion\Form\FormInputFloat $input)
    {
        $attrs  = $this->getAtributos($input);

        $valor = $attrs['valor'];

        foreach($attrs as $attr=>$value){

            switch($attr){
                
                case 'acao':
                    if($value == 'FLOAT'){
                        if($this->numero->isFloat($valor) === false){
                            throw new FormException("O valor informado nao e um float valido!");
                        }
                    }
                    break;

                case 'valorMinimo':
                    if(is_numeric($value)){
                        if($valor < $value){
                            throw new FormException("O valor informado nao pode ser menor que ". $value ."!");
                        }
                    }
                    break;

                case 'valorMaximo':
                    if(is_numeric($value)){
                        if($valor > $value){
                            throw new FormException("O valor informado nao pode ser maior que ". $value ."!");
                        }
                    }
                    break;
            }

        }

        return true;
    }

    /**
     * FormValida::validaFormInputDateTime()
     * Valida input do tipo Date/Time
     * 
     * @param \Zion\Form\FormInputDateTime $input Instância da classe \Zion\Form\FormInputDateTime com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     * @throws \Zion\Form\Exception\FormException se algum erro for encontrado na validação do input.
     */
    private function validaFormInputDateTime(\Zion\Form\FormInputDateTime $input)
    {
        $attrs  = $this->getAtributos($input);

        $valor = $attrs['valor'];

        foreach($attrs as $attr=>$value){

            switch($attr){
                
                case 'acao':
                    if($value == 'DATA'){
                        if($this->data->validaData($valor) === false){
                            throw new FormException("O valor informado nao e uma data valida!");
                        }
                    } elseif($value == "HORA"){
                        if($this->data->validaHora($valor) === false){
                            throw new FormException("O valor informado nao e uma hora valida!");
                        }
                    }
                    break;

                case 'dataMinima':
                    if(isset($value)){
                        if($this->data->verificaDiferencaDataHora($valor, $value) > 0){
                            throw new FormException("O valor informado nao pode ser menor que ". $value ."!");
                        }
                    }
                    break;

                case 'dataMaxima':
                    if(isset($value)){
                        if($this->data->verificaDiferencaDataHora($valor, $value) < 0){
                            throw new FormException("O valor informado nao pode ser maior que ". $value ."!");
                        }
                    }
                    break;
            }

        }

        return true;
    }

    /**
     * FormValida::validaFormInputNumber()
     * Valida input do tipo Number
     * 
     * @param \Zion\Form\FormInputNumber $input Instância da classe \Zion\Form\FormInputNumber com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     * @throws \Zion\Form\Exception\FormException se algum erro for encontrado na validação do input.
     */
    private function validaFormInputNumber(\Zion\Form\FormInputNumber $input)
    {
        $attrs  = $this->getAtributos($input);

        $valor = $attrs['valor'];

        foreach($attrs as $attr=>$value){

            switch($attr){
                
                case 'acao':
                    if($value == 'NUMBER'){
                        if(is_numeric($valor) === false){
                            throw new FormException("O valor informado nao e um numero valido!");
                        }
                    }
                    break;

                case 'valorMinimo':
                    if(is_numeric($value)){
                        if($valor < $value){
                            throw new FormException("O valor informado nao pode ser menor que ". $value ."!");
                        }
                    }
                    break;

                case 'valorMaximo':
                    if(is_numeric($value)){
                        if($valor > $value){
                            throw new FormException("O valor informado nao pode ser maior que ". $value ."!");
                        }
                    }
                    break;
            }

        }

        return true;
    }

    /**
     * FormValida::validaFormInputSuggest()
     * Valida input do tipo Suggest
     * 
     * @param \Zion\Form\FormInputSuggest $input Instância da classe \Zion\Form\FormInputSuggest com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     * @throws \Zion\Form\Exception\FormException se algum erro for encontrado na validação do input.
     */
    private function validaFormInputSuggest(\Zion\Form\FormInputSuggest $input)
    {
        $attrs  = $this->getAtributos($input);

        $valor = $attrs['valor'];

        foreach($attrs as $attr=>$value){

            switch($attr){
                
                case 'acao':
                    if($value == 'suggest'){
                    }
                    break;
                    
                case 'obrigatorio':
                    if($value === true){
                        if(empty($valor)){
                            throw new FormException("Nenhum valor informado!");
                        }
                    }
                    break;

            }

        }

        return true;
    }

    /**
     * FormValida::validaFormInputHidden()
     * Valida input do tipo Hidden
     * 
     * @param \Zion\Form\FormInputHidden $input Instância da classe \Zion\Form\FormInputHidden com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     * @throws \Zion\Form\Exception\FormException se algum erro for encontrado na validação do input.
     */
    private function validaFormInputHidden(\Zion\Form\FormInputHidden $input)
    {
        $attrs  = $this->getAtributos($input);

        $valor = $attrs['valor'];

        foreach($attrs as $attr=>$value){

            switch($attr){
                
                case 'acao':
                    if($value == 'hidden'){
                    }
                    break;

            }

        }

        return true;
    }

    /**
     * FormValida::validaFormInputButton()
     * Valida input do tipo Button
     * 
     * @param \Zion\Form\FormInputButton $input Instância da classe \Zion\Form\FormInputButton com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     * @throws \Zion\Form\Exception\FormException se algum erro for encontrado na validação do input.
     */
    private function validaFormInputButton(\Zion\Form\FormInputButton $input)
    {
        $attrs  = $this->getAtributos($input);

        $valor = $attrs['valor'];

        foreach($attrs as $attr=>$value){

            switch($attr){
                
                case 'acao':
                    if($value == 'button'){
                    }
                    break;
            }

        }

        return true;
    }

    /**
     * FormValida::validaFormInputDefault()
     * Valida input de tipo indefinido.
     * 
     * @param \Zion\Form $input Instância de uma das classes de formulário com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     * @throws \Zion\Form\Exception\FormException se algum erro for encontrado na validação do input.
     */
    private function validaFormInputDefault($input)
    {
        $attrs  = $this->getAtributos($input);
        
        $valor = $attrs['valor'];

        foreach($attrs as $attr=>$value){

            switch($attr){
                
                case 'maximoCaracteres':
                    if(!empty($value)){
                        if(strlen($valor) > $value){
                            throw new FormException("O valor informado excede o tamanho maximo permitido de ". $value ." caracteres!");
                        }
                    }
                    break;

                case 'minimoCaracteres':
                    if(!empty($value)){
                        if(strlen($valor) < $value){
                            throw new FormException("O valor informado e menor que o tamanho minimo solicitado de ". $value ." caracteres!");
                        }
                    }
                    break;

                case 'valorMinimo':
                    if(is_numeric($value)){
                        if($valor < $value){
                            throw new FormException("O valor informado nao pode ser menor que ". $value ."!");
                        }
                    }
                    break;

                case 'valorMaximo':
                    if(is_numeric($value)){
                        if($valor > $value){
                            throw new FormException("O valor informado nao pode ser maior que ". $value ."!");
                        }
                    }
                    break;

                case 'obrigatorio':
                    if($value === true){
                        if(empty($valor)){
                            throw new FormException("Nenhum valor informado!");
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
            $attrs[$key]    = (is_object($val) ? NULL : (is_string($val) ? strtoupper($val) : $val));
        }

        return $attrs;
    }

}