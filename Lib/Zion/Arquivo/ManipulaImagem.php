<?php

namespace Zion\Arquivo;

class ManipulaImagem extends ManipulaArquivo
{

    private $qualidade;

    public function __construct()
    {
        $this->setQualidade(75);
    }

    public function setQualidade($Valor)
    {
        $this->qualidade = $Valor;
    }

    public function getQualidade()
    {
        return $this->qualidade;
    }

    public function vericaFuncoesDeImagem()
    {
        $tI = "Função de manipulação "; //Texto Inicial
        $tF = " não existe, entre em contato com o administrador do sistema."; //Texto Final

        if (!\function_exists('getimagesize')) {
            throw new \Exception($tI . "getimagesize" . $tF);
        }

        if (!\function_exists('imagecreatefromjpeg')) {
            throw new \Exception($tI . "imagecreatefromjpeg" . $tF);
        }

        if (!\function_exists('imagecreatetruecolor')) {
            throw new \Exception($tI . "imagecreatetruecolor" . $tF);
        }

        if (!\function_exists('imagecolorallocate')) {
            throw new \Exception($tI . "imagecolorallocate" . $tF);
        }

        if (!\function_exists('imagecopyresampled')) {
            throw new \Exception($tI . "imagecopyresampled" . $tF);
        }

        if (!\function_exists('imagesy')) {
            throw new \Exception($tI . "imagesy" . $tF);
        }

        if (!\function_exists('imagesx')) {
            throw new \Exception($tI . "imagesx" . $tF);
        }

        if (!\function_exists('imagejpeg')) {
            throw new \Exception($tI . "imagejpeg" . $tF);
        }
    }

    /**
     * 	Retorna um array com a altura e a largura da imagem
     * 	@param Imagem String - Diretãrio para criaãão do arquivo
     * 	@return  Array()
     */
    public function dimensaoImagem($imagem)
    {
        $imgSize = \getimagesize($imagem);
        return ["L" => $imgSize[0], "A" => $imgSize[1]];
    }

    /**
     * 	Retorna um array com altura e largura proporcionais da imagem
     * 	@param Tamanho Inteiro - Tamanho mãximo da imagem
     * 	@param Por String - A -> Redimeciona pela altura, L pela largura
     * 	@param Originais Array() - Vetor contendo as dimenãães originais
     * 	@return Array()
     */
    public function proporcoesImagem($tamanho, $por, $originais)
    {
        if ($originais['A'] == 0 or $originais['L'] == 0) {
            throw new \Exception("Proporções do arquivo invalidas, certifique-se que o arquivo é mesmo uma imagem!");
        }

        switch ($por) {
            case "A":
                $calcula = ($tamanho * 100) / $originais['A'];
                $largura = ($originais['L'] * $calcula) / 100;
                return ["L" => $largura, "A" => $tamanho];

            case "L":
                $calcula = ($tamanho * 100) / $originais['L'];
                $altura = ($originais['A'] * $calcula) / 100;
                return ["L" => $tamanho, "A" => $altura];
        }
    }

    /**
     * 	Armazena e redimenciona uma imagem no formato JPG
     * 	@param Destino String - Local onde a foto serã tratada e guardada
     * 	@param Arquivo String - Nome do arquivo a ser criado
     * 	@param Altura Inteiro - Fixa a altura da nova imagem
     * 	@param Largura Inteiro - Fixa a largura da nova imagem
     * 	@param Manter String - Indica se o arquivo deve ser mantido ou não "ok"
     * 	@return String
     */
    public function uploadImagem($nome, $origem, $destino, $altura = 0, $largura = 0)
    {
        $nomeTemporario = $origem;
        $nomeArquivo = $nome;

        $postMax = \ini_get("post_max_size");
        $upMax = \ini_get("upload_max_filesize");

        //Menor Tamanho de Configuração
        $tMax = $postMax > $upMax ? $upMax : $postMax;

        //Verifica a integridade do arquivo
        if (!$this->integridade($nomeTemporario)) {
            throw new \Exception("O Arquivo não foi carregado, certifique-se que o tamanho do arquivo não tenha ultrapassado " . $tMax . " pois, este tamanho é o maximo permitido pelo seu servidor.");
        }

        //Verifica se a pasta permite gravação
        if (!$this->permiteEscrita(\dirname($destino))){
            throw new \Exception("A pasta onde você esta tentando gravar o arquivo não tem permissão de escrita, contate o administrador do sistema.$destino");
        }

        //Verifica se o arquivo ja existe
        if ($this->integridade($destino)) {
            //Se sim verifica se tem permissão para substitui-lo
            if (!$this->permiteEscrita($destino)){
                throw new \Exception("Este arquivo já existe e você não tem permissão para substituí-lo.");
            }
        }

        //Verifica a existencia das funãoes
        $this->vericaFuncoesDeImagem();

        //Recupera a Extensao do Arquivo
        $extensao = \strtolower($this->extenssaoArquivo($nomeArquivo));

        //Cria um vetor com as proporãães da imagem
        $tArquivo = $this->dimensaoImagem($nomeTemporario);

        //Calcula as proporções
        if (empty($altura) and empty($largura)) {

            $proporcao = ["L" => $tArquivo['L'], "A" => $tArquivo['A']];
        } elseif (!empty($altura) and ! empty($largura)) {

            $proporcao = ["L" => $largura, "A" => $altura];
        } elseif (!empty($altura)) {

            $proporcao = $this->proporcoesImagem($altura, "A", $tArquivo);
        } else {

            $proporcao = $this->proporcoesImagem($largura, "L", $tArquivo);
        }

        //Executando a criaãão da nova imagem
        if ($extensao == 'jpg' or $extensao == 'jpeg') {
            $origem = \imagecreatefromjpeg($nomeTemporario);
        } elseif ($extensao == 'gif') {
            $origem = \imagecreatefromgif($nomeTemporario);
        } elseif ($extensao == 'png') {
            $origem = \imagecreatefrompng($nomeTemporario);
        } else {
            throw new \Exception("Extensao inválida!");
        }

        $img = \imagecreatetruecolor($proporcao['L'], $proporcao['A']);

        if (\imagecopyresampled($img, $origem, 0, 0, 0, 0, $proporcao['L'], $proporcao['A'], \imagesx($origem), \imagesy($origem))) {
            if ($extensao == 'jpg' or $extensao == 'jpeg') {
                if (!(\imagejpeg($img, $destino, $this->qualidade))) {
                    throw new \Exception("Não foi possivel criar o arquivo " . \basename($destino));
                }
            } elseif ($extensao == 'gif') {
                if (!(\imagegif($img, $destino))) {
                    throw new \Exception("Não foi possivel criar o arquivo " . \basename($destino));
                }
            } elseif ($extensao == 'png') {
                if (!(\imagepng($img, $destino))) {
                    throw new \Exception("Não foi possivel criar o arquivo " . \basename($destino));
                }
            }
        } else {
            throw new \Exception("Não foi possivel gerar o arquivo");
        }
    }
}
