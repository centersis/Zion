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

use Pixel\Form\Form;
use Pixel\Form\FormInputTexto;
use Pixel\Form\FormInputSuggest;
use Pixel\Form\FormInputData;
use Pixel\Form\FormInputHora;
use Pixel\Form\FormInputNumber;
use Pixel\Form\FormInputFloat;
use Pixel\Form\FormInputCpf;
use Pixel\Form\FormInputCnpj;
use Pixel\Form\FormInputCep;
use Pixel\Form\FormInputTelefone;
use Pixel\Form\FormInputEmail;
use Pixel\Form\FormEscolha;
use Pixel\Form\FormChosen;

class FormFiltro extends Form
{

    private $operacaoE;

    public function __construct()
    {
        parent::__construct();

        $this->operacaoE = [];
    }

    public function setOperacaoE($operacaoE)
    {
        if (\is_array($operacaoE) or \is_null($operacaoE)) {
            $this->operacaoE = $operacaoE;
        } else {
            throw new \Exception('Configuração de Filtros: $operacaoE deve ser um array ou null');
        }
    }

    public function getOperacaoE()
    {
        return $this->operacaoE;
    }

    public function texto($nome, $identifica, $aliasSql)
    {
        $obj = new FormInputTexto('texto', $nome, $identifica, false);

        $obj->setFiltroPadrao('*');
        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function suggest($nome, $identifica, $aliasSql)
    {
        $obj = new FormInputSuggest('suggest', $nome, $identifica, false);

        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function data($nome, $identifica, $aliasSql)
    {
        $obj = new FormInputData('date', $nome, $identifica, false);

        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function hora($nome, $identifica, $aliasSql)
    {
        $obj = new FormInputHora('time', $nome, $identifica, false);

        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function numero($nome, $identifica, $aliasSql)
    {
        $obj = new FormInputNumber('number', $nome, $identifica, false);

        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function float($nome, $identifica, $aliasSql)
    {
        $obj = new FormInputFloat('float', $nome, $identifica, false);

        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function cpf($nome, $identifica, $aliasSql)
    {
        $obj = new FormInputCpf('cpf', $nome, $identifica, false);

        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function cnpj($nome, $identifica, $aliasSql)
    {
        $obj = new FormInputCnpj('cnpj', $nome, $identifica, false);

        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function cep($nome, $identifica, $aliasSql)
    {
        $obj = new FormInputCep('cep', $nome, $identifica, false);

        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function telefone($nome, $identifica, $aliasSql)
    {
        $obj = new FormInputTelefone('telefone', $nome, $identifica, false);

        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function email($nome, $identifica, $aliasSql)
    {
        $obj = new FormInputEmail('email', $nome, $identifica, false);

        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function escolha($nome, $identifica, $aliasSql)
    {
        $obj = new FormEscolha('escolha', $nome, $identifica, false);

        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function chosen($nome, $identifica, $aliasSql)
    {
        $obj = new FormChosen('chosen', $nome, $identifica, false);

        $obj->setAliasSql($aliasSql);

        return $obj;
    }

    public function senha()
    {
        throw new \Exception('Não pode usar ' . __METHOD__ . ' como filtro!');
    }

    public function textArea()
    {
        throw new \Exception('Não pode usar ' . __METHOD__ . ' como filtro!');
    }

    public function editor()
    {
        throw new \Exception('Não pode usar ' . __METHOD__ . ' como filtro!');
    }

    public function upload()
    {
        throw new \Exception('Não pode usar ' . __METHOD__ . ' como filtro!');
    }

    public function botaoSubmit()
    {
        throw new \Exception('Não pode usar ' . __METHOD__ . ' como filtro!');
    }

    public function botaoSalvarPadrao()
    {
        throw new \Exception('Não pode usar ' . __METHOD__ . ' como filtro!');
    }

    public function botaoSalvarEContinuar()
    {
        throw new \Exception('Não pode usar ' . __METHOD__ . ' como filtro!');
    }

    public function botaoDescartarPadrao()
    {
        throw new \Exception('Não pode usar ' . __METHOD__ . ' como filtro!');
    }

    public function botaoSimples()
    {
        throw new \Exception('Não pode usar ' . __METHOD__ . ' como filtro!');
    }

    public function botaoReset()
    {
        throw new \Exception('Não pode usar ' . __METHOD__ . ' como filtro!');
    }

    public function masterDetail()
    {
        throw new \Exception('Não pode usar ' . __METHOD__ . ' como filtro!');
    }

}
