<?php

namespace Pixel\Form;

use Zion\Form\FormHtml as FormHtmlZion;
use Zion\Form\FormInputHidden;
use Zion\Layout\JavaScript;
use Zion\Banco\Conexao;
use Pixel\Form\MasterDetail\MasterDetailHtml;
use Pixel\Arquivo\ArquivoUpload;

class FormHtml extends FormHtmlZion
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     * @param \Pixel\Form\FormInputSuggest $config
     * @return string
     */
    public function montaSuggest($config)
    {
        $this->preConfig($config);

        $nome = $config->getNome();
        $valorOriginal = '';
        $retHidden = '';
        if ($config->getHidden()) {

            $valorOriginal = $config->getValor();

            if (\is_numeric($valorOriginal)) {
                $con = Conexao::conectar($config->getIdConexao());

                if ($config->getHiddenSql()) {
                    $qb = $config->getHiddenSql();
                } else {
                    $qb = $con->qb();
                    $qb->select($config->getCampoDesc())
                        ->from($config->getTabela(), '');
                }

                $qb->where($qb->expr()->eq($config->getCampoCod(), ':campoCod'))
                    ->setParameter('campoCod', $valorOriginal, \PDO::PARAM_INT);

                $valorTexto = $con->execRLinha($qb);
                $config->setValor($valorTexto);
            }

            $config->setNome('sisL' . $nome);
            $config->setId('sisL' . $nome);

            $cofHidden = new FormInputHidden('hidden', $nome);
            $cofHidden->setValor($valorOriginal);

            $retHidden = parent::montaHiddenHtml($cofHidden);
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

        return parent::montaTextoHtml($config);
    }

    public function montaSenha($config)
    {
        $this->preConfig($config);

        return parent::montaSenhaHtml($config);
    }

    /**
     * 
     * @param \Pixel\Form\FormInputTextArea $config
     * @return string
     */
    public function montaTextArea($config)
    {
        $this->preConfig($config);

        $jsFinal = '';
        if ($config->getAcao() == 'editor') {

            $idEditor = $config->getNomeForm() . $config->getId();
            $config->setId($idEditor);

            $js = new JavaScript();

            $barra = $config->getFerramentas();

            if ($barra) {
                $barra = ',' . $barra;
            }

            $jsFinal = $js->entreJS("CKEDITOR.replace( '" . $config->getId() . "'" . $barra . " );");

            $classCss = $config->getClassCss() . ' ignore';
            $config->setClassCss($classCss);
        }

        return parent::montaTextAreaHtml($config) . $jsFinal;
    }

    /**
     * 
     * @param \Pixel\Form\FormInputData $config
     * @return string
     */
    public function montaData($config)
    {
        $this->preConfig($config);

        return parent::montaDataHtml($config);
    }

    /**
     * 
     * @param \Pixel\Form\FormInputHora $config
     * @return string
     */
    public function montaHora($config)
    {
        $this->preConfig($config);

        return parent::montaHoraHtml($config);
    }

    /**
     * 
     * @param \Pixel\Form\FormInputDataHora $config
     * @return string
     */
    public function montaDataHora($config)
    {
        $this->preConfig($config);

        return parent::montaDataHoraHtml($config);
    }

    /**
     * 
     * @param \Pixel\Form\FormInputCpf $config
     * @return string
     */
    public function montaCpf($config)
    {
        $this->preConfig($config);

        return parent::montaCpfHtml($config);
    }

    /**
     * 
     * @param \Pixel\Form\FormInputCnpj $config
     * @return string
     */
    public function montaCnpj($config)
    {
        $this->preConfig($config);

        return parent::montaCnpjHtml($config);
    }

    /**
     * 
     * @param \Pixel\Form\FormInputCep $config
     * @return string
     */
    public function montaCep($config)
    {
        $this->preConfig($config);

        return parent::montaCepHtml($config);
    }

    /**
     * 
     * @param \Pixel\Form\FormInputNumber $config
     * @return string
     */
    public function montaNumber($config)
    {
        $this->preConfig($config);

        return parent::montaNumberHtml($config);
    }

    /**
     * 
     * @param \Pixel\Form\FormInputFloat $config
     * @return string
     */
    public function montaFloat($config)
    {
        $this->preConfig($config);

        return parent::montaFloatHtml($config);
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

                    $formE = $i->{$dMetodo}($dCod, $config->getParametros());

                    $objeto = $formE->getObjetos($config->getNome());
                    $objeto->setValor($config->getValor());
                    $objeto->setContainer('sisDP' . $config->getNome());

                    $campo = $formE->getFormHtml($config->getNome());

                    return $campo;
                }
            }

            return parent::montaEscolhaHtml($config, false);
        } else {
            $retorno = '';

            $config->setClassCss('px');

            if ($expandido === true and $multiplo === true) {

                $retorno = $this->montaCheckRadioPixel('check', parent::montaEscolhaHtml($config, true), $config);
            } else if ($expandido === true and $multiplo === false) {

                $retorno = $this->montaCheckRadioPixel('radio', parent::montaEscolhaHtml($config, true), $config);
            }

            return $retorno;
        }
    }

    /**
     * 
     * @param \Pixel\Form\FormInputTelefone $config
     * @return string
     */
    public function montaTelefone($config)
    {
        $this->preConfig($config);

        return parent::montaTelefoneHtml($config);
    }

    /**
     * 
     * @param \Pixel\Form\FormInputEmail $config
     * @return string
     */
    public function montaEmail($config)
    {
        $this->preConfig($config);

        return parent::montaEmailHtml($config);
    }

    public function montaCor($config)
    {
        $this->preConfig($config);

        return parent::montaCorHtml($config);
    }

    /**
     * 
     * @param \Pixel\Form\FormMasterDetail $config
     * @param type $nomeForm
     * @return type
     */
    public function montaMasterDetail($config, $nomeForm)
    {
        return (new MasterDetailHtml())->montaMasterDetail($config, $nomeForm);
    }

    private function montaCheckRadioPixel($tipo, $arrayCampos, $config)
    {
        $type = $tipo === 'check' ? 'checkbox' : 'radio';
        $classCss = $config->getInLine() === true ? $type . '-inline' : $type;

        $retorno = '';
        if (\is_array($arrayCampos)) {
            foreach ($arrayCampos as $dadosCampo) {

                $retorno .= \sprintf('<label class="%s">%s<span class="lbl">%s</span></label>', $classCss, $dadosCampo['html'], $dadosCampo['label']);
            }
        }

        return $retorno;
    }

    /**
     * 
     * @param \Pixel\Form\FormUpload $config
     * @return string
     */
    public function montaUpload($config)
    {
        $arquivoUpload = new ArquivoUpload();

        $this->preConfig($config);

        $crop = $config->getCrop();

        $nomeTratado = \str_replace('[]', '', $config->getNome());

        $htmlAlterar = $arquivoUpload->visualizarArquivos($nomeTratado, $config->getCodigoReferencia(), $config->getModulo());

        if ($crop) {

            $js = new JavaScript();

            $jsFinal = $js->entreJS('
                $("#' . $config->getId() . '").change(function(e){$imagem=$("#sis_demo_crop_' . $config->getId() . '");$imagem.show();var a=new FileReader;a.onload=function(e){var a=new Image;a.onload=function(){var e=a.width,t=a.height;e>t?e>500&&(t*=500/e,e=500):t>500&&(e*=500/t,t=500);var r=document.createElement("canvas");r.width=e,r.height=t,r.getContext("2d").drawImage(this,0,0,e,t),this.src=r.toDataURL()},a.src=e.target.result,document.getElementById("sis_demo_crop_' . $config->getId() . '").src=e.target.result,$imagem.rcrop({minSize:[10,10],preserveAspectRatio:!1,preview:{display:!0,size:["100%",250],wrapper:""}}),$imagem.on("rcrop-changed",function(){var e=$(this).rcrop("getDataURL",250,250);$("#sis_base64_crop_' . $config->getId() . '").val(e)})},a.readAsDataURL(e.target.files[0]),$imagem.rcrop("destroy"),$("canvas").remove()});'
            );

            return '
            <div align="center">                
                ' . parent::montaUploadHtml($config) .
                '<input type="hidden" id="sis_base64_crop_' . $config->getId() . '" value="" />' .
                '<img id="sis_demo_crop_' . $config->getId() . '" height="300" src="" style="display:none" />' .
                $htmlAlterar . '                
            </div>' . $jsFinal;
        }

        $complemento = 'onchange="sisUploadMultiplo(\'' . $config->getId() . '\');"';
        $config->setComplemento($complemento);

        return \sprintf('%s<div id="sisUploadMultiploLista' . $config->getId() . '"></div>', parent::montaUploadHtml($config) . $htmlAlterar);
    }

    public function montaButton($config)
    {
        return parent::montaButtonHtml($config);
    }

    /**
     * 
     * @param \Pixel\Form\FormLayout $config
     * @return string
     */
    public function montaLayout($config)
    {
        return $config->getConteudo();
    }

    protected function preConfig($config)
    {
        $config->setClassCss('form-control');

        if (\method_exists($config, 'getToolTipMsg')) {

            if ($config->getToolTipMsg()) {
                $complemento = $config->getComplemento() . ' title="' . $config->getToolTipMsg() . '"';
                $config->setComplemento($complemento);
            }
        }
    }

}
