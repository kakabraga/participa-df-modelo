<?php

namespace App\DTO;

class DecisaoPedidoDTO
{
    public function __construct(
        public readonly string $resultado,   // Detectado | Limpo
        public readonly string $origem,      // regex | contexto | classificador | heuristico
        public readonly ?string $tipo_dado,
        public readonly float $confianca,
        public readonly array $evidencias = []
    ) {}

    public static function detectado(
        string $origem,
        ?string $tipo_dado,
        float $confianca,
        array $evidencias = []
    ): self {
        return new self(
            resultado: 'Detectado',
            origem: $origem,
            tipo_dado: $tipo_dado,
            confianca: $confianca,
            evidencias: $evidencias
        );
    }

    public static function limpo(): self
    {
        return new self(
            resultado: 'Limpo',
            origem: 'heuristico',
            tipo_dado: null,
            confianca: 1.0,
            evidencias: []
        );
    }
}
