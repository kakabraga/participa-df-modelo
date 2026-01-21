<?php

namespace App\DTO;

class DecisaoPedidoDTO
{
    /**
     * @param EvidenciaDTO[] $evidencias
     */
    public function __construct(
        public readonly string $resultado,   // Detectado | Limpo
        public readonly string $origem,      // image_pipeline | regex | ml | heuristico
        public readonly ?string $tipo_dado,
        public readonly float $confianca,
        public readonly int $pedido_id,
        public readonly array $evidencias = []
    ) {
    }

    public static function detectado(
        string $origem,
        ?string $tipo_dado,
        float $confianca,
        int $pedido_id,
        array $evidencias = []
    ): self {
        return new self(
            resultado: 'Detectado',
            origem: $origem,
            tipo_dado: $tipo_dado,
            confianca: $confianca,
            pedido_id: $pedido_id,
            evidencias: $evidencias
        );
    }

    public static function limpo(
        int $pedido_id,
        string $origem = 'heuristico'
    ): self {
        return new self(
            resultado: 'Limpo',
            origem: $origem,
            tipo_dado: null,
            confianca: 1.0,
            pedido_id: $pedido_id,
            evidencias: []
        );
    }

    public static function fromPythonResult(array $data): self
    {
        return new self(
            resultado: $data['resultado'],
            origem: $data['origem'],
            tipo_dado: $data['tipo_dado'] ?? null,
            confianca: (float) $data['confianca'],
            pedido_id: (int) $data['pedido_id'],
            evidencias: array_map(
                fn ($e) => new EvidenciaDTO(
                    tipo: $e['tipo'],
                    score: $e['score'],
                    valor: $e['valor'] ?? null
                ),
                $data['evidencias'] ?? []
            )
        );
    }
}
