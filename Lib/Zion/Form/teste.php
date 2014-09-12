<?php
include_once '../ClassLoader/Loader.class.php';

new Zion\ClassLoader\Loader();

header("Content-Type: text/html; charset=UTF-8", true);

class teste extends \Zion\Form\Form
{

    public function formTeste()
    {
        $metodo = 'GET';

        $this->setMetodo($metodo);
        
        $campos[] = $this->hidden()
                ->setNome('escondido');
        
        $campos[] = $this->texto()
                ->setNome('texto')
                ->setValor($this->retornaValor($metodo, 'texto'))
                ->setId('nome'); 
        
        $campos[] = $this->suggest()
                ->setNome('suggest');
        
        $campos[] = $this->senha()                
                ->setNome('senha')
                ->setLargura('30');
        
        $campos[] = $this->data()
                ->setNome('data')
                ->setDataMaxima('2014-09-09')
                ->setDataMinima('2014-09-08');  
        
        $campos[] = $this->hora()
                ->setNome('hora')
                ->setDataMaxima('14:00');                
        
        $campos[] = $this->numero()
                ->setNome('number')
                ->setValor('numero')
                ->setValorMinimo(10)
                ->setValorMaximo(20);
        
        $campos[] = $this->float()
                ->setNome('float')
                ->setValorMinimo(10)
                ->setValorMaximo(20);
        
        $campos[] = $this->cpf()
                ->setNome('cpf');
        
        $campos[] = $this->cnpj()
                 ->setNome('CNPJ');
        
        $campos[] = $this->cep()
                 ->setNome('cep');
        
        $campos[] = $this->telefone()
            ->setNome('telefone');
        
        $campos[] = $this->email()
            ->setNome('email');
        
        $campos[] = $this->botaoSubmit()
                ->setNome('Enviar')
                ->setMetodo($metodo)
                ->setValor('Meu Botão Feliz');
        
        $campos[] = $this->botaoReset()
                ->setNome('Reset')
                ->setValor('Limpar');
        
        $campos[] = $this->botaoSimples()
                ->setNome('BotaoSimples')
                ->setValor('Sou um botão que da alert')
                ->setComplemento('onclick="alert(1)"');

        return $this->processarForm($campos);
    }

}

try {
    $a = new teste();
    $campos = $a->formTeste();
    
    echo '<form name="teste">'."\n";
    foreach ($campos->getFormHtml() as $nome=>$html)
        echo $nome.": ".$html."<br>\n";
    
    echo '<hr>';
    
    echo $campos->get('texto');
    $campos->set('texto', 'mijador');
    echo '<hr>';
    echo $campos->get('texto');
    
    echo "\n".'</form>';
} catch (Exception $e) {
    echo $e->getMessage();
}