<?php

namespace App\Services;

use App\DTO\EntradaPedidoDTO;
use App\Models\Evidencia;
use App\Models\Pedido;
use App\Support\RegexPatterns;
use App\Services\ContextDetectorService;
use App\Services\ClassificadorService;
use App\Services\AnaliseMidiaService;
use App\DTO\PersistenciaDecisaoDTO;
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

    public function analisarTextoArquivo(EntradaPedidoDTO $entrada): PersistenciaDecisaoDTO
    {
        $pedido = $this->criarPedido($entrada);
        $detecoes_regex = $this->detectarRegex($entrada->texto);
        $detecoes_contexto = [];

        if (empty($detecoes_regex)) {
            $detecoes_contexto = $this->contextDetectorService->detectarContextoPorArquivo($entrada->texto);
        }

        $decisao = $this->classificadorService->decide($detecoes_regex, $detecoes_contexto, $pedido->id);
        if ($decisao->resultado == 'Limpo') {
            $decisao = $this->analiseMidiaService->analisarArquivo($entrada->arquivo, $pedido->id);
        }

        return $this->resolveCriacao($decisao);
    }
    public function analisarTexto(EntradaPedidoDTO $entrada): PersistenciaDecisaoDTO
    {
        $pedido = $this->criarPedido($entrada);
        $detecoes_regex = $this->detectarRegex($entrada->texto);
        $detecoes_contexto = [];

        if (empty($detecoes_regex)) {
            $detecoes_contexto = $this->contextDetectorService->detectarContextoPorArquivo($entrada->texto);
        }
        $decisao = $this->classificadorService->decide($detecoes_regex, $detecoes_contexto, $pedido->id);

        if ($decisao->resultado == 'Limpo') {
            $decisao = $this->analiseMidiaService->analisarTexto($entrada, $pedido->id);
        }

        return $this->resolveCriacao($decisao);
        
    }
    public function analisarAudioArquivo(EntradaPedidoDTO $entrada): PersistenciaDecisaoDTO
    {
        $pedido = $this->criarPedido($entrada);
        $decisao = $this->analiseMidiaService->analisarAudio($entrada, $pedido->id);

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

    public function criarPedido($entrada): Pedido
    {
        return Pedido::create([
            'arquivo' => $entrada->isArquivo,
            'hash_texto' => hash('sha256', $entrada->texto),
            'resultado' => "Aguardando Análise",
            'tipo_arquivo' => $entrada->tipo_arquivo ?? "texto",
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
                'tipo' => $evidencia->tipo ?? "nenhum",
                'score' => $evidencia->score ?? 0.0,
            ]);
        }
        return $evidenciasCriadas;
    }
    public function atualizaPedido($decisao)
    {
        $pedido = Pedido::findOrFail($decisao->pedido_id);
        $pedido->update([
            'resultado' => $decisao->resultado,
            'origem' => $decisao->origem,
            'confianca' => $decisao->confianca,
        ]);

        return $pedido;
    }

    public function resolveCriacao($decisao): PersistenciaDecisaoDTO
    {
        return new PersistenciaDecisaoDTO(
            pedido: $this->atualizaPedido($decisao),
            evidencias: $this->registrarEvidencias(
                $decisao->evidencias,
                $decisao->pedido_id,
            )
        );
    }

}
