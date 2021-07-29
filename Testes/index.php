<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';

use Centersis\Zion\Form\Form;

$caminhoArquivo = 'retorno_sicoob.ret';

echo '<pre>';

try {
    $form = new Form();

    $campos[] = $form->texto('email', 'Email');
    
    $form->processarForm($campos);
    
    $form->getFormHtml('email');
    
} catch (\Exception $e) {
    echo $e->getMessage();
    echo '<br>';
    echo $e->getTraceAsString();
}