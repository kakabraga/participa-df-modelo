<?php

namespace App\Services;

use App\Models\Pedido;
use App\Support\RegexPatterns;
use App\Services\ContextDetectorService;

class PedidoService
{
    private $contextDetectorService;

    public function __construct(ContextDetectorService $contextDetectorService)
    {
        $this->contextDetectorService = $contextDetectorService;
    }

    public function analisarTexto(string $texto, bool $isArquivo = false): Pedido
    {
        $detecoes_regex = $this->detectarRegex($texto);
        $detecoes_contexto = $this->contextDetectorService->detectarContextoPorArquivo($texto);
        if (!empty($detecoes_regex)) {
            return Pedido::criarComDeteccoes($detecoes_regex, $texto, $isArquivo);
        }
        if (!empty($detecoes_contexto)) {
            return Pedido::criarComDeteccoes($detecoes_contexto, $texto, $isArquivo);
        }
        return Pedido::criarLimpo($texto, $isArquivo);
    }
    private function detectarRegex($texto): array
    {
        $detecoes = [];

        if (preg_match(RegexPatterns::CPF, $texto)) {
            $detecoes[] = [
                'tipo' => 'CPF',
                'origem' => 'Regex'
            ];
        }

        if (preg_match(RegexPatterns::EMAIL, $texto)) {
            $detecoes[] = [
                'tipo' => 'EMAIL',
                'origem' => 'Regex',
            ];
        }

        if (preg_match(RegexPatterns::TELEFONE, $texto)) {
            $detecoes[] = [
                'tipo' => 'TELEFONE',
                'origem' => 'Regex',
            ];
        }

        return $detecoes;
    }
}
