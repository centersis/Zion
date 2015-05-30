<?php

/**
 *
 *    Sappiens Framework
 *    Copyright (C) 2014, BRA Consultoria
 *
 *    Website do autor: www.braconsultoria.com.br/sappiens
 *    Email do autor: sappiens@braconsultoria.com.br
 *
 *    Website do projeto, equipe e documentação: www.sappiens.com.br
 *   
 *    Este programa é software livre; você pode redistribuí-lo e/ou
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *    publicada pela Free Software Foundation, versão 2.
 *
 *    Este programa é distribuído na expectativa de ser útil, mas SEM
 *    QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *    detalhes.
 * 
 *    Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *    junto com este programa; se não, escreva para a Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *    02111-1307, USA.
 *
 *    Cópias da licença disponíveis em /Sappiens/_doc/licenca
 *
 */

namespace Pixel\Remoto;

use Zion\Tratamento\Tratamento;

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
