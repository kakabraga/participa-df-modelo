<?php
namespace App\Services;
class ClassificadorService
{

    public function decidir(array $regex, array $contexto): array
    {
        if (!empty($regex)) {
            return $this->processaDecisaoRegex($regex);
        }

        if (!empty($contexto)) {
            return $this->processaDecisaoContexto($contexto);
        }

        return [
            'resultado' => 'Limpo',
            'origem' => 'heuristico',
            'tipo_dado' => null,
            'confianca' => 1.0
        ];
    }

    public function processaDecisaoRegex(array $regex)
    {
        return [
            'resultado' => 'Detectado',
            'origem' => 'regex',
            'tipo_dado' => $regex[0]['tipo_dado'] ?? null,
            'confianca' => 0.9
        ];
    }
    public function processaDecisaoContexto(array $contexto)
    {
        return [
            'resultado' => 'Detectado',
            'origem' => 'contexto',
            'tipo_dado' => $contexto[0]['tipo_dado'] ?? null,
            'confianca' => 0.8
        ];
    }
}
