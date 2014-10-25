<?php

namespace Pixel\Template\Main;

class Tab extends \Zion\Layout\Padrao
{

    /**
     * 
     * @return Tab
     */
    public function getTab($tabId = '', $arrayConfs = '', $tabArray = '')
    {	

        $buffer  = '';
        $buffer .= $this->html->entreTags('script','init.push(function () {$(\'ul.'.$tabId.'\').tabdrop();});');
        $buffer .= $this->html->abreTagAberta('div', ['class' => $arrayConfs['classCss']]);
        $buffer .= $this->html->abreTagAberta('ul', ['class' => 'nav nav-tabs '.$tabId]);

        if(is_array($tabArray)) {

            foreach($tabArray as $tabs => $values) {

                $tabId = $values['tabId'];
                $tabActive = $values['tabActive'];            
                $tabTitle = $values['tabTitle'];

                $buffer .= $this->html->abreTagAberta('li', ['class' => $tabActive]);
                    $buffer .= $this->html->abreTagAberta('a', ['href' => '#bs-tabdrop-tab'.$tabId, 'data-toggle' => 'tab']) . 
                        $tabTitle . $this->html->fechaTag('a');
                $buffer .= $this->html->fechaTag('li'); 

            }

            $buffer .= $this->html->fechaTag('ul');
            $buffer .= $this->html->abreTagAberta('div', ['class' => 'tab-content tab-content-bordered']);

            foreach($tabArray as $tabs => $values) {

                $tabId = $values['tabId'];
                $tabActive = $values['tabActive'];            
                $tabContent = $values['tabContent'];            

                $buffer .= $this->html->abreTagAberta('div', ['id' => 'bs-tabdrop-tab'.$tabId, 'class' => 'tab-pane '.$tabActive]);
                    $buffer .= $this->html->abreTagAberta('p') . $tabContent . $this->html->fechaTag('p');
                $buffer .= $this->html->fechaTag('div');

            }

        }

        $buffer .= $this->html->fechaTag('div');       
        $buffer .= $this->html->fechaTag('div');      

        return $buffer;

	}

}