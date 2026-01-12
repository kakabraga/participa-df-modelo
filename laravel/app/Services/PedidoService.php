<?php

namespace App\Services;

use App\Models\Pedido;
use App\Support\RegexPatterns;
use App\Services\ContextDetectorService;
use App\Services\ClassificadorService;
class PedidoService
{
    private $contextDetectorService;
    private $classificadorService;
    public function __construct(ContextDetectorService $contextDetectorService, ClassificadorService $classificadorService)
    {
        $this->contextDetectorService = $contextDetectorService;
        $this->classificadorService   = $classificadorService;
    }

    public function analisarTexto(array $input): Pedido
    {
        $detecoes_regex = $this->detectarRegex($input['texto']);
        $detecoes_contexto = [];
        if(empty($detecoes_regex)) {
            $detecoes_contexto = $this->contextDetectorService->detectarContextoPorArquivo($input['texto']);
        }
        $decisao = $this->classificadorService->decidir($detecoes_regex, $detecoes_contexto);
        return Pedido::criar($input, $decisao);
    }
    private function detectarRegex($texto): array
    {
        $detecoes = [];

        if (preg_match(RegexPatterns::CPF, $texto)) {
            $detecoes[] = [
                'tipo' => 'CPF',
                'origem' => 'Regex'
            ];
        }

        if (preg_match(RegexPatterns::EMAIL, $texto)) {
            $detecoes[] = [
                'tipo' => 'EMAIL',
                'origem' => 'Regex',
            ];
        }

        if (preg_match(RegexPatterns::TELEFONE, $texto)) {
            $detecoes[] = [
                'tipo' => 'TELEFONE',
                'origem' => 'Regex',
            ];
        }

        return $detecoes;
    }
}
