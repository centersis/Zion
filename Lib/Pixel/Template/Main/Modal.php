<?php
/*

    Sappiens Framework
    Copyright (C) 2014, BRA Consultoria

    Website do autor: www.braconsultoria.com.br/sappiens
    Email do autor: sappiens@braconsultoria.com.br

    Website do projeto, equipe e documentação: www.sappiens.com.br
   
    Este programa é software livre; você pode redistribuí-lo e/ou
    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
    publicada pela Free Software Foundation, versão 2.

    Este programa é distribuído na expectativa de ser útil, mas SEM
    QUALQUER GARANTIA; sem mesmo a garantia implícita de
    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
    detalhes.
 
    Você deve ter recebido uma cópia da Licença Pública Geral GNU
    junto com este programa; se não, escreva para a Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
    02111-1307, USA.

    Cópias da licença disponíveis em /Sappiens/_doc/licenca

*/

namespace Pixel\Template\Main;

class Modal extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Modal
     */
    public function getModal()
    {	

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'modal-msg', 'class' => 'modal modal-alert fade modal-danger', 'aria-hidden' => 'false', 'style' => 'display:none;'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'modal-dialog'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'modal-content'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'modal-header'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-times-circle')) . $this->html->fechaTag('i');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'modal-titulo', 'class' => 'modal-title')) . ' Servidor muito ocupado! ' . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'modal-descricao', 'class' => 'modal-body')) . ' Não foi possível processar a sua requisição neste momento. Tente novamente mais tarde... ' . $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'modal-footer'));
        $buffer .= $this->html->abreTagAberta('button', array('type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => 'modal')) . ' OK ' . $this->html->fechaTag('button');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

        return $buffer;

	}

}