<?php

namespace App\Services;

use App\Models\Pedido;
use App\Support\RegexPatterns;
use App\Services\ContextDetectorService;
use App\Services\ClassificadorService;
use App\Services\AnaliseMidiaService;
class PedidoService
{
    private $contextDetectorService;
    private $classificadorService;
    private $analiseMidiaService;
    public function __construct(
        ContextDetectorService $contextDetectorService, 
        ClassificadorService $classificadorService, 
        AnaliseMidiaService $analiseMidiaService
    )
    {
        $this->contextDetectorService = $contextDetectorService;
        $this->classificadorService   = $classificadorService;
        $this->analiseMidiaService    = $analiseMidiaService;
    }

    public function analisarTexto(array $input, $request): Pedido
    {
        $detecoes_regex = $this->detectarRegex($input['texto']);
        $detecoes_contexto = [];
        if(empty($detecoes_regex)) {
            $detecoes_contexto = $this->contextDetectorService->detectarContextoPorArquivo($input['texto']);
        }
        $decisao = $this->classificadorService->decidir($detecoes_regex, $detecoes_contexto);
        if($decisao->resultado != 'Limpo' && $input['isArquivo']) {
            $decisao = $this->analiseMidiaService->analisarArquivo($input, $request->arquivo);
        }
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
