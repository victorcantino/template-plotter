<?php
// header('location: /');
require_once __DIR__ . '/vendor/autoload.php';

use Victor\TemplatePlotter\Compacta;
use Victor\TemplatePlotter\CriaPDF;
use Victor\TemplatePlotter\Formulario;
use Victor\TemplatePlotter\Imagem;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $formulario = new Formulario($_POST);

    if ($formulario->valida()) {

        $nome_arquivo = $formulario->__toString();
        $zip = new Compacta($nome_arquivo);
        foreach ($_FILES as $superficie => $arquivo) {

            $nome_final = "$nome_arquivo - $superficie";
            $imagem = new Imagem($nome_final, $arquivo);
            $caminho_imagem = $imagem->nome() . '.' . $imagem->extensao();
            if ($imagem->validaPNG() && $imagem->salva()) {
                
                $zip->adicionaArquivo($caminho_imagem);
                
                $pdf = new CriaPDF(
                    $nome_final,
                    $formulario->material(),
                    $formulario->comprimentoMaterial(),
                    $formulario->larguraMaterial(),
                    $caminho_imagem,
                    $formulario->corLinha()
                );
                $zip->adicionaArquivo($nome_final . '.pdf');
            }
        }
        $zip->fechaZip();
        $zip->enviaZip();
    }
}