<?php

class FiltraFormulario
{
    // Função para validar um valor numérico inteiro positivo
    public static function validaNumeroInteiroPositivo($valor)
    {
        return filter_var($valor, FILTER_VALIDATE_INT) !== false && intval($valor) > 0;
    }

    // Função para validar uma string não vazia
    public static function validaStringNaoVazia($valor)
    {
        return filter_var($valor, FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== false && trim($valor) !== '';
    }
}
