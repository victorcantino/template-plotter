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

        $nome_formatado = $formulario->nomeArquivo();
        $imagens_criadas = array();

        /** Ajusta todas as imagens na vertical e salva com nome formatado */
        foreach ($_FILES as $superficie => $arquivo) {

            $nome_imagem = $nome_formatado . ' - ' . $superficie;

            $imagem = (new Imagem($arquivo))->abre();
            if ($imagem->valida()) {
                $caminho_imagem = $nome_imagem . '.' . $imagem->extensao();
                if ($imagem->salva($caminho_imagem)) {
                    $imagens_criadas[$superficie] = $caminho_imagem;
                }
            }
        }

        switch ($formulario->material()) {
            case 'Pulseira de acesso':

                $altura_pagina = $formulario->comprimentoMaterial() * 2;
                $largura_imagem = $formulario->larguraMaterial() + 5;

                // cria página frente
                $pdf = new CriaPDF($formulario, $altura_pagina);
                $pdf->distribuiImagensPulseira($imagens_criadas['frente'], $largura_imagem, $formulario->comprimentoMaterial());
                $pdf->salva($nome_formatado . ' - frente.pdf');

                // cria a página verso
                if ($formulario->superficie() !== 'Apenas Frente') {
                    $pdf = new CriaPDF($formulario, $altura_pagina);
                    if ($formulario->superficie() === 'Frente e Verso iguais') {
                        $pdf->distribuiImagensPulseira($imagens_criadas['frente'], $largura_imagem, $formulario->comprimentoMaterial(), false);
                    } else if ($formulario->superficie() === 'Frente e Verso diferentes') {
                        $pdf->distribuiImagensPulseira($imagens_criadas['verso'], $largura_imagem, $formulario->comprimentoMaterial(), false);
                    }
                    $pdf->salva($nome_formatado . ' - verso.pdf');
                }
                break;

            case 'Cordão/Tirante com tic-tac':

                $comprimento_tictac = 170;
                $altura_pagina = $formulario->comprimentoMaterial() + $comprimento_tictac;
                $largura_imagem = $formulario->larguraMaterial() + 5;
                
                // cria a página da frente
                $pdf = new CriaPDF($formulario, $altura_pagina - 1);
                $pdf->distribuiImagensTicTac($imagens_criadas['frente'], $formulario->comprimentoMaterial(), $imagens_criadas['tictac_frente'], $comprimento_tictac, $largura_imagem);
                $pdf->salva($nome_formatado . ' - frente.pdf');
                
                // cria a página do verso
                if ($formulario->superficie() !== 'Apenas Frente') {
                    $pdf = new CriaPDF($formulario, $altura_pagina);
                    if ($formulario->superficie() === 'Frente e Verso iguais') {
                        $pdf->distribuiImagensTicTac($imagens_criadas['frente'], $formulario->comprimentoMaterial(), $imagens_criadas['tictac_frente'], $comprimento_tictac, $largura_imagem, false);
                    } else if ($formulario->superficie() === 'Frente e Verso diferentes') {
                        $pdf->distribuiImagensTicTac($imagens_criadas['verso'], $formulario->comprimentoMaterial(), $imagens_criadas['tictac_verso'], $comprimento_tictac, $largura_imagem, false);
                    }
                    $pdf->salva($nome_formatado . ' - verso.pdf');
                }
                break;

            default:

                $altura_pagina = $formulario->comprimentoMaterial();
                $largura_imagem = $formulario->larguraMaterial() + 5;
                
                // cria a página da frente
                $pdf = new CriaPDF($formulario, $altura_pagina - 1);
                $pdf->distribuiImagens($imagens_criadas['frente'], $largura_imagem, $formulario->comprimentoMaterial());
                $pdf->salva($nome_formatado . ' - frente.pdf');
                
                // cria a página do verso
                if ($formulario->superficie() !== 'Apenas Frente') {
                    $pdf = new CriaPDF($formulario, $altura_pagina);
                    if ($formulario->superficie() === 'Frente e Verso iguais') {
                        $pdf->distribuiImagens($imagens_criadas['frente'], $largura_imagem, $formulario->comprimentoMaterial(), false);
                    } else if ($formulario->superficie() === 'Frente e Verso diferentes') {
                        $pdf->distribuiImagens($imagens_criadas['verso'], $largura_imagem, $formulario->comprimentoMaterial(), false);
                    }
                    $pdf->salva($nome_formatado . ' - verso.pdf');
                }
                break;
        }

        // $nome_formatado = $formulario->__toString();
        // $zip = new Compacta($nome_formatado);

        // foreach ($_FILES as $superficie => $arquivo) {

        //     $nome_imagem = "$nome_formatado - $superficie";

        //     $imagem = new Imagem($nome_imagem, $arquivo);

        //     $caminho_imagem = $imagem->nome() . '.' . $imagem->extensao();

        //     if ($imagem->validaPNG() && $imagem->salva()) {

        //         $zip->adicionaArquivo($caminho_imagem);

        //         $pdf = new CriaPDF(
        //             $nome_imagem,
        //             $formulario->material(),
        //             $formulario->comprimentoMaterial(),
        //             $formulario->larguraMaterial(),
        //             $caminho_imagem,
        //             $formulario->corLinha()
        //         );

        //         $caminho_pdf = $nome_imagem . '.pdf';

        //         $zip->adicionaArquivo($caminho_pdf);
        //     }
        // }
        // $zip->fechaZip();
        // $zip->enviaZip();
    }
}