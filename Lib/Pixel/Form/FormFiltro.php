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

class FormFiltro extends \Pixel\Form\Form
{

    public function __construct()
    {
        parent::__construct();
    }

    public function texto($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormInputTexto('texto', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function suggest($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormInputSuggest('suggest', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function data($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormInputData('date', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function hora($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormInputHora('time', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function senha()
    {
        throw new \Exception('Não pode usar '.__METHOD__.' como filtro!');
    }

    public function numero($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormInputNumber('number', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function float($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormInputFloat('float', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function cpf($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormInputCpf('cpf', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function cnpj($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormInputCnpj('cnpj', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function cep($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormInputCep('cep', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function telefone($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormInputTelefone('telefone', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function email($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormInputEmail('email', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function escolha($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormEscolha('escolha', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function chosen($nome, $identifica, $aliasSql)
    {
        $obj = new \Pixel\Form\FormChosen('chosen', $nome, $identifica, false);
        
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function textArea()
    {
        throw new \Exception('Não pode usar '.__METHOD__.' como filtro!');
    }

    public function editor()
    {
        throw new \Exception('Não pode usar '.__METHOD__.' como filtro!');
    }

    public function upload()
    {
        throw new \Exception('Não pode usar '.__METHOD__.' como filtro!');
    }

    public function botaoSubmit()
    {
        throw new \Exception('Não pode usar '.__METHOD__.' como filtro!');
    }

    public function botaoSalvarPadrao()
    {
        throw new \Exception('Não pode usar '.__METHOD__.' como filtro!');
    }

    public function botaoSalvarEContinuar()
    {
        throw new \Exception('Não pode usar '.__METHOD__.' como filtro!');
    }

    public function botaoDescartarPadrao()
    {
        throw new \Exception('Não pode usar '.__METHOD__.' como filtro!');
    }

    public function botaoSimples()
    {
        throw new \Exception('Não pode usar '.__METHOD__.' como filtro!');
    }

    public function botaoReset()
    {
        throw new \Exception('Não pode usar '.__METHOD__.' como filtro!');
    }

    public function masterDetail()
    {
        throw new \Exception('Não pode usar '.__METHOD__.' como filtro!');
    }

}
