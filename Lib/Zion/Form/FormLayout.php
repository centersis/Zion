<?php

namespace Zion\Form;

class FormLayout
{

    private $nome;
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
        if(!empty($nome)){
             $this->nome = $nome;
            return $this;
        } else {
            throw new FormException("nome: Nenhum valor informado.");
        }
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
