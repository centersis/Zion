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

namespace Pixel\Template;

class Cabecalho extends \Zion\Layout\Padrao
{

    public function getCabecalho()
    {

        $buffer = '';
        $buffer .= $this->html->abreComentario() . 'INICIO DO MANIFESTO' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Sappiens Framework.' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Copyright (C) 2014, BRA Consultoria.' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Disponibilizado publicamente como software livre em 01/09/2014. Patente requerida.' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Website do autor: www.braconsultoria.com.br/sappiens' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Email do autor: sappiens@braconsultoria.com.br' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Website do projeto, equipe e documentação: www.sappiens.com.br' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Este programa é software livre; você pode redistribuí-lo e/ou modificá-lo sob os termos da Licença Pública Geral GNU, conforme publicada pela Free Software Foundation, versão 2.' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Este programa é distribuído na expectativa de ser útil, mas SEM QUALQUER GARANTIA; sem mesmo a garantia implícita de COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais detalhes.' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'Você deve ter recebido uma cópia da Licença Pública Geral GNU junto com este programa; se não, escreva para a Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.' . $this->html->fechaComentario();
        $buffer .= $this->html->abreComentario() . 'FIM DO MANIFESTO' . $this->html->fechaComentario();

        $buffer .= $this->topo();

//        $buffer .= $this->html->abreTagAberta('link', array('href' => '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . 'fonts/font.css', 'rel' => 'stylesheet', 'type' => 'text/css'));  
//        $buffer .= $this->html->abreTagAberta('link', array('href' => '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . 'libs/font-awesome/4.2.0/css/font-awesome.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/bootstrap.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/pixel-admin.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/widgets.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/pages.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/rtl.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/themes.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . SIS_URL_BASE_TEMPLATE . 'assets/stylesheets/fine-tuning.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . 'libs/jquery/2.0.3/jquery.min.js')) . $this->html->fechaTag('script');
        $buffer .= '<script data-pace-options=\'{ "restartOnRequestAfter": true }\' src="' . SIS_URL_BASE_STATIC . 'libs/jquery/plugins/jquery-pace/0.5.6/pace.min.js"></script>' . "\n";
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . 'libs/jquery/plugins/jquery-pace/0.5.6/pace.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
        $buffer .= $this->html->abreTagAberta('link', array('href' => SIS_URL_BASE_STATIC . 'libs/jquery/plugins/bootstrap-tags/bootstrap-tagsinput.css', 'rel' => 'stylesheet', 'type' => 'text/css'));        
        $buffer .= $this->html->abreTagAberta('script', array('src' => SIS_URL_BASE_STATIC . 'libs/jquery/2.0.3/jquery.min.js')) . $this->html->fechaTag('script');        
//$buffer .= $this->conteudoHeader;
        $buffer .= $this->html->fechaTag('head');
        
        return $buffer;

    }

}