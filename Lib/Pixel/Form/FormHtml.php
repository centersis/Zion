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

namespace Pixel\Form;

use Zion\Form\FormHtml as FormHtmlZion;
use Zion\Form\FormInputHidden;
use Zion\Layout\JavaScript;
use Zion\Banco\Conexao;
use Pixel\Form\MasterDetail\FormMasterDetail;
use Pixel\Form\MasterDetail\MasterDetailHtml;
use Pixel\Form\MasterVinculo\FormMasterVinculo;
use Pixel\Form\MasterVinculo\MasterVinculoHtml;
use Pixel\Arquivo\ArquivoUpload;

class FormHtml extends FormHtmlZion
{

    public function __construct()
    {
        parent::__construct();
    }

    public function montaSuggest(FormInputSuggest $config)
    {
        $this->preConfig($config);

        $nome = $config->getNome();
        $valorOriginal = '';
        $retHidden = '';
        if ($config->getHidden()) {

            $valorOriginal = $config->getValor();

            if (\is_numeric($valorOriginal)) {
                $con = Conexao::conectar();
                $qb = $con->qb();

                $qb->select($config->getCampoDesc())
                        ->from($config->getTabela(), '')
                        ->where($qb->expr()->eq($config->getCampoCod(), ':campoCod'))
                        ->setParameter('campoCod', $valorOriginal, \PDO::PARAM_INT);

                $valorTexto = $con->execRLinha($qb);
                $config->setValor($valorTexto);              
            }
            
            $config->setNome('sisL' . $nome);
            $config->setId('sisL' . $nome);

            $cofHidden = new FormInputHidden('hidden', $nome);
            $cofHidden->setValor($valorOriginal);

            $retHidden = $this->montaHidden($cofHidden);
        }

        $attr = \array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('size', $config->getLargura()),
            $this->attr('caixa', $config->getCaixa()),
            $this->attr('autocomplete', 'false'),
            $this->attr('placeholder', $config->getPlaceHolder())));

        $ret = \vsprintf($this->prepareInput(count($attr), $config), $attr);

        return $ret . $retHidden;
    }

    public function montaTexto($config)
    {
        $this->preConfig($config);

        return parent::montaTexto($config);
    }

    public function montaSenha($config)
    {
        $this->preConfig($config);

        return parent::montaSenha($config);
    }

    public function montaTextArea(FormInputTextArea $config)
    {
        $this->preConfig($config);

        $jsFinal = '';
        if ($config->getAcao() == 'editor') {

            $idEditor = $config->getNomeForm() . $config->getId();
            $config->setId($idEditor);

            $js = new JavaScript();
            $jsFinal = $js->entreJS("CKEDITOR.replace( '" . $config->getId() . "' );");

            $classCss = $config->getClassCss() . ' ignore';
            $config->setClassCss($classCss);
        }

        return parent::montaTextArea($config) . $jsFinal;
    }

    public function montaData(FormInputData $config)
    {
        $this->preConfig($config);

        return parent::montaData($config);
    }

    public function montaHora(FormInputHora $config)
    {
        $this->preConfig($config);

        return parent::montaHora($config);
    }

    public function montaCpf(FormInputCpf $config)
    {
        $this->preConfig($config);

        return parent::montaCpf($config);
    }

    public function montaCnpj(FormInputCnpj $config)
    {
        $this->preConfig($config);

        return parent::montaCnpj($config);
    }

    public function montaCep(FormInputCep $config)
    {
        $this->preConfig($config);

        return parent::montaCep($config);
    }

    public function montaNumber(FormInputNumber $config)
    {
        $this->preConfig($config);

        return parent::montaNumber($config);
    }

    public function montaFloat(FormInputFloat $config)
    {
        $this->preConfig($config);

        return parent::montaFloat($config);
    }

    public function montaEscolha($config, $form)
    {
        $expandido = $config->getExpandido();
        $multiplo = $config->getMultiplo();
        $chosen = $config->getChosen();

        if (($expandido === false and $multiplo === false) or $chosen === true) {

            $this->preConfig($config);

            if ($config->getCampoDependencia()) {

                $config->setContainer('sisDP' . $config->getNome());

                $acao = $form->getAcao();

                if ($acao == 'cadastrar') {
                    $config->setArray([]);
                    $config->setTabela('');
                } else if ($acao == 'alterar') {

                    $dMetodo = $config->getMetodoDependencia();
                    $dClasse = $config->getClasseDependencia();
                    $dNomeCampo = $config->getCampoDependencia();
                    $dObjeto = $form->getObjetos($dNomeCampo);
                    $dCod = $dObjeto->getValor();
                    $novoNamespace = \str_replace('/', '\\', $dClasse);

                    $instancia = '\\' . $novoNamespace;

                    if (!\is_numeric($dCod)) {
                        $dCod = 0;
                    }

                    $i = new $instancia();

                    $formE = $i->{$dMetodo}($dCod);

                    $objeto = $formE->getObjetos($config->getNome());
                    $objeto->setValor($config->getValor());
                    $objeto->setContainer('dp' . $config->getNome());

                    $campo = $formE->getFormHtml($config->getNome());

                    return $campo;
                }
            }

            return parent::montaEscolha($config, false);
        } else {
            $retorno = '';

            $config->setClassCss('px');

            if ($expandido === true and $multiplo === true) {

                $retorno = $this->montaCheckRadioPixel('check', parent::montaEscolha($config, true), $config);
            } else if ($expandido === true and $multiplo === false) {

                $retorno = $this->montaCheckRadioPixel('radio', parent::montaEscolha($config, true), $config);
            }

            return $retorno;
        }
    }

    public function montaTelefone(FormInputTelefone $config)
    {
        $this->preConfig($config);

        return parent::montaTelefone($config);
    }

    public function montaEmail(FormInputEmail $config)
    {
        $this->preConfig($config);

        return parent::montaEmail($config);
    }

    /**
     * 
     * @param \Pixel\Form\FormMasterDetail $config
     */
    public function montaMasterDetail(FormMasterDetail $config, $nomeForm)
    {
        return (new MasterDetailHtml())->montaMasterDetail($config, $nomeForm);
    }

    /**
     * 
     * @param \Pixel\Form\FormMasterVinculo $config
     */
    public function montaMasterVinculo(FormMasterVinculo $config, $nomeForm)
    {
        return (new MasterVinculoHtml())->montaMasterVinculo($config, $nomeForm);
    }

    private function montaCheckRadioPixel($tipo, $arrayCampos, $config)
    {
        $type = $tipo === 'check' ? 'checkbox' : 'radio';
        $classCss = $config->getInLine() === true ? $type . '-inline' : $type;

        $retorno = '';
        foreach ($arrayCampos as $dadosCampo) {

            $retorno .= \sprintf('<label class="%s">%s<span class="lbl">%s</span></label>', $classCss, $dadosCampo['html'], $dadosCampo['label']);
        }

        return $retorno;
    }

    public function montaUpload(FormUpload $config)
    {
        $arquivoUpload = new ArquivoUpload();

        $this->preConfig($config);

        //$complemento = $config->getComplemento() . 'onchange="sisUploadMultiplo(\'' . $config->getId() . '\');"';
        $complemento = 'onchange="sisUploadMultiplo(\'' . $config->getId() . '\');"';
        $config->setComplemento($complemento);

        $nomeTratado = \str_replace('[]', '', $config->getNome());

        $htmlAlterar = $arquivoUpload->visualizarArquivos($nomeTratado, $config->getCodigoReferencia());

        return \sprintf('%s<div id="sisUploadMultiploLista' . $config->getId() . '"></div>', parent::montaUpload($config) . $htmlAlterar);
    }

    public function montaButton($config)
    {
        return parent::montaButton($config);
    }

    public function montaLayout(FormLayout $config)
    {
        return $config->getConteudo();
    }

    private function preConfig($config)
    {
        $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }
    }

}
