<?php
require_once __DIR__ . '/vendor/autoload.php';

define('LARGURA_PAGINA', 260);
define('IMAGENS', array(
    'frente',
    'verso',
    'tictac_frente',
    'tictac_verso'
));
define('MATERIAIS', array(
    'Chaveiro',
    'Pulseira de acesso',
    'Cordão/Tirante',
    'Cordão/Tirante com tic-tac',
));
define('SUPERFICIES', array(
    'Apenas Frente',
    'Frente e Verso iguais',
    'Frente e Verso diferentes'
));
define('LARGURA_MATERIAL', array(
    '15',
    '20',
    '25'
));
define('COMPRIMENTO_MATERIAL', array(
    '300',
    '360',
    '850',
    '950',
    '1020',
    '1200',
    '1400'
));
define('DISTANCIA_DOBRA', 25);

require_once __DIR__ . '/formulario.php';

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
function validaNumeroInteiroPositivo($valor)
{
    return filter_var($valor, FILTER_VALIDATE_INT) !== false && intval($valor) > 0;
}

// Função para validar uma string não vazia
function validaStringNaoVazia($valor)
{
    return filter_var($valor, FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== false && trim($valor) !== '';
}

function geraNome($formulario): string
{
    return "{$formulario['os']} - {$formulario['projeto']} - {$formulario['modelo']} - {$formulario['observacao']}";
}

function criaArquivoPDF(string $nome, string $imagem, int $imagens_lado_a_lado, int $comprimento_material, int $largura_material, string $material, $quantidade)
{
    // Carregar a imagem PNG
    $caminhoImagem = __DIR__ . "/$nome $imagem.png";
    if (getimagesize($caminhoImagem) !== false) {

        // Criar nova instância do TCPDF
        $pdf = new TCPDF('P', 'mm', array(LARGURA_PAGINA, $comprimento_material), true, 'UTF-8', false);

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

        distribuirImagens($pdf, $imagens_lado_a_lado, $caminhoImagem, $largura_material, $comprimento_material, $material);

        // Gerar o arquivo PDF
        $pdf->Output(__DIR__ . "/$nome $imagem.pdf", 'F');
    }
}

function distribuirImagens($pdf, $imagens_lado_a_lado, $caminhoImagem, $largura_material, $comprimento_material, $material)
{
    // Distribuir as imagens horizontalmente
    for ($i = 0; $i < $imagens_lado_a_lado; $i++) {
        $x = $i * (LARGURA_PAGINA / $imagens_lado_a_lado);
        $y = 0; // sempre no topo da página
        $pdf->Image($caminhoImagem, $x, $y, $largura_material, $comprimento_material);
        if ($material === 'Pulseira de acesso') {
            desenhaLinhaInclinada($pdf, $largura_material, $x, $y);
        }
        if ($material !== 'Pulseira de acesso') {
            desenhaLinhaHorizontal($pdf, $largura_material, $x, $y);
        }
    }
}

function desenhaLinhaHorizontal($pdf, $largura_material, $x, $y, $cor = [255, 255, 0]): void
{
    $pdf->setLineStyle([
        'dash' => 10, // linha tracejada
        'color' => $cor,  // linha amarela
        'width' => 1 // espessura da linha
    ]);

    // Desenha uma linha no topo da página como marcação para montagem
    $pdf->Line($x, DISTANCIA_DOBRA, $x + $largura_material, DISTANCIA_DOBRA);
}

function desenhaLinhaInclinada($pdf, $largura_material, $x, $y, $cor = [255, 255, 0]): void
{
    $pdf->setLineStyle([
        'color' => $cor,  // linha amarela
        'width' => 1 // espessura da linha
    ]);

    // Desenha uma linha no topo da página como marcação para montagem
    $pdf->Line($x, $y, $x + $largura_material, DISTANCIA_DOBRA);
}

function imagensPorPagina($largura_material, $quantidade): int
{
    if ($quantidade >= 1 && $quantidade < 8) {
        return $quantidade;
    }
    switch ($largura_material) {
        case 15:
            return 11;
        case 20:
            return 9;
        case 25:
            return 8;
    }
    return 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome_novo = geraNome($_POST);
    salvaImagens($_FILES, $nome_novo);

    // Definir as dimensões do material em mm
    $material = $_POST['material'];
    $largura_material = $_POST['largura'];
    $comprimento_material = $_POST['comprimento'];
    $quantidade = $_POST['quantidade'];
    $numero_os = $_POST['os'];
    $projeto = $_POST['projeto'];
    $modelo = $_POST['modelo'];
    $observacao = $_POST['observacao'];

    // Validar as dimensões do material
    if (!validaNumeroInteiroPositivo($largura_material) || !validaNumeroInteiroPositivo($comprimento_material)) {
        echo "Dimensões do material inválidas!";
        exit;
    }

    // Validar a quantidade
    if (!validaNumeroInteiroPositivo($quantidade)) {
        echo "Quantidade inválida!";
        exit;
    }

    // Validar o número da ordem de serviço (OS)
    if (!validaStringNaoVazia($numero_os)) {
        echo "Número da ordem de serviço (OS) inválido!";
        exit;
    }

    // Validar o projeto
    if (!validaStringNaoVazia($projeto)) {
        echo "Projeto inválido!";
        exit;
    }

    // Validar o modelo
    if (!validaStringNaoVazia($modelo)) {
        echo "Modelo inválido!";
        exit;
    }

    $repetir = imagensPorPagina($largura_material, $quantidade);

    foreach (IMAGENS as $imagem) {
        criaArquivoPDF($nome_novo, $imagem, $repetir, $comprimento_material, $largura_material, $material, $quantidade);
    }
}
