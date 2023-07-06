<?php
namespace Victor\TemplatePlotter;

use TCPDF;

class CriaPDF
{
    private TCPDF $pdf;
    private string $extensao = 'pdf';
    private int $comprimento_pagina = 260;

    public function __construct(private Formulario $formulario, private int $altura_pagina = 300)
    {
        // Criar nova instância do TCPDF
        $this->pdf = new TCPDF('P', 'mm', array($this->comprimento_pagina, $altura_pagina), true, 'UTF-8', false);
        $this->pdf->SetCreator('Hack Print');
        $this->pdf->SetAuthor('SublimaSystem');
        $this->pdf->SetPrintHeader(false);
        $this->pdf->SetPrintFooter(false);
        // permite que a imagem toque a borda inferior
        $this->pdf->setAutoPageBreak(false);
        $this->pdf->AddPage();
    }

    public function salva(string $caminho_arquivo): void
    {
        $this->pdf->SetTitle($caminho_arquivo);
        $this->pdf->Output(__DIR__ . '/../' . $caminho_arquivo, 'F');
    }

    public function distribuiImagens(string $imagem, int $largura, int $comprimento, bool $frente = true)
    {
        // Distribuir as imagens horizontalmente
        $imagens_eixo_x = $this->quantidadeDeImagensLadoALado();
        $distancia_dobra = 25;
        for ($i = 0; $i < $imagens_eixo_x; $i++) {
            $x = $i * ($this->comprimento_pagina / $imagens_eixo_x);
            $y = 0; // topo da página
            $this->pdf->Image($imagem, $x, $y, $largura, $comprimento);
            if ($frente) {
                $this->desenhaLinhaDobra($x, $distancia_dobra, $x + $largura, $distancia_dobra);
            }
        }
    }

    public function distribuiImagensPulseira(string $imagem, int $largura, int $comprimento, bool $frente = true)
    {
        // Distribuir as imagens horizontalmente
        $imagens_eixo_x = $this->quantidadeDeImagensLadoALado();
        for ($i = 0; $i < $imagens_eixo_x; $i++) {
            $x = $i * ($this->comprimento_pagina / $imagens_eixo_x);
            $y = 0; // topo da página
            $this->pdf->Image($imagem, $x, $y, $largura, $comprimento);
            $this->pdf->Image($imagem, $x, $comprimento, $largura, $comprimento);
            if ($frente) {
                $this->desenhaLinhaPulseira($x, $largura, $comprimento);
            }
        }
    }

    public function distribuiImagensTicTac(string $imagem_cordao, int $comprimento_cordao, string $imagem_tictac, int $comprimento_tictac, int $largura, bool $frente = true)
    {
        // Distribuir as imagens horizontalmente
        $imagens_eixo_x = $this->quantidadeDeImagensLadoALado();
        $distancia_dobra_tictac = 25;
        $distancia_dobra_cordao = $comprimento_tictac + 1 + 35;

        for ($i = 0; $i < $imagens_eixo_x; $i++) {
            $x = $i * ($this->comprimento_pagina / $imagens_eixo_x);
            $y = 0; // topo da página

            $this->pdf->Image($imagem_tictac, $x, $y, $largura, $comprimento_tictac);
            $this->pdf->Image($imagem_cordao, $x, $frente ? $comprimento_tictac + 1 : $comprimento_tictac, $largura, $comprimento_cordao);
            if ($frente) {
                $this->desenhaLinhaDobra($x, $distancia_dobra_tictac, $x + $largura, $distancia_dobra_tictac);
                $this->desenhaLinhaDobra($x, $distancia_dobra_cordao, $x + $largura, $distancia_dobra_cordao);
            }
        }
    }

    private function desenhaLinhaDobra($xa, $ya, $xb, $yb): void
    {
        $this->pdf->setLineStyle([
            'dash' => 10,
            'color' => $this->converteHexaRGB($this->formulario->corLinha()),
            'width' => 1
        ]);

        $this->pdf->Line($xa, $ya, $xb, $yb);
    }

    private function desenhaLinhaPulseira($x, $largura, $comprimento): void
    {
        $distancia_corte = 15;

        $this->pdf->setLineStyle([
            'color' => $this->converteHexaRGB($this->formulario->corLinha()),
            'width' => 1
        ]);

        $this->pdf->Line($x, -$distancia_corte, $x + $largura, $distancia_corte);
        $this->pdf->Line($x, $comprimento + $distancia_corte, $x + $largura, $comprimento - $distancia_corte);
        $this->pdf->Line($x, $comprimento * 2 - $distancia_corte, $x + $largura, $comprimento * 2 + $distancia_corte);
    }

    /**
     * Define a quantidade de imagens lado a lado
     * Materiais de 15mm geram de 11 imagens de 20mm lado a lado
     * Materiais de 20mm geram de 9 imagens de 25mm lado a lado
     * Materiais de 25mm geram de 8 imagens de 30mm lado a lado
     * @return int
     */
    private function quantidadeDeImagensLadoALado(): int
    {
        switch ($this->formulario->larguraMaterial()) {
            case 15:
                return 11;
            case 20:
                return 9;
            case 25:
                return 8;
        }
        return 1;
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

    function calculaQuantidadeDeCopias($quantidade_total): int
    {
        return round($quantidade_total / $this->quantidadeDeImagensLadoALado());
    }
}