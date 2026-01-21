<?php

namespace App\DTO;
use App\Models\Pedido;
class PersistenciaDecisaoDTO
{
    public function __construct(
        public Pedido $pedido,
        public array $evidencias
    ) {}
}
