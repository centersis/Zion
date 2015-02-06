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

namespace Pixel\Template\BarraSuperior;

class FormPesquisar extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Form
     */
    public function getFormPesquisar()
    {	

        $con = \Zion\Banco\Conexao::conectar();
        $sql = new \Sappiens\Dashboard\DashboardSql();
        
        $getDadosUsuario = $con->execLinhaArray($sql->getDadosUsuario($_SESSION['usuarioCod']));

        $formPesquisar = new \Sappiens\Dashboard\DashboardForm();
        $form = $formPesquisar->getFormPesquisarOrganograma();

        $buffer  = '';
        $buffer .= $this->html->abreTagAberta('li', array('class' => 'clearfix'));
        $buffer .= $this->html->abreTagAberta('form', array('id' => 'FormOrganograma', 'name' => 'FormOrganograma', 'class' => 'navbar-form clearfix'));
        $buffer .= $form->getFormHtml('organograma');

        if($getDadosUsuario['organogramacod'] <> $_SESSION['organogramaCod']) {

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