<?php

include_once($_SESSION['FMBase'] . 'arquivo.class.php');

class ManipulaImagem extends Arquivo
{
    /*
     * Atributos da Classe
     */

    private $Qualidade;

    /*
     * Construtor
     */

    public function __construct()
    {
        $this->setQualidade(75);
    }

    /**
     * 	Seta a Qualidades da imagem
     */
    public function setQualidade($Valor)
    {
        $this->Qualidade = $Valor;
    }

    /**
     * 	Recupera a Qualidades da imagem
     */
    public function getQualidade()
    {
        return $this->Qualidade;
    }

    /**
     * 	Retorna verdadeiro caso a funãão existam e falso caso não
     * 	@return Boolean
     */
    public function vericaFuncoesDeImagem()
    {
        $TI = "Função de manipulação "; //Texto Inicial
        $TF = " não existe, entre em contato com o administrador do sistema."; //Texto Final

        if (!function_exists('getimagesize'))
            throw new Exception($TI . "getimagesize" . $TF);
        if (!function_exists('imagecreatefromjpeg'))
            throw new Exception($TI . "imagecreatefromjpeg" . $TF);
        if (!function_exists('imagecreatetruecolor'))
            throw new Exception($TI . "imagecreatetruecolor" . $TF);
        if (!function_exists('imagecolorallocate'))
            throw new Exception($TI . "imagecolorallocate" . $TF);
        if (!function_exists('imagecopyresampled'))
            throw new Exception($TI . "imagecopyresampled" . $TF);
        if (!function_exists('imagesy'))
            throw new Exception($TI . "imagesy" . $TF);
        if (!function_exists('imagesx'))
            throw new Exception($TI . "imagesx" . $TF);
        if (!function_exists('imagejpeg'))
            throw new Exception($TI . "imagejpeg" . $TF);
    }

    /**
     * 	Retorna um array com a altura e a largura da imagem
     * 	@param Imagem String - Diretãrio para criaãão do arquivo
     * 	@return  Array()
     */
    public function dimensaoImagem($Imagem)
    {
        $ImgSize = getimagesize($Imagem);
        return array("L" => $ImgSize[0], "A" => $ImgSize[1]);
    }

    /**
     * 	Retorna um array com altura e largura proporcionais da imagem
     * 	@param Tamanho Inteiro - Tamanho mãximo da imagem
     * 	@param Por String - A -> Redimeciona pela altura, L pela largura
     * 	@param Originais Array() - Vetor contendo as dimenãães originais
     * 	@return Array()
     */
    public function proporcoesImagem($Tamanho, $Por, $Originais)
    {
        if ($Originais['A'] == 0 or $Originais['L'] == 0)
            throw new Exception("Proporções do arquivo invalidas, certifique-se que o arquivo é mesmo uma imagem!");

        switch ($Por)
        {
            case "A":
                $Calcula = ($Tamanho * 100) / $Originais['A'];
                $Largura = ($Originais['L'] * $Calcula) / 100;
                return array("L" => $Largura, "A" => $Tamanho);
                break;

            case "L":
                $Calcula = ($Tamanho * 100) / $Originais['L'];
                $Altura = ($Originais['A'] * $Calcula) / 100;
                return array("L" => $Tamanho, "A" => $Altura);
                break;
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
    public function trataImagem($Arquivo, $Destino, $Altura, $Largura, $Manter = '', $Posicao = false)
    {
        //Arquivo Atual deve ser mantido
        if ($Manter == "Ok")
            return;

        //Dados do File
        $NomeTemporario = ($Posicao === false) ? $_FILES[$Arquivo]['tmp_name'] : $_FILES[$Arquivo]['tmp_name'][$Posicao];
        $NomeArquivo = ($Posicao === false) ? $_FILES[$Arquivo]['name'] : $_FILES[$Arquivo]['name'][$Posicao];

        $PostMax = ini_get("post_max_size");
        $UpMax = ini_get("upload_max_filesize");

        //Menor Tamanho de Configuração
        $TMax = $PostMax > $UpMax ? $UpMax : $PostMax;

        //Verifica a integridade do arquivo
        if (!$this->integridade($NomeTemporario))
            throw new Exception("O Arquivo não foi carregado, certifique-se que o tamanho do arquivo não tenha ultrapassado " . $TMax . " pois, este tamanho é o maximo permitido pelo seu servidor.");

        //Verifica se a pasta permite gravação
        if (!is_writable(dirname($Destino)))
            throw new Exception("A pasta onde você esta tentando gravar o arquivo não tem permissão de escrita, contate o administrador do sistema.$Destino");

        //Verifica se o arquivo ja existe
        if ($this->integridade($Destino))
        {
            //Se sim verifica se tem permissão para substitui-lo
            if (!is_writable($Destino))
                throw new Exception("Este arquivo já existe e você não tem permissão para substituí-lo.");
        }

        //Verifica a existencia das funãoes
        $this->vericaFuncoesDeImagem();

        //Recupera a Extensao do Arquivo
        $Extensao = strtolower($this->extenssaoArquivo($NomeArquivo));

        //Cria um vetor com as proporãães da imagem
        $TArquivo = $this->dimensaoImagem($NomeTemporario);

        //Calcula as proporções
        if (empty($Altura) and empty($Largura))
            $Proporcao = array("L" => $TArquivo['L'], "A" => $TArquivo['A']);
        elseif (!empty($Altura) and !empty($Largura))
            $Proporcao = array("L" => $Largura, "A" => $Altura);
        elseif (!empty($Altura))
            $Proporcao = $this->proporcoesImagem($Altura, "A", $TArquivo);
        else
            $Proporcao = $this->proporcoesImagem($Largura, "L", $TArquivo);

        //Executando a criaãão da nova imagem
        if ($Extensao == 'jpg' or $Extensao == 'jpeg')
        {
            $Origem = @imagecreatefromjpeg($NomeTemporario);
        }
        elseif ($Extensao == 'gif')
        {
            $Origem = @imagecreatefromgif($NomeTemporario);
        }
        elseif ($Extensao == 'png')
        {
            $Origem = @imagecreatefrompng($NomeTemporario);
        }
        else
        {
            throw new Exception("Extensao inválida!");
        }

        $Img = @imagecreatetruecolor($Proporcao['L'], $Proporcao['A']);

        if (@imagecopyresampled($Img, $Origem, 0, 0, 0, 0, $Proporcao['L'], $Proporcao['A'], @imagesx($Origem), @imagesy($Origem)))
        {
            if ($Extensao == 'jpg' or $Extensao == 'jpeg')
            {
                if (!(@imagejpeg($Img, $Destino, $this->Qualidade)))
                {
                    throw new Exception("Não foi possivel criar o arquivo " . basename($Destino));
                }
            }
            elseif ($Extensao == 'gif')
            {
                if (!(@imagegif($Img, $Destino)))
                {
                    throw new Exception("Não foi possivel criar o arquivo " . basename($Destino));
                }
            }
            elseif ($Extensao == 'png')
            {
                if (!(@imagepng($Img, $Destino)))
                {
                    throw new Exception("Não foi possivel criar o arquivo " . basename($Destino));
                }
            }
        }
        else
        {
            throw new Exception("Não foi possivel gerar o arquivo");
        }
    }
    
    public function trataImagemDeOrigem($Arquivo, $Destino, $Altura, $Largura)
    {
        //Arquivo Atual deve ser mantido
        if ($Manter == "Ok")
            return;

        //Dados do File
        $NomeTemporario = $Arquivo;
        $NomeArquivo = $Arquivo;

        $PostMax = ini_get("post_max_size");
        $UpMax = ini_get("upload_max_filesize");

        //Menor Tamanho de Configuração
        $TMax = $PostMax > $UpMax ? $UpMax : $PostMax;

        //Verifica a integridade do arquivo
        if (!$this->integridade($NomeTemporario))
            throw new Exception("O Arquivo não foi carregado, certifique-se que o tamanho do arquivo não tenha ultrapassado " . $TMax . " pois, este tamanho é o maximo permitido pelo seu servidor.");

        //Verifica se a pasta permite gravação
        if (!is_writable(dirname($Destino)))
            throw new Exception("A pasta onde você esta tentando gravar o arquivo não tem permissão de escrita, contate o administrador do sistema.$Destino");

        //Verifica se o arquivo ja existe
        if ($this->integridade($Destino))
        {
            //Se sim verifica se tem permissão para substitui-lo
            if (!is_writable($Destino))
                throw new Exception("Este arquivo já existe e você não tem permissão para substituí-lo.");
        }

        //Verifica a existencia das funãoes
        $this->vericaFuncoesDeImagem();

        //Recupera a Extensao do Arquivo
        $Extensao = strtolower($this->extenssaoArquivo($NomeArquivo));

        //Cria um vetor com as proporãães da imagem
        $TArquivo = $this->dimensaoImagem($NomeTemporario);

        //Calcula as proporções
        if (empty($Altura) and empty($Largura))
            $Proporcao = array("L" => $TArquivo['L'], "A" => $TArquivo['A']);
        elseif (!empty($Altura) and !empty($Largura))
            $Proporcao = array("L" => $Largura, "A" => $Altura);
        elseif (!empty($Altura))
            $Proporcao = $this->proporcoesImagem($Altura, "A", $TArquivo);
        else
            $Proporcao = $this->proporcoesImagem($Largura, "L", $TArquivo);

        //Executando a criaãão da nova imagem
        if ($Extensao == 'jpg' or $Extensao == 'jpeg')
        {
            $Origem = @imagecreatefromjpeg($NomeTemporario);
        }
        elseif ($Extensao == 'gif')
        {
            $Origem = @imagecreatefromgif($NomeTemporario);
        }
        elseif ($Extensao == 'png')
        {
            $Origem = @imagecreatefrompng($NomeTemporario);
        }
        else
        {
            throw new Exception("Extensao inválida!");
        }

        $Img = @imagecreatetruecolor($Proporcao['L'], $Proporcao['A']);

        if (@imagecopyresampled($Img, $Origem, 0, 0, 0, 0, $Proporcao['L'], $Proporcao['A'], @imagesx($Origem), @imagesy($Origem)))
        {
            if ($Extensao == 'jpg' or $Extensao == 'jpeg')
            {
                if (!(@imagejpeg($Img, $Destino, $this->Qualidade)))
                {
                    throw new Exception("Não foi possivel criar o arquivo " . basename($Destino));
                }
            }
            elseif ($Extensao == 'gif')
            {
                if (!(@imagegif($Img, $Destino)))
                {
                    throw new Exception("Não foi possivel criar o arquivo " . basename($Destino));
                }
            }
            elseif ($Extensao == 'png')
            {
                if (!(@imagepng($Img, $Destino)))
                {
                    throw new Exception("Não foi possivel criar o arquivo " . basename($Destino));
                }
            }
        }
        else
        {
            throw new Exception("Não foi possivel gerar o arquivo");
        }
    }

}

?>
