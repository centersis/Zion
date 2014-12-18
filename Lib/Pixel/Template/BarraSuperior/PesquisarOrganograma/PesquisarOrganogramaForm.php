<?php

namespace Pixel\Template\BarraSuperior\PesquisarOrganograma;

class PesquisarOrganogramaForm extends \Zion\Layout\Padrao
{

	private $con;
    private $pesquisarOrganogramaSql;

    public function __construct()
    {

    	$this->con = \Zion\Banco\Conexao::conectar();
    	$this->html = new \Zion\Layout\Html();
        $this->pesquisarOrganogramaSql = new \Pixel\Template\BarraSuperior\PesquisarOrganograma\PesquisarOrganogramaSql();

    }  	

    /**
     * 
     * @return Form
     */
    public function getForm()
    {	
        return '';
    	$buffer = '';

    	if($_SESSION['usuarioCod']) {

		    $getDadosUsuario = $this->con->execLinhaArray($this->pesquisarOrganogramaSql->getDadosUsuario($_SESSION['usuarioCod']));
		    $form = $this->getFormPesquisarOrganograma();

		    $buffer  = '';
		    $buffer .= $this->html->abreTagAberta('li', array('class' => 'clearfix'));
		    $buffer .= $this->html->abreTagAberta('form', array('id' => 'FormOrganograma', 'name' => 'FormOrganograma', 'class' => 'navbar-form clearfix'));
		    $buffer .= $form->getFormHtml('organograma');

		    if($getDadosUsuario['organogramaCod'] <> $_SESSION['organogramaCod']) {

		    	$buffer .= $this->html->abreTagAberta('div', array('style' => 'float:inherit; position:relative; margin-top:-37px; padding-right:15px;'));
		    	$buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'title' => 'Redefinir organograma', 'onclick' => 'getController(\'organogramaCod\', \'organograma\', \'resetOrganogramaCod\')', 'class' => 'close')) . '×' . $this->html->fechaTag('a');
		    	$buffer .= $this->html->fechaTag('div');	 

		    }

		    $buffer .= $this->html->fechaTag('form');   	    	    
		    $buffer .= $this->html->fechaTag('li');
		    $buffer .= $form->javaScript(false, true)->getLoad(true);
		    $buffer .= $this->getJSEstatico();    

		}

	    return $buffer;	    

	}

    public function getFormPesquisarOrganograma()
    {

        $form = new \Pixel\Form\Form();

        $form->config('FormOrganograma', 'GET')
                ->setNovalidate(true);

//        $getDadosOrganograma = $this->con->execLinhaArray($this->pesquisarOrganogramaSql->getDadosOrganograma($_SESSION['organogramaCod']));
//        $organogramaNome = $getDadosOrganograma['organogramaNome'];

        $campos[] = $form->texto('organograma', 'organograma');
//        $campos[] = $form->suggest('organograma', 'organograma', false)
//                ->setTabela('organograma')
//                ->setCampoCod('organogramaCod')
//                ->setCampoDesc('organogramaNome')
//                ->setClassCss('clearfix')
//                ->setPlaceHolder($organogramaNome)
//                ->setCondicao("e INSTR(organogramaAncestral,CONCAT(:|:," . $_SESSION['organogramaCod'] . ",:|:)) > 0")
//                ->setHiddenValue('organogramaCod')
//                ->setOnSelect('getController(\'organogramaCod\', \'organograma\', \'setOrganogramaCod\')')
//                ->setLayoutPixel(false);

        return $form->processarForm($campos);
    }  	

    public function getJSEstatico()
    {

        $jsStatic = \Pixel\Form\FormJavaScript::iniciar();
        $jQuery = new \Zion\JQuery\JQuery();                
        return $jsStatic->getFunctions($jsStatic->setFunctions($this->getMeuJS()));

    }    

    private function getMeuJS()
    {

        $buffer  = '';
        $buffer .= 'function getController(a,b,c){
                        var aa = $("#"+a).val();
                        $.ajax({type: "get", url: "'.SIS_URL_BASE.'Dashboard/?acao="+c+"&a="+aa, dataType: "json", beforeSend: function() {
                            $("#"+b).html(\'<i class="fa fa-refresh fa-spin" style="margin-top:10px;"></i>\');
                        }}).done(function (ret) {
                            $("#"+b).html(ret.retorno);
                            location.reload();
                        }).fail(function () {
                            sisMsgFailPadrao();
                        });  
                    }';
        return $buffer;

    }    

}