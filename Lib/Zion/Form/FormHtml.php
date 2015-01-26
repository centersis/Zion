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

class FormHtml extends \Zion\Form\FormAtributos
{

    /**
     * FormHtml::__construct()
     * 
     * @return
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * FormHtml::opcoesBasicas()
     * 
     * @param mixed $config
     * @return
     */
    public function opcoesBasicas($config)
    {
        return [$this->attr('name', $config->getNome()),
            $this->attr('id', $config->getId() ? $config->getId() : $config->getNome()),
            $this->attr('value', $config->getValor()),
            $this->attr('complemento', $config->getComplemento()),
            $this->attr('disabled', $config->getDisabled()),
            $this->attr('classCss', $config->getClassCss())];
    }

    /**
     * FormHtml::montaHidden()
     * 
     * @param mixed $config
     * @return
     */
    public function montaHidden(FormInputHidden $config)
    {
        $attr = array_merge($this->opcoesBasicas($config),[
            $this->attr('type', 'hidden')]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }
    
    public function montaUpload(FormUpload $config)
    {
        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'file'),
            $this->attr('multiple', $config->getMultiple()),
            $this->attr('form', $config->getForm())]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaTexto()
     * 
     * @param mixed $config
     * @return
     */
    public function montaTexto(FormInputTexto $config)
    {
        $type = 'text';

        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', $type),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('size', $config->getLargura()),
            $this->attr('caixa', $config->getCaixa()),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('autocomplete', $config->getAutoComplete())]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaCpf()
     * 
     * @param mixed $config
     * @return
     */
    public function montaCpf(\Zion\Form\FormInputCpf $config)
    {

        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaCnpj()
     * 
     * @param mixed $config
     * @return
     */
    public function montaCnpj(\Zion\Form\FormInputCnpj $config)
    {

        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaCep()
     * 
     * @param mixed $config
     * @return
     */
    public function montaCep(\Zion\Form\FormInputCep $config)
    {

        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaEmail()
     * 
     * @param mixed $config
     * @return
     */
    public function montaEmail(\Zion\Form\FormInputEmail $config)
    {

        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaTelefone()
     * 
     * @param mixed $config
     * @return
     */
    public function montaTelefone(\Zion\Form\FormInputTelefone $config)
    {

        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaSenha()
     * 
     * @param mixed $config
     * @return
     */
    public function montaSenha(\Zion\Form\FormInputSenha $config)
    {

        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'password'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaTextArea()
     * 
     * @param mixed $config
     * @return
     */
    public function montaTextArea(\Zion\Form\FormInputTextArea $config)
    {
        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'textarea'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('readonly', $config->getReadonly()),
            $this->attr('colunas', $config->getColunas()),
            $this->attr('linhas', $config->getLinhas()),
            $this->attr('form', $config->getForm())]);
        
        $attr[] = $this->attr('valueTextArea', $config->getValor());

        return vsprintf($this->prepareTextArea(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaData()
     * 
     * @param mixed $config
     * @return
     */
    public function montaData(\Zion\Form\FormInputData $config)
    {
        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getDataMaxima()),
            $this->attr('min', $config->getDataMinima())]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaHora()
     * 
     * @param mixed $config
     * @return
     */
    public function montaHora(\Zion\Form\FormInputHora $config)
    {
        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getHoraMaxima()),
            $this->attr('min', $config->getHoraMinima())]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaNumber()
     * 
     * @param mixed $config
     * @return
     */
    public function montaNumber(\Zion\Form\FormInputNumber $config)
    {
        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'number'),
            $this->attr('size', $config->getLargura()),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getValorMaximo()),
            $this->attr('min', $config->getValorMinimo())]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaFloat()
     * 
     * @param mixed $config
     * @return
     */
    public function montaFloat(\Zion\Form\FormInputFloat $config)
    {
        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text')]);

        return vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaEscolha()
     * 
     * @param mixed $config
     * @return
     */
    public function montaEscolha(\Zion\Form\FormEscolha $config, $retornarArray)
    {
        return (new \Zion\Form\EscolhaHtml())->montaEscolha($config,$retornarArray);
    }

    /**
     * FormHtml::montaButton()
     * 
     * @param mixed $config
     * @return
     */
    public function montaButton($config)
    {
        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('name', $config->getNome()),
            $this->attr('id', $config->getId()),
            $this->attr('type', $config->getAcao()),
            $this->attr('formmethod', $config->getMetodo()),
            $this->attr('formaction', $config->getAction()),
            $this->attr('formtarget', $config->getTarget())]);

        $attr[] = $this->attr('valueButton', $config->getValor());

        return vsprintf($this->prepareButton(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::montaLayout()
     * 
     * @param mixed $config
     * @return
     */
    public function montaLayout(FormLayout $config)
    {
        return $config->getConteudo();
    }

    /**
     * FormHtml::abreForm()
     * 
     * @param mixed $config
     * @return
     */
    public function abreForm(FormTag $config)
    {
        $attr = [
            $this->attr('name', $config->getNome()),
            $this->attr('id', $config->getId() ? $config->getId() : $config->getNome()),
            $this->attr('action', $config->getAction()),
            $this->attr('autocomplete', $config->getAutoComplete()),
            $this->attr('enctype', $config->getEnctype()),
            $this->attr('method', $config->getMethod()),
            $this->attr('novalidate', $config->getNovalidate()),
            $this->attr('target', $config->getTarget()),
            $this->attr('complemento', $config->getComplemento()),
            $this->attr('classCss', $config->getClassCss())];

        return vsprintf($this->prepareForm(\count($attr), $config), $attr);
    }

    /**
     * FormHtml::fechaForm()
     * 
     * @return
     */
    public function fechaForm()
    {
        return '</form>';
    }

}
