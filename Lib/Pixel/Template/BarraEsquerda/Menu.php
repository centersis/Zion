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

class Menu extends \Zion\Layout\Padrao
{

    public function getMenu()
    {

        $buffer  = '';
        $buffer .= $this->html->abreTagAberta('ul', array('class' => 'navigation'));
        $buffer .= $this->html->abreTagAberta('li', array('class' => 'active'));
        $buffer .= $this->html->abreTagAberta('a', array('href' => SIS_URL_BASE . 'Dashboard/'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => 'menu-icon fa fa-dashboard')) . $this->html->fechaTag('i');
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'mm-text')) . 'Dashboard' . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');
        $buffer .= $this->geraMenu();
        $buffer .= $this->html->fechaTag('ul');

        return $buffer;

    }

    private function geraMenu()
    {

        $imenu = new \Zion\Menu\Menu();
        $menu = $imenu->geraMenu();

        $obj = json_decode($menu, true);

        $buffer = '';

        if ($obj['sucesso'] == true) {

            foreach ($obj['retorno'] as $valor) {

                if(!empty($valor['grupo'])){

                    $buffer .= $this->abreGrupoMenu();
                    $buffer .= $this->populaGrupoMenu($valor);
                    $buffer .= $this->abreConjuntoSubMenu();

                    foreach($valor['modulosGrupo'] as $modulo){

                        if(is_array($modulo['subs'])){
                            $buffer .= $this->populaSubs($modulo);
                        } else {
                            $buffer .= $this->populaSubMenu($modulo);
                        }

                    }

                }
                
                $buffer .= $this->fechaConjuntoSubMenu();
                $buffer .= $this->fechaGrupoMenu();
            }
        } else {

            $buffer = '';
        }

        return $buffer;
    }

    private function populaSubs($menu, $buffer = NULL)
    {
        $buffer .= $this->abreSubsHtml($menu, true);

        foreach($menu['subs'] as $sub) {

            if(is_array($sub['subs'])){
                $buffer = $this->populaSubs($sub, $buffer);
                continue;
            } else {
                if(empty($sub['menu'])) continue;
                $buffer .= $this->populaSubMenu($sub);
            }
        }

        $buffer .= $this->fechaSubsHtml();

        return $buffer;
    }
    
    private function abreSubsHtml($subs, $main = false)
    {
        $buffer = '';
        if($main === false){
            foreach($subs as $sub){

                if(is_array($sub['subs'])){
                    $buffer .= $this->html->abreTagAberta('li', array('class' => 'mm-dropdown'));
                    $buffer .= $this->html->abreTagAberta('a', array('href' => "#", 'tabindex' => '-1'));
                    $buffer .= $this->html->abreTagAberta('i', array('class' => '' . $sub['moduloClass'] . '')) . $this->html->fechaTag('i');
                    $buffer .= $this->html->abreTagAberta('span', array('class' => 'mm-text')) . $sub['menu'] . $this->html->fechaTag('span');
                    $buffer .= $this->html->fechaTag('a');
                    $buffer .= $this->html->abreTagAberta('ul');        
                }
            }
        } else {

            $buffer .= $this->html->abreTagAberta('li', array('class' => 'mm-dropdown'));
            $buffer .= $this->html->abreTagAberta('a', array('href' => "#", 'tabindex' => '-1'));
            $buffer .= $this->html->abreTagAberta('i', array('class' => '' . $subs['moduloClass'] . '')) . $this->html->fechaTag('i');
            $buffer .= $this->html->abreTagAberta('span', array('class' => 'mm-text')) . $subs['menu'] . $this->html->fechaTag('span');
            $buffer .= $this->html->fechaTag('a');
            $buffer .= $this->html->abreTagAberta('ul');        
        }

        return $buffer;
    }
    
    private function fechaSubsHtml()
    {
        $buffer = $this->html->fechaTag('ul');
        $buffer .= $this->html->fechaTag('li');
        return $buffer;
    }
    
    private function abreGrupoMenu()
    {
        $buffer = '';
        $buffer .= $this->html->abreTagAberta('li', array('class' => 'mm-dropdown'));
        return $buffer;
    }

    private function abreSubGrupoMenu($subGrupo)
    {
        $buffer = '';
        $buffer .= $this->html->abreTagAberta('li', array('class' => 'mm-dropdown'));
        return $buffer;
    }
    private function fechaGrupoMenu()
    {
        $buffer = '';
        $buffer .= $this->html->fechaTag('li');
        return $buffer;
    }

    private function populaGrupoMenu($valor)
    {
        $buffer = '';
        $buffer .= $this->html->abreTagAberta('a', array('href' => '#'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => '' . $valor['grupoClass'] . '')) . $this->html->fechaTag('i');
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'mm-text')) . $valor['grupo'] . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('a');
        return $buffer;
    }

    private function abreConjuntoSubMenu()
    {
        $buffer = '';
        $buffer .= $this->html->abreTagAberta('ul', array('class' => ''));
        return $buffer;
    }

    private function fechaConjuntoSubMenu()
    {
        $buffer = '';
        $buffer .= $this->html->fechaTag('ul');
        return $buffer;
    }

    private function populaSubMenu($valor)
    {
        $buffer = '';
        $buffer .= $this->html->abreTagAberta('li', array('class' => ''));
        $buffer .= $this->html->abreTagAberta('a', array('href' => $valor['menuUrl'], 'tabindex' => '-1'));
        $buffer .= $this->html->abreTagAberta('i', array('class' => '' . $valor['moduloClass'] . '')) . $this->html->fechaTag('i');
        $buffer .= $this->html->abreTagAberta('span', array('class' => 'mm-text')) . $valor['menu'] . $this->html->fechaTag('span');
        $buffer .= $this->html->fechaTag('a');
        $buffer .= $this->html->fechaTag('li');
        return $buffer;
    }

}