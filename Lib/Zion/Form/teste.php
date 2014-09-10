<?php

header("Content-Type: text/html; charset=UTF-8", true);

include_once './Form.class.php';

class teste extends Form
{

    public function formTeste()
    {
        $metodo = 'GET';

        $this->setMetodo($metodo);
        
        $campos[] = $this->texto()
                ->setNome('nome')
                ->setValor($this->retornaValor($metodo, 'nome'))
                ->setId('nome');               
        
        $campos[] = $this->data()
                ->setNome('Data')
                ->setDataMaxima('2014-09-09')
                ->setDataMinima('2014-09-08');  
        
        $campos[] = $this->hora()
                ->setNome('Hora')
                ->setDataMaxima('14:00'); 
        
        $campos[] = $this->senha()                
                ->setNome('senha')
                ->setLargura('30');
        
        $campos[] = $this->numero()
                ->setNome('number')
                ->setValorMinimo(10)
                ->setValorMaximo(20);
        
        $campos[] = $this->botaoSubmit()
                ->setNome('Enviar')
                ->setMetodo($metodo)
                ->setValor('Meu Botão Feliz');

        return $this->processarForm($campos);
    }

}

try {
    $a = new teste();
    $campos = $a->formTeste();
    
    echo '<form name="teste">';
    foreach ($campos->getFormHtml() as $html)
        echo $html;
    
    echo '<hr>';
    
    echo $campos->get('nome');
    $campos->set('nome', 'mijador');
    echo '<hr>';
    echo $campos->get('nome');
    
    echo '</form>';
} catch (Exception $e) {
    echo $e->getMessage();
}