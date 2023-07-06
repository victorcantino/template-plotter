<?php
namespace Victor\TemplatePlotter;

class Imagem
{
    private \GdImage|bool $imagem;
    private string $extensao;
    private string $tipo;
    private string $nome_temp;
    private array $tipos_permitidos = ['image/png', 'image/jpg', 'image/jpeg'];
    public function __construct(array $arquivo)
    {
            $this->tipo = $arquivo['type'];
            $this->extensao = explode('/', $this->tipo)[1];
            $this->nome_temp = $arquivo['tmp_name'];
    }
    public function valida(): bool
    {
        return filter_var($this->tipo, FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== false
            // && filter_var($this->nome_temp, FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== false
            // && trim($this->nome_temp) !== ''
            && in_array($this->tipo, $this->tipos_permitidos);
    }

    function abre(): self
    {
        switch ($this->tipo) {
            case 'image/png':
                $this->imagem = imagecreatefrompng($this->nome_temp);
                break;
            case 'image/jpg':
            case 'image/jpeg':
                $this->imagem = imagecreatefromjpeg($this->nome_temp);
                break;
            default:
                $this->imagem = false;
                break;
        }
        return $this;
    }

    /** 
     * se a imagem estiver na horizontal 
     * salva na vertical, girando no sentido horário
     */
    public function salva($caminho_imagem): bool
    {
        if (imagesx($this->imagem) > imagesy($this->imagem)) {
            $this->imagem = imagerotate($this->imagem, -90, 0);
        }

        if ($this->tipo === 'image/png') {
            return imagepng($this->imagem, $caminho_imagem);
        } else if ($this->tipo === 'image/jpg' || $this->tipo === 'image/jpeg') {
            return imagejpeg($this->imagem, $caminho_imagem);
        }
        return false;
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