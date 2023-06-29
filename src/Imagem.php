<?php
namespace Victor\TemplatePlotter;

class Imagem
{
    private \GdImage|bool $imagem;
    private string $extensao;
    private string $nome;
    private string $tipo;
    private string $nome_temp;
    public function __construct(string $nome, array $arquivo)
    {
        $this->nome = $nome;
        $this->tipo = $arquivo['type'];
        // o tipo do arquivo vem neste formato 'image/png'
        $this->extensao = explode('/', $this->tipo)[1];
        $this->nome_temp = $arquivo['tmp_name'];
    }
    public function validaPNG(): bool
    {
        return filter_var($this->tipo, FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== false && $this->tipo == 'image/png';
    }

    public function salva(): bool
    {
        $this->imagem = imagecreatefrompng($this->nome_temp);
        $tamanho = getimagesize($this->nome_temp);

        // se a imagem estiver na horizontal
        if ($tamanho[0] > $tamanho[1]) {
            $this->imagem = imagerotate($this->imagem, 270, 0);
        }

        $caminho_imagem = "$this->nome.$this->extensao";
        return imagepng($this->imagem, $caminho_imagem);
    }

    /**
     * Recupera o valor de nome
     */
    public function nome(): string
    {
        return $this->nome;
    }

    /**
     * Recupera o valor de tipo
     */
    public function tipo(): string
    {
        return $this->tipo;
    }

    /**
     * Recupera o valor de nome_temp
     */
    public function nomeTemp(): string
    {
        return $this->nome_temp;
    }

    /**
     * Recupera o valor de extensao
     */
    public function extensao(): string
    {
        return $this->extensao;
    }
}