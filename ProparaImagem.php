<?php

class PreparaImagem
{

    function validaImagem($arquivo): bool
    {
        return filter_var($arquivo['type'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== false && $arquivo['type'] == 'image/png';
    }

    // Salva um arquivo com o 
    function salvaImagem($arquivo, $nome)
    {
        if (!move_uploaded_file($arquivo['tmp_name'], __DIR__ . "/$nome.png")) {
            echo 'Erro ao salvar o arquivo: ' . __DIR__ . "/$nome.png";
            exit;
        }
    }

    function salvaImagens($arquivos, $nome)
    {
        foreach (IMAGENS as $imagem) {
            if (validaImagem($arquivos[$imagem])) {
                salvaImagem($arquivos[$imagem], "$nome $imagem");
            }
        }
    }

}