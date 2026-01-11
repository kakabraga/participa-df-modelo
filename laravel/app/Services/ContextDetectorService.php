<?php

namespace App\Services;

use App\Models\Pedido;
use Illuminate\Support\Facades\Storage;

class ContextDetectorService
{

    public function carregarFrases()
    {
        $conteudo = Storage::get('context/relacoes.txt');

        return array_filter(
            array_map('trim', explode("\n", $conteudo))
        );
    }

    public function detectarContextoPorArquivo(string $texto): array
    {
        $detecoes = [];

        foreach ($this->carregarFrases() as $frase) {
            if (str_contains($this->normalizar($texto), $this->normalizar($frase))) {
                $detecoes[] = [
                    'tipo_dado' => 'relações_pessoais',
                    'origem' => 'contexto'
                ];
            }
        }

        return $detecoes;
    }
    private function normalizar(string $texto): string
    {
        $texto = mb_strtolower($texto);
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
        return preg_replace('/[^\w\s]/', '', $texto);
    }

}
