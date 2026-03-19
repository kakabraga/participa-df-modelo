<?php
namespace App\Services;
use App\Support\RegexPatterns;
use App\DTO\EvidenciaDTO;

class RegexService
{
    public function detectarRegex(string $texto, int $id_pedido): array
    {
        $detecoes = [];

        foreach ($this->getDetectores() as $tipo => $detector) {
            if ($detector($texto)) {
                $detecoes[] = $this->criarDeteccao($tipo, $id_pedido);
            }
        }
        return $detecoes;
    }

    private function detectarCpf(string $texto): bool
    {
        return preg_match(RegexPatterns::CPF, $texto);
    }

    private function detectarEmail(string $texto): bool
    {
        return preg_match(RegexPatterns::EMAIL, $texto);
    }

    private function detectarTelefone(string $texto): bool
    {
        return preg_match(RegexPatterns::TELEFONE, $texto);
    }

    private function criarDeteccao(string $tipo, $id_pedido): EvidenciaDTO
    {
        return new EvidenciaDTO(
            pedido_id: $id_pedido,
            tipo: $tipo,
            score: 1.0,
            valor: null
        );
    }

    private function getDetectores()
    {
        return [
            'CPF' => fn($texto) => $this->detectarCpf($texto),
            'EMAIL' => fn($texto) => $this->detectarEmail($texto),
            'TELEFONE' => fn($texto) => $this->detectarTelefone($texto),
        ];
    }
}


