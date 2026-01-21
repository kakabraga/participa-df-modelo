<?php

namespace App\Services;

use App\Models\Evidencia;
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
    ) {
        $this->contextDetectorService = $contextDetectorService;
        $this->classificadorService = $classificadorService;
        $this->analiseMidiaService = $analiseMidiaService;
    }

    public function analisarTexto(array $input, $arquivo): array
    {
        $pedido = $this->criarPedido($input);
        $detecoes_regex = $this->detectarRegex($input['texto']);
        $detecoes_contexto = [];

        if (empty($detecoes_regex)) {
            $detecoes_contexto = $this->contextDetectorService->detectarContextoPorArquivo($input['texto']);
        }

        $decisao = $this->classificadorService->decide($detecoes_regex, $detecoes_contexto);
        if ($decisao->resultado == 'Limpo' && $input['isArquivo']) {
            $decisao = $this->analiseMidiaService->analisarArquivo($input, $arquivo, $pedido->id);
        }
        return $this->resolveCriacao($decisao);
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

    public function criarPedido($input): Pedido
    {
        return Pedido::create([
            'arquivo' => $input['isArquivo'],
            'hash_texto' => hash('sha256', $input['texto']),
            'resultado' => "Aguardando Análise",
            'tipo_arquivo' => $input['tipo_arquivo'],
            'status' => 'Pendente',
            'origem' => 'Pendente'
        ]);
    }

    public function registrarEvidencias($evidencias, $pedido_id)
    {
        $evidenciasCriadas = [];
        foreach ($evidencias as $evidencia) {
            $evidenciasCriadas[] = Evidencia::create([
                'pedido_id' => $pedido_id,
                'tipo' => $evidencia['tipo'],
                'score' => $evidencia['score'],
            ]);
        }
        return $evidenciasCriadas;
    }
    public function atualizaPedido($decisao)
    {
        $pedido = Pedido::findOrFail($decisao['pedido_id']);
        $pedido->update([
            'resultado' => $decisao['resultado'],
            'origem' => $decisao['origem'],
            'confianca' => $decisao['confianca'],
        ]);

        return $pedido;
    }

    public function resolveCriacao($decisao)
    {
        $retorno = [];
        $retorno[] = $this->atualizaPedido($decisao);
        $retorno[] = $this->registrarEvidencias($decisao['evidencias'], $decisao['pedido_id']);
        return ($retorno);
    }
}
