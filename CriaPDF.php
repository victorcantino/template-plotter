<?php

class CriaPDF
{
    private TCPDF $pdf;
    private int $distancia_dobra = 25;
    private int $comprimento_pagina = 260;

    /**
     * Definição de criaArquivoPDF
     * @param string $nome nome do arquivo
     * @param string $material tipo do material aonde a imagem será aplicada
     * @param int $comprimento_material comprimento do material em milímetros
     * @param int $largura_material largura do material em milímetros
     * @param string $caminho_imagem largura do material em milímetros
     * @param array $cor_linha cor da linha da dobra ou corte array(C,M,Y,K)
     * @return void
     */
    public function __construct(
        private string $nome,
        private string $material,
        private int $comprimento_material,
        private int $largura_material,
        private string $caminho_imagem,
        private array $cor_linha
    ) {
        if (getimagesize($caminho_imagem) !== false) {

            // Criar nova instância do TCPDF
            $this->pdf = new TCPDF('P', 'mm', array($this->comprimento_pagina, $comprimento_material), true, 'UTF-8', false);

            // Definir informações do documento PDF
            $this->pdf->SetCreator('TCPDF');
            $this->pdf->SetAuthor('Victor Cantino');
            $this->pdf->SetTitle($nome);
            $this->pdf->SetPrintHeader(false);
            $this->pdf->SetPrintFooter(false);

            // permite que a imagem toque a borda inferior
            $this->pdf->setAutoPageBreak(false);

            // Adicionar nova página ao PDF
            $this->pdf->AddPage();

            $this->distribuiImagens();

            // Gerar o arquivo PDF
            $this->pdf->Output(__DIR__ . "/$nome.pdf", 'F');
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
            if ($material === 'Pulseira de acesso') {
                $this->desenhaLinhaInclinada($x);
            }
            if ($material !== 'Pulseira de acesso') {
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
            'color' => $this->cor_linha,
            'width' => 1
        ]);

        $this->pdf->Line($x, $this->distancia_dobra, $x + $this->largura_material, $this->distancia_dobra);
    }

    // Desenha uma linha no topo da página como marcação para montagem
    private function desenhaLinhaInclinada($x): void
    {
        $this->pdf->setLineStyle([
            'color' => $this->cor_linha,
            'width' => 1
        ]);

        $this->pdf->Line($x, 0, $x + $this->largura_material, $this->distancia_dobra);
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
}