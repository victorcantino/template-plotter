<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/formulario.php';

// Filtra e valida o tipo de arquivo
function validaArquivo($arquivo)
{
    $tipo_arquivo = $arquivo['type'];
    if ($tipo_arquivo == 'PNG') {
    }
    echo $tipo_arquivo;
}

function salvaArquivo($nome_temporario, $nome_arquivo)
{
    move_uploaded_file($nome_temporario, __DIR__ . "/{$nome_arquivo}");
}

function gerarNome()
{
}

function hidratarLista()
{
}

function definirRepeticoes()
{
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $imagemFrente = $_FILES['frente'];
    validaArquivo($imagemFrente);
    $imagemVerso = $_FILES['verso'];
    validaArquivo(($imagemVerso));

    // Definir as dimensões do material em mm
    $larguraMaterial = $_POST['largura'];
    $alturaMaterial = $_POST['comprimento'];
    $quantidade = $_POST['quantidade'];

    // Definir dados do projeto
    $numeroOS = $_POST['os'];
    $projeto = $_POST['projeto'];
    $modelo = $_POST['modelo'];
    $observacao = $_POST['observacao'];
    $nomeArquivo = "$numeroOS - $projeto - $modelo - $observacao - $copias cópias";

    // Definir as dimensões do PDF em mm
    $larguraPágina = 260;
    $alturaPagina = $alturaMaterial;
    $repetir = 1;

    switch ($larguraMaterial) {
        case 15:
            $repetir = 11;
            break;
        case 20:
            $repetir = 9;
            break;
        case 25:
            $repetir = 9;
            break;
    }

    if ($quantidade >= 1 && $quantidade < 8) {
        $repetir = $quantidade;
    }

    // Criar nova instância do TCPDF
    $pdf = new TCPDF('P', 'mm', array($larguraPágina, $alturaPagina), true, 'UTF-8', false);

    // Definir informações do documento PDF
    $pdf->SetCreator('TCPDF');
    $pdf->SetAuthor('Victor Cantino');
    $pdf->SetTitle($nomeArquivo);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);

    // permite que a imagem toque a borda inferior
    $pdf->setAutoPageBreak(false);

    // Adicionar nova página ao PDF
    $pdf->AddPage();

    // Carregar a imagem PNG
    // $imagePath = __DIR__ . '/imagem.png';

    // Distribuir as imagens horizontalmente
    for ($i = 0; $i < $repetir; $i++) {
        $x = $i * ($larguraPágina / $repetir);
        $y = 0; //
        $pdf->Image($imagePath, $x, $y, $larguraMaterial, $alturaMaterial);
    }

    $pdf->setLineStyle([
        'dash' => 10, // linha tracejada
        'color' => [255, 255, 0],  // linha amarela
        'width' => 1 // espessura da linha
    ]);

    // Desenha uma linha no topo da página como marcação para montagem
    $pdf->Line(0, 25, 260, 25);

    // Gerar o arquivo PDF
    $pdf->Output(__DIR__ . '/template.pdf', 'F');
}
