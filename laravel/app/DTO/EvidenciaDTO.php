<?php

namespace App\DTO;

class EvidenciaDTO
{
    public function __construct(
        public readonly string $pedido_id,
        public readonly string $tipo,
        public readonly float $score,
        public readonly ?string $valor = null
    ) {}
}
