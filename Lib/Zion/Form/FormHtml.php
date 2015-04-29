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

use Zion\Form\FormAtributos;
use Zion\Form\FormInputCpf;
use Zion\Form\FormInputCnpj;
use Zion\Form\FormInputCep;
use Zion\Form\FormInputEmail;
use Zion\Form\FormInputTelefone;
use Zion\Form\FormInputSenha;
use Zion\Form\FormInputTextArea;
use Zion\Form\FormInputData;
use Zion\Form\FormInputHora;
use Zion\Form\FormInputNumber;
use Zion\Form\FormInputFloat;
use Zion\Form\FormEscolha;

class FormHtml extends FormAtributos
{

    public function __construct()
    {
        parent::__construct();
    }

    public function opcoesBasicas($config)
    {
        $valor = $config->getValor() ? $config->getValor() : $config->getValorPadrao();
        
        return [$this->attr('name', $config->getNome()),
            $this->attr('id', $config->getId() ? $config->getId() : $config->getNome()),
            $this->attr('value', $valor),
            $this->attr('complemento', $config->getComplemento()),
            $this->attr('disabled', $config->getDisabled()),
            $this->attr('classCss', $config->getClassCss())];
    }

    public function montaHiddenHtml(FormInputHidden $config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'hidden')]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaUploadHtml(FormUpload $config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'file'),
            $this->attr('multiple', $config->getMultiple()),
            $this->attr('form', $config->getForm())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaTextoHtml(FormInputTexto $config)
    {
        $type = 'text';

        $attr = array_merge($this->opcoesBasicas($config), [
            $this->attr('type', $type),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('size', $config->getLargura()),
            $this->attr('caixa', $config->getCaixa()),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('autocomplete', $config->getAutoComplete())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaCpfHtml(FormInputCpf $config)
    {

        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaCnpjHtml(FormInputCnpj $config)
    {

        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaCepHtml(FormInputCep $config)
    {

        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaEmailHtml(FormInputEmail $config)
    {

        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }
    
    public function montaCorHtml($config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'hidden')]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaTelefoneHtml(FormInputTelefone $config)
    {

        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaSenhaHtml(FormInputSenha $config)
    {

        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'password'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaTextAreaHtml(FormInputTextArea $config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'textarea'),
            $this->attr('maxlength', $config->getMaximoCaracteres()),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('readonly', $config->getReadonly()),
            $this->attr('colunas', $config->getColunas()),
            $this->attr('linhas', $config->getLinhas()),
            $this->attr('form', $config->getForm())]);

        $attr[] = $this->attr('valueTextArea', $config->getValor());

        return \vsprintf($this->prepareTextArea(\count($attr), $config), $attr);
    }

    public function montaDataHtml(FormInputData $config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getDataMaxima()),
            $this->attr('min', $config->getDataMinima())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaHoraHtml(FormInputHora $config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text'),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getHoraMaxima()),
            $this->attr('min', $config->getHoraMinima())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaNumberHtml(FormInputNumber $config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'number'),
            $this->attr('size', $config->getLargura()),
            $this->attr('placeholder', $config->getPlaceHolder()),
            $this->attr('max', $config->getValorMaximo()),
            $this->attr('min', $config->getValorMinimo())]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaFloatHtml(FormInputFloat $config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('type', 'text')]);

        return \vsprintf($this->prepareInput(\count($attr), $config), $attr);
    }

    public function montaEscolhaHtml(FormEscolha $config, $retornarArray)
    {
        return (new EscolhaHtml())->montaEscolha($config, $retornarArray);
    }

    public function montaButtonHtml($config)
    {
        $attr = \array_merge($this->opcoesBasicas($config), [
            $this->attr('name', $config->getNome()),
            $this->attr('id', $config->getId()),
            $this->attr('type', $config->getAcao()),
            $this->attr('formmethod', $config->getMetodo()),
            $this->attr('formaction', $config->getAction()),
            $this->attr('formtarget', $config->getTarget())]);

        $attr[] = $this->attr('valueButton', $config->getValor());

        return \vsprintf($this->prepareButton(\count($attr), $config), $attr);
    }

    public function montaLayoutHtml(FormLayout $config)
    {
        return $config->getConteudo();
    }
}
