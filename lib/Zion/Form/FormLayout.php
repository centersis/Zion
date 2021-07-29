<?php

namespace Zion\Form;

class FormLayout
{

    private $nome;
    private $id; //Compatibilidade
    private $valor; //Compatibilidade
    private $valorPadrao; //Compatibilidade
    private $identifica;
    private $tipoBase;
    private $acao;
    private $conteudo;

    public function __construct($nome, $conteudo)
    {
        $this->tipoBase = 'layout';
        $this->setNome($nome);
        $this->setConteudo($conteudo);
    }

    public function setNome($nome)
    {
        if (!empty($nome)) {
            $this->nome = $nome;
            return $this;
        } else {
            throw new FormException("nome: Nenhum valor informado.");
        }
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValorPadrao($valorPadrao)
    {
        $this->valorPadrao = $valorPadrao;
        return $this;
    }

    public function getValorPadrao()
    {
        return $this->valorPadrao;
    }

    public function setIdentifica($identifica)
    {
        $this->identifica = $identifica;
        return $this;
    }

    public function getIdentifica()
    {
        return $this->identifica;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
        return $this;
    }

    public function getConteudo()
    {
        return $this->conteudo;
    }
}
