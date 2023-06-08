<?php

require_once __DIR__ . '/vendor/autoload.php';

// Definir as dimensões do PDF em mm
$larguraPDF = 260; // 26cm em mm
$alturaPDF = 370; // 370mm

// Criar nova instância do TCPDF
$pdf = new TCPDF('P', 'mm', array($larguraPDF, $alturaPDF), true, 'UTF-8', false);

// Definir informações do documento PDF
$pdf->SetCreator('PDF_CREATOR');
$pdf->SetAuthor('Seu Nome');
$pdf->SetTitle('Exemplo de criação de template PDF com PHP e TCPDF');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

// Adicionar nova página ao PDF
$pdf->AddPage();

// Definir as dimensões da imagem
$imagemLargura = 20;
$imagemAltura = $alturaPDF;

// Carregar a imagem PNG
$imagePath = __DIR__ . '/imagem.png';

// Distribuir as imagens horizontalmente
for ($i = 0; $i < 11; $i++) {
    $x = $i * 23.63;
    $y = 0;
    $pdf->Image($imagePath, $x, $y);
}

// Gerar o arquivo PDF
$pdf->Output(__DIR__ . '/template.pdf', 'F');
