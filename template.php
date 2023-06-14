<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/formulario.php';

define('LARGURA_PAGINA', 260);
define('IMAGENS', array(
    'frente',
    'verso',
    'tictac_frente',
    'tictac_verso'
));

// Filtra e valida o tipo de arquivo
function validaImagem($arquivo): bool
{
    return filter_var($arquivo['type'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== false && $arquivo['type'] == 'image/png';
}

// Salva um arquivo com o 
function salvaImagem($arquivo, $nome)
{
    if (!move_uploaded_file($arquivo['tmp_name'], __DIR__ . "/$nome.png")) {
        echo 'Erro ao salvar o arquivo: ' . __DIR__  . "/$nome.png";
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

// Função para validar um valor numérico inteiro positivo
function validarNumeroInteiroPositivo($valor)
{
    return filter_var($valor, FILTER_VALIDATE_INT) !== false && intval($valor) > 0;
}

// Função para validar uma string não vazia
function validarStringNaoVazia($valor)
{
    return filter_var($valor, FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== false && trim($valor) !== '';
}

function gerarNome($formulario): string
{
    return "{$formulario['os']} - {$formulario['projeto']} - {$formulario['modelo']} - {$formulario['observacao']}";
}

function criaArquivoPDF(string $nome, string $imagem, int $imagens_lado_a_lado, int $alturaMaterial, int $larguraMaterial)
{
    // Carregar a imagem PNG
    $imagePath = __DIR__ . "/$nome $imagem.png";
    if (getimagesize($imagePath) !== false) {

        // Criar nova instância do TCPDF
        $pdf = new TCPDF('P', 'mm', array(LARGURA_PAGINA, $alturaMaterial), true, 'UTF-8', false);

        // Definir informações do documento PDF
        $pdf->SetCreator('TCPDF');
        $pdf->SetAuthor('Victor Cantino');
        $pdf->SetTitle($nome);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        // permite que a imagem toque a borda inferior
        $pdf->setAutoPageBreak(false);

        // Adicionar nova página ao PDF
        $pdf->AddPage();

        // Distribuir as imagens horizontalmente
        for ($i = 0; $i < $imagens_lado_a_lado; $i++) {
            $x = $i * (LARGURA_PAGINA / $imagens_lado_a_lado);
            $y = 0;
            $pdf->Image($imagePath, $x, $y, $larguraMaterial, $alturaMaterial);
        }

        if ($imagem === 'frente' || $imagem === 'tictac_frente') {
            $pdf->setLineStyle([
                'dash' => 10, // linha tracejada
                'color' => [255, 255, 0],  // linha amarela
                'width' => 1 // espessura da linha
            ]);

            // Desenha uma linha no topo da página como marcação para montagem
            $pdf->Line(0, 25, 260, 25);
        }

        // Gerar o arquivo PDF
        $pdf->Output(__DIR__ . "/$nome $imagem.pdf", 'F');
        
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome_novo = gerarNome($_POST);
    salvaImagens($_FILES, $nome_novo);

    // Definir as dimensões do material em mm
    $larguraMaterial = $_POST['largura'];
    $alturaMaterial = $_POST['comprimento'];
    $quantidade = $_POST['quantidade'];
    $numeroOS = $_POST['os'];
    $projeto = $_POST['projeto'];
    $modelo = $_POST['modelo'];
    $observacao = $_POST['observacao'];

    // Validar as dimensões do material
    if (!validarNumeroInteiroPositivo($larguraMaterial) || !validarNumeroInteiroPositivo($alturaMaterial)) {
        echo "Dimensões do material inválidas!";
        exit;
    }

    // Validar a quantidade
    if (!validarNumeroInteiroPositivo($quantidade)) {
        echo "Quantidade inválida!";
        exit;
    }

    // Validar o número da ordem de serviço (OS)
    if (!validarNumeroInteiroPositivo($numeroOS)) {
        echo "Número da ordem de serviço (OS) inválido!";
        exit;
    }

    // Validar o projeto
    if (!validarStringNaoVazia($projeto)) {
        echo "Projeto inválido!";
        exit;
    }

    // Validar o modelo
    if (!validarStringNaoVazia($modelo)) {
        echo "Modelo inválido!";
        exit;
    }

    // Definir as dimensões do PDF em mm
    $larguraPágina = LARGURA_PAGINA;
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
            $repetir = 8;
            break;
    }

    if ($quantidade >= 1 && $quantidade < 8) {
        $repetir = $quantidade;
    }

    foreach (IMAGENS as $imagem) {
        criaArquivoPDF($nome_novo, $imagem, $repetir, $alturaMaterial, $larguraMaterial);
    }
}
