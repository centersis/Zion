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

namespace Pixel\Template\BarraEsquerda;

class BlocoUsuario extends \Zion\Layout\Padrao
{

    public function getBlocoUsuario()
    {

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('div', array('id' => 'menu-content-demo', 'class' => 'menu-content top'));

        $buffer .= $this->html->abreTagAberta('div');
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'text-bg'));
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'text-slim')) . 'Vinícius Pozzebon' . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->abreTagAberta('img', array('src' => SIS_URL_BASE_STATIC .'PixelAdmin/1.3.0/assets/demo/avatars/1.jpg'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'btn-group'));
        // envelope
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE . './Accounts/Message', 'class' => 'btn btn-xs btn-primary btn-outline dark'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-envelope')) . $this->html->fechaTag('i');
        $buffer .= $this->html->fechaTag('a');
        // perfil
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE . './Accounts/User', 'class' => 'btn btn-xs btn-primary btn-outline dark'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-user')) . $this->html->fechaTag('i');
        $buffer .= $this->html->fechaTag('a');
        // configuracoes
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE . './Accounts/Config', 'class' => 'btn btn-xs btn-primary btn-outline dark'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-cog')) . $this->html->fechaTag('i');
        $buffer .= $this->html->fechaTag('a');
        // sair
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE . './Accounts/Logoff', 'class' => 'btn btn-xs btn-danger btn-outline dark'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'fa fa-power-off')) . $this->html->fechaTag('i');
        $buffer .= $this->html->fechaTag('a');

        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

        // end: menu-content-demo
        $buffer .= $this->html->fechaTag('div');
        return $buffer;

    }

}