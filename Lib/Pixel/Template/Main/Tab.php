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

namespace Pixel\Template\Main;

class Tab extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Tab
     */
    public function getTab($tabId = '', $arrayConfs = array(), $tabArray = array())
    {

        $buffer = '';
        $buffer .= $this->html->entreTags('script', 'init.push(function () {$(\'ul.' . $tabId . '\').tabdrop();});');
        $buffer .= $this->html->abreTagAberta('div', ['class' => $arrayConfs['classCss']]);
        $buffer .= $this->html->abreTagAberta('ul', ['class' => 'nav nav-tabs ' . $tabId]);

        if (is_array($tabArray)) {

            $copiaTabArray = $tabArray;

            foreach ($tabArray as $tabs => $values) {

                $tabId = $values['tabId'];
                $tabActive = $values['tabActive'];
                $tabTitle = $values['tabTitle'];

                if ($values['onClick']) {
                    $onClick = ['onClick' => $values['onClick']];
                } else {
                    $onClick = [];
                }
                
                $buffer .= $this->html->abreTagAberta('li', array_merge(['class' => $tabActive], $onClick));
                $buffer .= $this->html->abreTagAberta('a', ['href' => '#bs-tabdrop-tab' . $tabId, 'data-toggle' => 'tab']) .
                        $tabTitle . $this->html->fechaTag('a');
                $buffer .= $this->html->fechaTag('li');
            }

            $buffer .= $this->html->fechaTag('ul');
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'tab-content tab-content-bordered']);

            foreach ($copiaTabArray as $tabs => $values) {

                $tabId = $values['tabId'];
                $tabActive = $values['tabActive'];
                $tabContent = $values['tabContent'];

                $buffer .= $this->html->abreTagAberta('div', ['id' => 'bs-tabdrop-tab' . $tabId, 'class' => 'tab-pane ' . $tabActive]);
                $buffer .= $this->html->abreTagAberta('p') . $tabContent . $this->html->fechaTag('p');
                $buffer .= $this->html->fechaTag('div');
            }
        }

        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

}
