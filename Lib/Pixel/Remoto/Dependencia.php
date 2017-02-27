<?php

namespace Pixel\Remoto;

use Zion\Tratamento\Tratamento;
use Pixel\Exception\RemotoException;

class Dependencia
{
    public function montaDependencia($metodo, $classe, $cod, $nomeCampo)
    {
        $novoNamespace = \str_replace('/', '\\', $classe);

        $instancia = '\\' . $novoNamespace;

        try {

            $i = new $instancia();

            $form = $i->{$metodo}($cod);

            $objetos = $form->getObjetos();

            /**
             * Se o nome enviado não existir no objeto ele
             * tenta encontrar o mesmo nome porem com 
             * colchetes de vetor como ultima alternativa 
             * antes da falha. Isso permite interoperabilidade
             * entre campos simples e de multipla escolha
             */
            if (!\array_key_exists($nomeCampo, $objetos)) {
                $campo = $form->getFormHtml($nomeCampo . '[]');
            } else {
                $campo = $form->getFormHtml($nomeCampo);
            }

            $campo .= $form->javaScript(false, true)->getLoad(true);

            return \json_encode(array('sucesso' => 'true', 'retorno' => $campo));
        } catch (\Exception $e) {
            $tratar = Tratamento::instancia();
            return \json_encode(array('sucesso' => 'false', 'retorno' => $tratar->texto()->trata($e->getMessage())));
        }
    }

}
