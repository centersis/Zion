<?php

namespace Pixel\Form;

class FormHtml extends \Zion\Form\FormHtml
{

    public function __construct()
    {
        parent::__construct();
    }

    public function montaSuggest(FormInputSuggest $config)
    {
        $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        $attr = array_merge($this->opcoesBasicas($config), array(
            $this->attr('type', 'text'),
            $this->attr('size', $config->getLargura()),
            $this->attr('caixa', $config->getCaixa()),
            $this->attr('placeholder', $config->getPlaceHolder())));

        $ret = vsprintf($this->prepareInput(count($attr), $config), $attr);


        if ($config->getHiddenValue()) {

            $cofHidden = new \Zion\Form\FormInputHidden('hidden', $config->getHiddenValue());
            $cofHidden->setValor($config->getValor());

            $ret.= $this->montaHidden($cofHidden);
        }

        return $this->prepareInputPixel($config, $ret);
    }

    public function montaTexto($config)
    {
        $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        return $this->prepareInputPixel($config, parent::montaTexto($config));
    }

    public function montaTextArea(FormInputTextArea $config)
    {
        $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control ';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        $jsFinal = '';
        if ($config->getAcao() == 'editor') {

            $idEditor = $config->getNomeForm() . $config->getId();
            $config->setId($idEditor);

            $js = new \Zion\Layout\JavaScript();
            $jsFinal = $js->entreJS("CKEDITOR.replace( '" . $config->getId() . "' );");

            $classCss = $config->getClassCss() . ' ignore';
            $config->setClassCss($classCss);
        }

        return $this->prepareInputPixel($config, parent::montaTextArea($config)) . $jsFinal;
    }

    public function montaData(FormInputData $config)
    {
        $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        return $this->prepareInputPixel($config, parent::montaData($config));
    }

    public function montaHora(FormInputHora $config)
    {
        $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        return $this->prepareInputPixel($config, parent::montaHora($config));
    }
    
    public function montaCpf(FormInputCpf $config)
    {
        $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        return $this->prepareInputPixel($config, parent::montaCpf($config));
    }

    public function montaCnpj(FormInputCnpj $config)
    {
        $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        return $this->prepareInputPixel($config, parent::montaCnpj($config));
    }
    
    public function montaCep(FormInputCep $config)
    {
        $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        return $this->prepareInputPixel($config, parent::montaCep($config));
    }
    
    public function montaNumber(FormInputNumber $config)
    {
        $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        return $this->prepareInputPixel($config, parent::montaNumber($config));
    }

    public function montaFloat(FormInputFloat $config)
    {
        $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        return $this->prepareInputPixel($config, parent::montaFloat($config));
    }

    public function montaEscolha($config, $form)
    {
        $expandido = $config->getExpandido();
        $multiplo = $config->getMultiplo();
        $chosen = $config->getChosen();

        if (($expandido === false and $multiplo === false) or $chosen === true) {

            $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control';
            $config->setClassCss($classCss);

            if ($config->getToolTipMsg()) {
                $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
                $config->setComplemento($complemento);
            }

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
                    $objeto->setLayoutPixel(false);
                    $objeto->setContainer('dp' . $config->getNome());

                    $campo = $formE->getFormHtml($config->getNome());
                    
                    return $this->prepareInputPixel($config, $campo);
                }
            }

            return $this->prepareInputPixel($config, parent::montaEscolha($config, false));
        } else {
            $retorno = '';

            $config->setClassCss('px');

            if ($expandido === true and $multiplo === true) {

                $retorno = $this->montaCheckRadioPixel('check', parent::montaEscolha($config, true), $config);
            } else if ($expandido === true and $multiplo === false) {

                $retorno = $this->montaCheckRadioPixel('radio', parent::montaEscolha($config, true), $config);
            }

            return $this->prepareInputPixel($config, $retorno);
        }
    }

    private function montaCheckRadioPixel($tipo, $arrayCampos, $config)
    {
        $type = $tipo === 'check' ? 'checkbox' : 'radio';
        $classCss = $config->getInLine() === true ? $type . '-inline' : $type;

        $retorno = '';
        foreach ($arrayCampos as $dadosCampo) {

            $retorno .= sprintf('<label class="%s">%s<span class="lbl">%s</span></label>', $classCss, $dadosCampo['html'], $dadosCampo['label']);
        }

        return $retorno;
    }

    public function montaUpload(FormUpload $config)
    {
        $arquivoUpload = new \Pixel\Arquivo\ArquivoUpload();

        $classCss = \str_replace('form-control', '', $config->getClassCss()) . ' form-control';
        $config->setClassCss($classCss);

        if ($config->getToolTipMsg()) {
            $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
            $config->setComplemento($complemento);
        }

        $complemento = $config->getComplemento() . 'onchange="sisUploadMultiplo(\'' . $config->getId() . '\');"';
        $config->setComplemento($complemento);

        $nomeTratado = \str_replace('[]', '', $config->getNome());

        $htmlAlterar = $arquivoUpload->visualizarArquivos($nomeTratado, $config->getCodigoReferencia());

        return $this->prepareInputPixel($config, sprintf('%s<div id="sisUploadMultiploLista' . $config->getId() . '"></div>', parent::montaUpload($config) . $htmlAlterar));
    }

    public function montaButton($config)
    {
        return parent::montaButton($config);
    }

    public function montaLayout(FormLayout $config)
    {
        return $config->getConteudo();
    }

    public function abreForm(FormTag $config)
    {
        $attr = array(
            $this->attr('name', $config->getNome()),
            $this->attr('id', $config->getId() ? $config->getId() : $config->getNome()),
            $this->attr('action', $config->getAction()),
            $this->attr('autocomplete', $config->getAutoComplete()),
            $this->attr('enctype', $config->getEnctype()),
            $this->attr('method', $config->getMethod()),
            $this->attr('novalidate', $config->getNovalidate()),
            $this->attr('target', $config->getTarget()),
            $this->attr('complemento', $config->getComplemento()),
            $this->attr('classCss', $config->getClassCss()));

        return vsprintf($this->prepareForm(count($attr), $config), $attr);
    }

    public function fechaForm()
    {
        return '</form>';
    }

    private function prepareInputPixel($config, $campo)
    {

        if ($config->getLayoutPixel() === false) {
            return $campo;
        }

        $html = new \Zion\Layout\Html();

        $buffer = $html->abreTagAberta('div', array('id' => 'sisFormId' . $config->getId(), 'class' => 'col-sm-' . $config->getEmColunaDeTamanho()));
        $buffer .= $html->abreTagAberta('div', array('class' => 'form-group'));

        $buffer .= $html->abreTagAberta('label', array('for' => $config->getId(), 'class' => 'col-sm-3 control-label'));
        $buffer .= $config->getIdentifica();
        $buffer .= $html->fechaTag('label');

        $buffer .= $html->abreTagAberta('div', array('class' => 'col-sm-9 has-feedback'));

        if (method_exists($config, 'getLabelAntes') or method_exists($config, 'getLabelDepois')) {

            if ($config->getLabelAntes() or $config->getLabelDepois()) {

                $buffer .= $html->abreTagAberta('div', array('class' => 'input-group'));
                if ($config->getLabelAntes()) {
                    $buffer .= $html->abreTagAberta('span', ['id' => 'labelAntes_' . $config->getId(), 'class' => 'input-group-addon bg-default no-border']);
                    $buffer .= $config->getLabelAntes();
                    $buffer .= $html->fechaTag('span');
                }
            }
        }

        $buffer .= $campo;

        if (method_exists($config, 'getIconFA') and $config->getIconFA()) {
            $buffer.= $html->abreTagAberta('span', array('class' => 'fa ' . $config->getIconFA() . ' form-control-feedback'));
            $buffer .= $html->fechaTag('span');
        }

        if (method_exists($config, 'getLabelAntes') or method_exists($config, 'getLabelDepois')) {

            if ($config->getLabelDepois()) {
                $buffer .= $html->abreTagAberta('span', ['id' => 'labelDepois_' . $config->getId(), 'class' => 'input-group-addon bg-default no-border']);
                $buffer .= $config->getLabelDepois();
                $buffer .= $html->fechaTag('span');
            }

            if ($config->getLabelAntes() or $config->getLabelDepois()) {

                $buffer .= $html->fechaTag('div');
            }
        }

        $buffer .= $html->fechaTag('div');
        $buffer .= $html->fechaTag('div');
        $buffer .= $html->fechaTag('div');

        return $buffer;
    }

}
