<?php

namespace Pixel\Layout;

class Tab
{

    private $html;
    private $tabId;
    private $emColunas;
    private $configs;

    public function __construct($tabId, $emColunas)
    {
        $this->html = new \Zion\Layout\Html;

        $this->setTabId($tabId);
        $this->setEmColunas($emColunas);

        $this->configs = [];
    }

    private function setTabId($tabId)
    {
        if (empty($tabId)) {
            throw new \Exception("Tbs: Id da Tab é Inválido!");
        }

        $this->tabId = $tabId;
    }

    private function setEmColunas($emColunas)
    {
        if (!in_array($emColunas, range(1, 12))) {
            throw new \Exception("Tbs: EmColunas deve ser um número entre 1 e 12!");
        }

        $this->emColunas = $emColunas;
    }

    /**
     * 
     * @return TabVO
     */
    public function config($id)
    {
        $tVO = new TabVO();

        $this->configs[] = $tVO;

        $tVO->setId($id);

        return $tVO;
    }

    /**
     * @return type
     */
    public function criar()
    {
        $ids = [];

        $buffer = '';
        $buffer .= $this->html->abreTagAberta('div', array('id'=>'sis'.$this->tabId.'Global','class' => 'form-group'));
        $buffer .= $this->html->abreTagAberta('div', array('class' => 'col-sm-12'));
        $buffer .= $this->html->entreTags('script', 'init.push(function () {$(\'ul.' . $this->tabId . '\').tabdrop();});');
        $buffer .= $this->html->abreTagAberta('div', ['class' => 'col-sm-' . $this->emColunas]);
        $buffer .= $this->html->abreTagAberta('ul', ['class' => 'nav nav-tabs ' . $this->tabId]);

        $numConfigs = \count($this->configs);

        for ($i = 0; $i < $numConfigs; $i++) {

            $objTabI = $this->configs[$i];

            $tabId = $objTabI->getId();
            $tabActive = $objTabI->getAtiva();
            $tabTitle = $objTabI->getTitulo();

            if (\in_array($tabId, $ids)) {
                throw new \Exception('É nescessário usar um ID diferente para cada TAB!');
            }

            $ids[] = $tabId;

            if ($objTabI->getOnClick()) {
                $onClick = ['onClick' => $objTabI->getOnClick()];
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

        for ($j = 0; $j < $numConfigs; $j++) {

            $objTabJ = $this->configs[$j];

            $tabId = $objTabJ->getId();
            $tabActive = $objTabJ->getAtiva();
            $tabTitle = $objTabJ->getTitulo();
            $conteudo = $objTabJ->getConteudo();

            $buffer .= $this->html->abreTagAberta('div', ['id' => 'bs-tabdrop-tab' . $tabId, 'class' => 'tab-pane ' . $tabActive]);
            $buffer .= $this->html->abreTagAberta('p') . $conteudo . $this->html->fechaTag('p');
            $buffer .= $this->html->fechaTag('div');
        }

        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');
        $buffer .= $this->html->fechaTag('div');

        return $buffer;
    }

}
