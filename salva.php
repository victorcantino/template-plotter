<?php
// header('location: /');
require_once __DIR__ . '/vendor/autoload.php';

use Victor\TemplatePlotter\Formulario;
use Victor\TemplatePlotter\Imagem;
use Victor\TemplatePlotter\CriaPDF;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $formulario = new Formulario($_POST);

    if ($formulario->valida()) {

        $nome_arquivo = $formulario->__toString();

        foreach ($_FILES as $superficie => $arquivo) {

            $nome_final = "$nome_arquivo - $superficie";
            $imagem = new Imagem($nome_final, $arquivo);
            $caminho_imagem = $imagem->nome() . '.' . $imagem->extensao();
            if ($imagem->validaPNG() && $imagem->salva()) {

                $pdf = new CriaPDF(
                    $nome_final,
                    $formulario->material(),
                    $formulario->comprimentoMaterial(),
                    $formulario->larguraMaterial(),
                    $caminho_imagem,
                    $formulario->corLinha()
                );
            }
        }
    }
}