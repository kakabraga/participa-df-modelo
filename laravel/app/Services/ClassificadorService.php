<?php
namespace App\Services;

use App\DTO\DecisaoPedidoDTO;
class ClassificadorService
{

    public function decide(array $regex, array $contexto): DecisaoPedidoDTO
    {
        if (!empty($regex)) {
            return $this->processaDecisaoRegex($regex);
        }

        if (!empty($contexto)) {
            return $this->processaDecisaoContexto($contexto);
        }

        return DecisaoPedidoDTO::limpo();
    }

    public function processaDecisaoRegex(array $regex)
    {
        return DecisaoPedidoDTO::detectado(
                origem: 'regex',
                tipo_dado: $regex[0]['tipo_dado'] ?? null,
                confianca: 0.9,
                evidencias: $regex
            );
    }
    public function processaDecisaoContexto(array $contexto)
    {
        return DecisaoPedidoDTO::detectado(
                origem: 'regex',
                tipo_dado: $contexto[0]['tipo_dado'] ?? null,
                confianca: 0.9,
                evidencias: $contexto
            );
    }
}
