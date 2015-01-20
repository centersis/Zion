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

namespace Pixel\Template;

class Rodape extends \Zion\Layout\Padrao
{

    public function getRodape($template)
    {

        $buffer = '';
        $buffer .= $template->getConteudoFooter();
        $buffer .= $this->html->abreComentario() . 'Sappiens Framework: starting scripts block' . $this->html->fechaComentario();
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/bootstrap.min.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . 'libs/jquery/plugins/jquery-maskMoney/3.0.2/jquery.maskMoney.min.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/javascripts/pixel-admin.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('type' => 'text/javascript')) . 'window.PixelAdmin.start(init);' . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . 'libs/jquery/plugins/bootstrap-tags/bootstrap-tagsinput.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_FM_BASE . 'Pixel/PixelPadrao.js')) . $this->html->fechaTag('script');
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_FM_BASE . 'Ckeditor/ckeditor.js')) . $this->html->fechaTag('script');
        $buffer .= $template->getTooltipForm();
        $buffer .= $template->getScripts();
        $buffer .= $this->html->abreComentario() . 'Sappiens Framework: ending scripts block' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Sappiens Framework: starting generic footer' . $this->html->fechaComentario();
        $buffer .= $this->rodape();
        $buffer .= $this->html->abreComentario() . 'Sappiens Framework: ending generic footer' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Sappiens Framework: good by!' . $this->html->fechaComentario();
        return $buffer;

    }

}