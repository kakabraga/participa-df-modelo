<?php

namespace App\Services;

use App\Models\Pedido;

class PedidoService
{
    public function analisarTexto(string $texto, bool $isArquivo = false): Pedido
    {
        $detecoes = $this->detectarRegex($texto);

        if (empty($detecoes)) {
            return Pedido::criarLimpo($texto, $isArquivo);
        }

        return Pedido::criarComDeteccoes($detecoes, $texto, $isArquivo);
    }
    private function detectarRegex($texto): array
    {
        $detecoes = [];

        if ($cpf = $this->detectarCpf($texto)) {
            $detecoes[] = [
                'tipo' => 'CPF',
                'quantidade' => count($cpf)
            ];
        }

        if ($email = $this->detectarEmail($texto)) {
            $detecoes[] = [
                'tipo' => 'EMAIL',
                'quantidade' => count($email)
            ];
        }

        if ($telefone = $this->detectarTelefone($texto)) {
            $detecoes[] = [
                'tipo' => 'TELEFONE',
                'quantidade' => count($telefone)
            ];
        }

        return $detecoes;
    }

    

    public function detectarCpf(string $texto): array
    {
        preg_match_all(
            '/\b\d{3}\.\d{3}\.\d{3}\-\d{2}\b|\b\d{11}\b/',
            $texto,
            $matches
        );

        return $matches[0];
    }

    private function detectarTelefone(string $texto): array
    {
        preg_match_all(
            '/\(\d{2}\)\s?\d{4,5}\-\d{4}/',
            $texto,
            $matches
        );
        return $matches[0];
    }

    private function detectarEmail(string $texto): array
    {
        preg_match_all(
            '/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i',
            $texto,
            $matches
        );

        return $matches[0];
    }
}
