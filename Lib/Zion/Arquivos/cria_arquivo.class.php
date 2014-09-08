<?php

/**
 * Description of cria_arquivo
 * @copyright DEMP - Soluções em Tecnologia da Informação LTDA.
 * @author Yuri Gauer marques
 * @since 06/07/2010
 * @name Funções para Criação e Escrita em arquivo
 * @version 1.0
 * @package Framework
 *
 */
class CriaArquivo {
    /**
     *Atributos da Classe
     * @var $Ponteiro<Ponteiro de Arquivo>;
     * @var $ArquivoNome <String> - Armazena o nome do Arquivo;
     */
    private $Ponteiro;
    private $ArquivoNome;
    private $ArquivoCaminho;

    /**
     * @abstract Cria um objeto para lidar com um arquivo, abre o ponteiro para o arquivo
     * @param <String> $CaminhoArquivo - Caminho+nome de onde o arquivo deve ser gerado
     * @param <String> $Mode - indica o modo que o arquivo deve ser aberto por default é 'w+'
     */
    function  __construct($CaminhoArquivo, $Mode = 'w+'){
        $this->ArquivoNome = $this->getArquivoNome($CaminhoArquivo);
        $this->ArquivoCaminho = $CaminhoArquivo;
        if(!$this->Ponteiro = fopen($CaminhoArquivo, $Mode)){
            throw new Exception("Não foi possível Criar o Arquivo($this->ArquivoNome)!");
        }
        //chmod($CaminhoArquivo, 0777);
    }

    /**
     *
     * @param <String> $CaminhoArquivo - Caminho+nome de onde o arquivo deve ser gerado
     * @return <String> retorna o nome do arquivo sem o caminho
     */
    public function getArquivoNome($CaminhoArquivo){
        $Dados = explode("/", $CaminhoArquivo);
        return $Dados[count($Dados)-1];
    }


    /**
     * @abstract Fecha o ponteiro do arquivo;
     */
    public function fechar(){
        if(fclose($this->Ponteiro)){
            throw new Exception("Erro ao fechar o Arquivo($this->ArquivoNome)!");
        }
    }

    /**
     * @abstract Função para escrever uma String dentro do Arquivo;
     * @param <String> $Conteudo - String que será escrita no arquivo
     * @return <boolean> retorna Verdadeiro caso consiga escrever no arquivo;
     */
    public function escrever($Conteudo){
        if(is_writable($this->ArquivoCaminho)){
            if(!fwrite($this->Ponteiro, $Conteudo)){
                throw new Exception("Não foi Possível escrever os Dados no Arquivo($this->ArquivoNome)!");
            }else{
                return true;
            }
        }else{
            throw new Exception("Não foi possível Gravar o Arquivo($this->ArquivoNome)!");
        }
    }
}
?>
