<?php

namespace Pixel\Template\BarraSuperior;

class FormPesquisar extends \Zion\Layout\Padrao
{

    public function getFormPesquisar()
    {

        $con = \Zion\Banco\Conexao::conectar();
        $sql = new \Sappiens\Dashboard\DashboardSql();

        $getDadosUsuario = $con->execLinhaArray($sql->getDadosUsuario($_SESSION['usuarioCod']));

        $formPesquisar = new \Sappiens\Dashboard\DashboardForm();
        $form = $formPesquisar->getFormPesquisarOrganograma();

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('li', array('class' => 'clearfix'));
        $buffer .= $this->html->abreTagAberta('form', array('id' => 'FormOrganograma', 'name' => 'FormOrganograma', 'class' => 'navbar-form clearfix'));
        $buffer .= $form->getFormHtml('organograma');

        if ($getDadosUsuario['organogramacod'] <> $_SESSION['organogramaCod']) {

            $buffer .= $this->html->abreTagAberta('div', array('style' => 'float:inherit; position:relative; margin-top:-37px; padding-right:15px;'));
            $buffer .= $this->html->abreTagAberta('a', array('href' => '#', 'title' => 'Redefinir organograma', 'onclick' => 'getController(\'organogramaCod\', \'organograma\', \'resetOrganogramaCod\')', 'class' => 'close')) . '×' . $this->html->fechaTag('a');
            $buffer .= $this->html->fechaTag('div');
        }

        $buffer .= $this->html->fechaTag('form');
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $form->javaScript(false, true)->getLoad(true);
        $buffer .= $formPesquisar->getJSEstatico();

        return $buffer;
    }

}
