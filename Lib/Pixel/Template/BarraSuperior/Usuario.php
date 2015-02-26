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

class Usuario extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Notificacoes
     */
    public function getUsuario()
    {	

        $buffer  = '';
        $buffer .= $this->html->abreTagAberta('li', ['class' => 'dropdown']);        
            $buffer .= $this->html->abreTagAberta('a', ['href' => '#user', 'class' => 'dropdown-toggle user-menu', 'data-toggle' => 'dropdown']);
                $buffer .= $this->html->abreTagAberta('img', ['src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/demo/avatars/1.jpg']);
                $buffer .= $this->html->abreTagAberta('span') . $_SESSION['usuarioLogin'] . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
            $buffer .= $this->html->abreTagAberta('ul', ['class' => 'dropdown-menu']);  

                $buffer .= $this->html->abreTagAberta('li');  
                    $buffer .= $this->html->abreTagAberta('a', ['href' => '#']);
                    $buffer .= $this->html->abreTagAberta('span', ['class' => 'label label-warning pull-right']) . 'Label' . $this->html->fechaTag('span') . ' Perfil ';
                    $buffer .= $this->html->fechaTag('a');
                $buffer .= $this->html->fechaTag('li');

                $buffer .= $this->html->abreTagAberta('li');  
                    $buffer .= $this->html->abreTagAberta('a', ['href' => '#']);
                    $buffer .= $this->html->abreTagAberta('i', ['class' => 'dropdown-icon fa fa-cog recD10px']) . $this->html->fechaTag('i') . ' Configurações ';
                    $buffer .= $this->html->fechaTag('a');
                $buffer .= $this->html->fechaTag('li');

                $buffer .= $this->html->abreTagAberta('li', ['class' => 'divider']) . $this->html->fechaTag('li');

                $buffer .= $this->html->abreTagAberta('li');  
                    $buffer .= $this->html->abreTagAberta('a', ['href' => SIS_URL_BASE . 'Accounts/Logoff']);
                    $buffer .= $this->html->abreTagAberta('i', ['class' => 'dropdown-icon fa fa-power-off recD10px']) . $this->html->fechaTag('i') . 'Desconectar ';
                    $buffer .= $this->html->fechaTag('a');
                $buffer .= $this->html->fechaTag('li');

            $buffer .= $this->html->fechaTag('ul');
        $buffer .= $this->html->fechaTag('li');
        return $buffer;

	}

}