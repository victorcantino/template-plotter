<?php
namespace Victor\TemplatePlotter;

class Formulario
{
    private string $material;
    private int $largura;
    private int $comprimento;
    private int $quantidade;
    private string $superficie;
    private string $cor_linha;
    private string $os;
    private string $projeto;
    private string $modelo;
    private string $observacao;
    private string $envio;
    public function __construct(array $post)
    {
        $this->hidrata($post);
    }

    private function hidrata(array $post): void
    {
        list(
            $this->material,
            $this->largura,
            $this->comprimento,
            $this->quantidade,
            $this->superficie,
            $this->cor_linha,
            $this->os,
            $this->projeto,
            $this->modelo,
            $this->observacao,
            $this->envio
        ) = array_values($post);
    }

    public function valida(): bool
    {
        // Valida as dimensões do material
        if (!$this->validaNumeroInteiroPositivo($this->largura) || !$this->validaNumeroInteiroPositivo($this->comprimento)) {
            echo "Dimensões do material inválidas!";
            return false;
        }

        // Valida a quantidade
        if (!$this->validaNumeroInteiroPositivo($this->quantidade)) {
            echo "Quantidade inválida!";
            return false;
        }

        // Valida o número da ordem de serviço (OS)
        if (!$this->validaStringNaoVazia($this->os)) {
            echo "Número da ordem de serviço (OS) inválido!";
            return false;
        }

        // Valida o projeto
        if (!$this->validaStringNaoVazia($this->projeto)) {
            echo "Projeto inválido!";
            return false;
        }

        // Valida o modelo
        if (!$this->validaStringNaoVazia($this->modelo)) {
            echo "Modelo inválido!";
            return false;
        }

        // Valida a observação
        if (!$this->validaStringNaoVazia($this->observacao)) {
            echo "Observação inválida!";
            return false;
        }

        // Valida o modelo
        if (!$this->validaStringNaoVazia($this->superficie)) {
            echo "Superfície inválida!";
            return false;
        }

        // Valida a cor da linha
        if (!$this->validaStringNaoVazia($this->cor_linha)) {
            echo "Cor da linha inválida!";
            return false;
        }
        return true;
    }

    // Função para validar um valor numérico inteiro positivo
    private function validaNumeroInteiroPositivo($valor)
    {
        return filter_var($valor, FILTER_VALIDATE_INT) !== false && intval($valor) > 0;
    }

    // Função para validar uma string não vazia
    private function validaStringNaoVazia($valor)
    {
        return filter_var($valor, FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== false && trim($valor) !== '';
    }

    function nomeArquivo()
    {
        $copias = round($this->quantidade / $this->quantidadeDeImagensLaloALado());
        $quantidade = $copias * $this->quantidadeDeImagensLaloALado();

        if ($quantidade < $this->quantidade) {
            $quantidade += $this->quantidadeDeImagensLaloALado();
            $copias++;
        }
        return "{$this->os} - {$this->projeto} - {$this->modelo} - {$this->observacao} - $quantidade unidades - $copias copias";
    }

    /**
     * Define a quantidade de imagens lado a lado
     * Materiais de 15mm geram de 11 imagens de 20mm lado a lado
     * Materiais de 20mm geram de 9 imagens de 25mm lado a lado
     * Materiais de 25mm geram de 8 imagens de 30mm lado a lado
     * @return int
     */
    private function quantidadeDeImagensLaloALado(): int
    {
        if ($this->quantidade < 8) {
            return $this->quantidade;
        }
        switch ($this->largura) {
            case 15:
                return 11;
            case 20:
                return 9;
            case 25:
                return 8;
        }
        return 0;
    }

    /**
     * Recupera o valor de material
     */
    public function material(): string
    {
        return $this->material;
    }

    /**
     * Recupera o valor de largura
     */
    public function larguraMaterial(): string
    {
        return $this->largura;
    }

    /**
     * Recupera o valor de comprimento
     */
    public function comprimentoMaterial(): string
    {
        return $this->comprimento;
    }

    /**
     * Recupera o valor de quantidade
     */
    public function quantidade(): string
    {
        return $this->quantidade;
    }

    /**
     * Recupera o valor de os
     */
    public function os(): string
    {
        return $this->os;
    }

    /**
     * Recupera o valor de projeto
     */
    public function projeto(): string
    {
        return $this->projeto;
    }

    /**
     * Recupera o valor de modelo
     */
    public function modelo(): string
    {
        return $this->modelo;
    }

    /**
     * Recupera o valor de observacao
     */
    public function observacao(): string
    {
        return $this->observacao;
    }

    /**
     * Recupera o valor de superficie
     */
    public function superficie(): string
    {
        return $this->superficie;
    }

    /**
     * Recupera o valor de cor_linha
     */
    public function corLinha(): string
    {
        return $this->cor_linha;
    }
}