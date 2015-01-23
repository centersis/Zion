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
 * \Pixel\Form\FormValida
 * @author Feliphe "O Retaliador" Bueno - feliphezion@gmail.com
 * @since 09/10/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Validação automatizada dos formulários
 *
 */

namespace Pixel\Form;

use \Zion\Form\Exception\FormException as FormException;
use \Zion\Form\Exception\FormInvalidArgumentException as FormInvalidArgumeException;

class FormValida extends \Zion\Form\FormValida
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
        parent::__construct();
        $valida = new \Zion\Validacao\Valida();

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
     * @throws \Zion\Form\Exception\InvalidArgumeException se $form não for uma instância válida de uma das classes de formulário.
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
     * @throws \Zion\Form\Exception\FormException se algum erro for encontrado na validação do input.
     */
    protected function validaFormInput($input)
    {
        $attrs = $this->getAtributos($input);

        $userValue = $input->getValor();
        $identifica = $this->texto->removerAcentos($input->getIdentifica());

        if (strtoupper($input->getAcao()) == 'SUGGEST') {
            //Ainda nao implementado
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
    private function getAtributos($input)
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
