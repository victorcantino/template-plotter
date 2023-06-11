<?php
require_once __DIR__ . '/vendor/autoload.php';


// Definir as dimensões do PNG em mm
$larguraPNG = 20;
$alturaPNG = 370;

// Definir as dimensões do PDF em mm
$larguraPDF = 260;
$alturaPDF = $alturaPNG;
$fitas = 11; // a quantidade de fitas vai depender da largura da fita

// Criar nova instância do TCPDF
$pdf = new TCPDF('P', 'mm', array($larguraPDF, $alturaPDF), true, 'UTF-8', false);

// Definir informações do documento PDF
$pdf->SetCreator('TCPDF');
$pdf->SetAuthor('Victor Cantino');
$pdf->SetTitle('Exemplo de criação de template PDF com PHP e TCPDF');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
// permite que a imagem toque a borda inferior
$pdf->setAutoPageBreak(false);

// Adicionar nova página ao PDF
$pdf->AddPage();

// Carregar a imagem PNG
$imagePath = __DIR__ . '/imagem.png';

// Distribuir as imagens horizontalmente
for ($i = 0; $i < $fitas; $i++) {
    $x = $i * ($larguraPDF / $fitas);
    $y = 0;
    $pdf->Image($imagePath, $x, $y, 0, $alturaPNG);
}
echo $pdf->getImageRBX();
$pdf->setLineStyle([
    'dash' => 10,
    'color' => [255, 0, 0],
    'width' => 1
]);
$pdf->Line(0, 25, 260, 25);
$pdf->getImageRBX();

// Gerar o arquivo PDF
$pdf->Output(__DIR__ . '/template.pdf', 'F');
