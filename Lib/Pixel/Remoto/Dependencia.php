<?php

namespace Pixel\Remoto;

class Dependencia
{

    public function montaDependencia($metodo, $classe, $cod, $nomeCampo)
    {
        $novoNamespace = \str_replace('/', '\\', $classe);

        $instancia = '\\' . $novoNamespace;

        try {
            
            if(!\is_numeric($cod)){
               $cod = 0;
            }
            
            $i = new $instancia();

            $form = $i->{$metodo}($cod);            
            
            $objeto = $form->getObjetos($nomeCampo);
            $objeto->setLayoutPixel(false);
            
            $campoOriginal = $form->getFormHtml($nomeCampo);
            $campo = \strip_tags($campoOriginal,'<option>');           
            
            return \json_encode(array('sucesso' => 'true', 'retorno' => $campo));
            
        } catch (\Exception $e) {
            $tratar = \Zion\Tratamento\Tratamento::instancia();
            return \json_encode(array('sucesso' => 'false', 'retorno' => $tratar->texto()->trata($e->getMessage())));
        }
    }

}
