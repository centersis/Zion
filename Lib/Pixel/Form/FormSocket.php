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

namespace Pixel\Form;

class FormSocket
{
    private $configs;
    private $campos;
    private $form;
    
    public function setConfigs($configs)
    {
        if(is_array($configs)){
            $this->configs = $configs;
        } else {
            throw new \Exception("configs: O valor inforamdo deve ser um array");
        }
    }
    
    public function getConfigs(){
        return $this->configs;
    }
    
    private function setCampos($campos)
    {
        if(is_array($campos)){
            $this->campos = $campos;
        } else {
            throw new \Exception("campos: O valor inforamdo deve ser um array");
        }
    }
    
    public function getCampos(){
        return $this->campos;
    }

    private function setForm($form)
    {
        if(is_object($form)){
            $this->form = $form;
        } else {
            throw new \Exception("form: O valor inforamdo deve ser um objeto");
        }
    }
    
    public function getForm(){
        return $this->form;
    }

    /**
     * FormSocket::openWebSocket()
     * 
     * @param mixed $form
     * @param array $campos
     * @param array $configs configs['nome'      => 'nome', 
     *                               'pesquisa'  => '{"tabela"      : ["nome"],
     *                                               "campoDado"    : ["nomeCod", "nome"],
     *                                               "condicao"     : {nomeCod: $nomeCod},
     *                                               "orderna"      : "nomeCod",
     *                                               "offset"       : 0,
     *                                               "limit"        : 10,
     *                                               "metodo"       : "getNome"}',
     *                               'evento'    => '$(document).ready(function{'
     *                              ]
     * @return object Objeto do método.
     */
    public function openWebSocket($form, $campos, $configs = false)
    {
        $this->setConfigs($configs);
        $this->setCampos($campos);
        $this->setForm($form);

        return $this;
    }
    
    public function processar($conteudo)
    {
        $configs    = $this->configs;
        $campos     = $this->campos;
        $form       = $this->form;

        $wsObject = "webSocket". $configs['nome'];
        
        $html = '<div id="'. $wsObject .'" name="'. $wsObject .'">'. $conteudo .'</div>';
        $html .= '<script type="text/javascript">'. $this->getScript($wsObject) .'</script>';
        
        \array_push($campos, $form->layout('divWebSocket', $html));
        
        return $campos;
    }
    
    private function getScript($wsObject)
    {

        $configs    = $this->configs;
        $evento     = (empty($configs['evento']) ? '$(document).ready(function(){' : $configs['evento']);
        $threadId   = $this->getRandomThreadId();
        
        $callback = (empty($configs['callback']) ? '$(\'#'. $wsObject .'\').html(data.retorno[0]);' : $configs['callback']);
        
        $script = $evento;
        $script .= 'function get'. $configs['nome'] .'(retorno) { '. $callback .'}';
        $script .= 'socketAPI.conecta("'. $threadId .'", '. $configs['pesquisa'] .');';
        $script .= '});';
        $script .= 'function get'. $configs['nome'] .'(retorno) { '. $callback .'}';
       
        return preg_replace('/@/', '', $script);
    }
    
    private function getRandomThreadId()
    {
        return md5(bin2hex(openssl_random_pseudo_bytes(30)));
    }
}