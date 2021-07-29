<?php

namespace Zion\Pixel\Form;

use Zion\Validacao\Valida;
use Zion\Form\FormValida as FormValidaZion;

class FormValida extends FormValidaZion
{

    private $instance;
    private $instaceBasico = 'Zion\Form\FormBasico';
    private $texto;
    private $numero;
    private $data;
    private $geral;

    public function __construct()
    {
        parent::__construct();
        $valida = new Valida();

        $this->texto = $valida->texto();
        $this->numero = $valida->numero();
        $this->data = $valida->data();
        $this->geral = $valida->geral();

        $this->instaceBasico = addslashes($this->instaceBasico);
    }

    /**
     * FormValida::validar()
     * Detecta o tipo de input a ser validado, seta informações básicas necessárias para a validação.
     * 
     * @param Zion\Form $form Instância de uma classe de formulário com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     */
    public function validar($form)
    {

        $this->instance = addslashes(get_class($form));
        return $this->validaFormInput($form);
    }

    /**
     * FormValida::validaFormInputTexto()
     * Valida input do tipo Texto
     * 
     * @param \Zion\Form\FormInputTexto $input Instância da classe \Zion\Form\FormInputTexto com as configurações do input a ser validado.
     * @return bool True, em caso de input válido, void otherwise.
     */
    protected function validaFormInput($input)
    {
        $attrs = $this->getAtributos($input);

        $userValue = $input->getValor();
        $identifica = $this->texto->removerAcentos($input->getIdentifica());

        if (strtoupper($input->getAcao()) == 'SUGGEST') {
            //Ainda não implementado
        } elseif (strtoupper($input->getAcao()) == 'HIDDEN') {
            //Ainda não implementado.
        } elseif (strtoupper($input->getAcao()) == 'BUTTON') {
            //Ainda não implementado.
        }

        parent::validaFormInput($input);

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

        foreach ((array) $input as $key => $val) {

            $key = preg_replace(array('/' . $this->instance . '/', '/' . $this->instaceBasico . '/', '/\W/'), array('', '', ''), $key);
            $attrs[$i++] = $key;
        }

        return $attrs;
    }

}
