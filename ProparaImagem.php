<?php

class PreparaImagem
{
    public function __construct(private string $nome, private string $tipo, private string $nome_temp)
    {
    }
    function validaImagem(): bool
    {
        return filter_var($arquivo['type'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== false && $arquivo['type'] == 'image/png';
    }

    // Salva um arquivo com o 
    function salvaImagem()
    {
        if (!move_uploaded_file($arquivo['tmp_name'], __DIR__ . "/$nome.png")) {
            echo 'Erro ao salvar o arquivo: ' . __DIR__ . "/$nome.png";
            exit;
        }
    }

    function salvaImagens($arquivos)
    {
        foreach (IMAGENS as $imagem) {
            if (validaImagem($arquivos[$imagem])) {
                salvaImagem($arquivos[$imagem], "$nome $imagem");
            }
        }
    }

    function verticalizar(): void
    {
        // Carregar a imagem usando a biblioteca GD
        $imagem = imagecreatefrompng($imagemPath);

        // Girar a imagem verticalmente (espelhar)
        $imagemVertical = imageflip($imagem, IMG_FLIP_VERTICAL);
    }
}