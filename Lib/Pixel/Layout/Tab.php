<?php

namespace Pixel\Layout;

class Tab
{

    private $html;

    /**
     *
     * @var TabVO 
     */
    private $tabVO;

    public function __construct()
    {
        $this->html = new \Zion\Layout\Html;
    }

    /**
     * 
     * @return TabVO
     */
    public function config()
    {
        $this->tabVO = new TabVO();

        return $this->tabVO;
    }

    /**
     * @return type
     */
    public function criar()
    {

        $buffer = '';
        $buffer .= $this->html->entreTags('script', 'init.push(function () {$(\'ul.' . $this->tabVO->getId() . '\').tabdrop();});');
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
