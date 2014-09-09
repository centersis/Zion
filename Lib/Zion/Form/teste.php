<?php

header("Content-Type: text/html; charset=UTF-8", true);

include_once './Form.class.php';

class teste extends Form
{

    public function escreve()
    {
        $campos[] = $this->texto()
                ->setNome('Pablo')
                ->setId('vanni')
                ->setValor('wakawaka');
        
        $campos[] = $this->botaoSimples()
                ->setNome('Enviar')
                ->setComplemento('onclick="alert(1)"');

        return $this->processarForm($campos);
    }

}

try {
    $a = new teste();
    $campos = $a->escreve();

    foreach ($campos as $html)
        echo $html;
} catch (Exception $e) {
    echo $e->getMessage();
}