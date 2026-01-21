<?php
namespace App\Services;

use App\DTO\DecisaoPedidoDTO;
class ClassificadorService
{

    public function decide(array $regex, array $contexto, $pedido_id): DecisaoPedidoDTO
    {
        if (!empty($regex)) {
            return $this->processaDecisaoRegex($regex, $pedido_id);
        }

        if (!empty($contexto)) {
            return $this->processaDecisaoContexto($contexto);
        }

        return DecisaoPedidoDTO::limpo($pedido_id);
    }

    public function processaDecisaoRegex(array $regex, $pedido_id)
    {
        return DecisaoPedidoDTO::detectado(
                origem: 'regex',
                tipo_dado: $regex[0]['tipo_dado'] ?? null,
                confianca: 0.9,
                pedido_id: $pedido_id,
                evidencias: $regex
            );
    }
    public function processaDecisaoContexto(array $contexto, $pedido_id)
    {
        return DecisaoPedidoDTO::detectado(
                origem: 'regex',
                tipo_dado: $contexto[0]['tipo_dado'] ?? null,
                confianca: 0.9,
                pedido_id: $pedido_id,
                evidencias: $contexto
            );
    }
}
