<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Constantes.php';

use FiltraFormulario;
use CriaPDF;

require_once __DIR__ . '/formulario.php';

// Filtra e valida o tipo de arquivo
function geraNome($formulario): string
{
    return "{$formulario['os']} - {$formulario['projeto']} - {$formulario['modelo']} - {$formulario['observacao']}";
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
    if (!FiltraFormulario::validaNumeroInteiroPositivo($largura_material) || !FiltraFormulario::validaNumeroInteiroPositivo($comprimento_material)) {
        echo "Dimensões do material inválidas!";
        exit;
    }

    // Validar a quantidade
    if (!FiltraFormulario::validaNumeroInteiroPositivo($quantidade)) {
        echo "Quantidade inválida!";
        exit;
    }

    // Validar o número da ordem de serviço (OS)
    if (!FiltraFormulario::validaStringNaoVazia($numero_os)) {
        echo "Número da ordem de serviço (OS) inválido!";
        exit;
    }

    // Validar o projeto
    if (!FiltraFormulario::validaStringNaoVazia($projeto)) {
        echo "Projeto inválido!";
        exit;
    }

    // Validar o modelo
    if (!FiltraFormulario::validaStringNaoVazia($modelo)) {
        echo "Modelo inválido!";
        exit;
    }

    foreach (IMAGENS as $imagem) {
        $pdf = new CriaPDF($nome_novo, $material, $comprimento_material, $largura_material, $imagem, $cor_linha);
    }
}