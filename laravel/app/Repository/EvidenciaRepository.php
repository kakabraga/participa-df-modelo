<?php

namespace App\Repository;
use App\DTO\EvidenciaDTO;
use App\Models\Evidencia;

class EvidenciaRepository
{


    public function criar(Array $dto, int $pedidoId): Evidencia
    {
        return Evidencia::create([
            'pedido_id' => $pedidoId,
            'tipo' => $dto['tipo'] ?? 'nenhum',
            'score' => $dto['score'] ?? 0.0,
            'valor' => $dto['valor'] ?? null
        ]);
    }



}
