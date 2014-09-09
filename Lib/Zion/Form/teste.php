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
        
        $campos[] = $this->data()
                ->setValorMaximo('2014-09-09')
                ->setNome('aniversario');
        
        $campos[] = $this->botaoSubmit()
                ->setNome('Enviar')
                ->setValor('Meu Botão Feliz');

        return $this->processarForm($campos);
    }

}

try {
    $a = new teste();
    $campos = $a->escreve();
    
    echo '<form name="teste">';
    foreach ($campos as $html)
        echo $html;
    echo '</form>';
} catch (Exception $e) {
    echo $e->getMessage();
}