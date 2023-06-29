<?php
namespace Victor\TemplatePlotter;

use TCPDF;

class CriaPDF
{
    private TCPDF $pdf;
    private int $distancia_dobra = 25;
    private int $comprimento_pagina = 260;

    private string $extensao = 'pdf';

    /**
     * Definição de criaArquivoPDF
     * @param string $nome_pdf nome do arquivo
     * @param string $material tipo do material aonde a imagem será aplicada
     * @param int $comprimento_material comprimento do material em milímetros
     * @param int $largura_material largura do material em milímetros
     * @param string $caminho_imagem largura do material em milímetros
     * @param string $cor_linha cor da linha, dobra ou corte é recebida como #rrggbb
     * @return void
     */
    public function __construct(
        private string $nome_pdf,
        private string $material,
        private int $comprimento_material,
        private int $largura_material,
        private string $caminho_imagem,
        private string $cor_linha
    ) {
        if (getimagesize($caminho_imagem) !== false) {

            if ($this->material === 'Pulseira de acesso') {
                $comprimento_material += $comprimento_material;
            }

            // Criar nova instância do TCPDF
            $this->pdf = new TCPDF('P', 'mm', array($this->comprimento_pagina, $comprimento_material), true, 'UTF-8', false);

            // Definir informações do documento PDF
            $this->pdf->SetCreator('TCPDF');
            $this->pdf->SetAuthor('Victor Cantino');
            $this->pdf->SetTitle($nome_pdf);
            $this->pdf->SetPrintHeader(false);
            $this->pdf->SetPrintFooter(false);

            // permite que a imagem toque a borda inferior
            $this->pdf->setAutoPageBreak(false);

            // Adicionar nova página ao PDF
            $this->pdf->AddPage();

            $this->distribuiImagens();

            // Gerar o arquivo PDF
            $this->pdf->Output(__DIR__ . "/$nome_pdf.pdf", 'F');
        }
    }

    private function distribuiImagens()
    {
        // Distribuir as imagens horizontalmente
        $imagens_eixo_x = $this->defineImagensEixoX();

        for ($i = 0; $i < $imagens_eixo_x; $i++) {

            $x = $i * ($this->comprimento_pagina / $imagens_eixo_x);
            $y = 0; // sempre no topo da página

            $this->pdf->Image($this->caminho_imagem, $x, $y, $this->largura_material, $this->comprimento_material);

            if ($this->material === 'Pulseira de acesso') {
                $this->pdf->Image($this->caminho_imagem, $x, $this->comprimento_material, $this->largura_material, $this->comprimento_material);
                if (str_contains($this->nome_pdf, 'frente')) {
                    $this->desenhaLinhaInclinada($x);
                }
            }

            if ($this->material !== 'Pulseira de acesso') {
                $this->desenhaLinhaHorizontal($x);
            }
        }
    }

    /**
     * Descrição da desenhaLinhaHorizontal
     * Desenha uma linha no topo da página como marcação para montagem
     * @param mixed $x posição no eixo x
     * @return void
     */
    private function desenhaLinhaHorizontal($x): void
    {
        $this->pdf->setLineStyle([
            'dash' => 10,
            'color' => $this->converteHexaRGB($this->cor_linha),
            'width' => 1
        ]);

        $this->pdf->Line($x, $this->distancia_dobra, $x + $this->largura_material, $this->distancia_dobra);
    }

    // Desenha uma linha no topo da página como marcação para montagem
    private function desenhaLinhaInclinada($x): void
    {
        $this->pdf->setLineStyle([
            'color' => $this->converteHexaRGB($this->cor_linha),
            'width' => 1
        ]);

        $this->pdf->Line($x, -$this->distancia_dobra / 2, $x + $this->largura_material, $this->distancia_dobra / 2);
        $this->pdf->Line($x, $this->comprimento_material + $this->distancia_dobra / 2, $x + $this->largura_material, $this->comprimento_material - $this->distancia_dobra / 2);
        $this->pdf->Line($x, $this->comprimento_material * 2 - $this->distancia_dobra / 2, $x + $this->largura_material, $this->comprimento_material * 2 + $this->distancia_dobra / 2);
    }

    private function defineImagensEixoX(): int
    {
        switch ($this->largura_material) {
            case 15:
                return 11;
            case 20:
                return 9;
            case 25:
                return 8;
        }
        return 4;
    }

    /**
     * Converte a cor de #rrggbb para [R, G, B]
     * 
     * #: O caractere “#” indica que a string começa com um sinal de hash.
     * %02x: O caractere “%” indica que o próximo valor deve ser lido e convertido. 
     *  “02” indica que o valor deve ser lido como um número hexadecimal de dois dígitos. 
     *  “x” indica que o valor deve ser interpretado como um número hexadecimal.
     * %02x: O segundo %02x lê o segundo conjunto de dois dígitos hexadecimais.
     * %02x: O terceiro %02x lê o terceiro conjunto de dois dígitos hexadecimais.
     * sscanf returna um array com 
     */
    private function converteHexaRGB($cor_hexadecimal): array
    {
        return sscanf($cor_hexadecimal, "#%02x%02x%02x");
    }
}