<?php

namespace App\Services;

use App\DTO\EntradaPedidoDTO;
use App\DTO\PersistenciaDecisaoDTO;
use App\DTO\DecisaoPedidoDTO;

use App\Services\ContextDetectorService;
use App\Services\ClassificadorService;
use App\Services\AnaliseMidiaService;
use App\Services\RegexService;

use App\Repository\PedidoRepository;

use App\Models\Evidencia;
use App\Models\Pedido;
class PedidoService
{
    private $contextDetectorService;
    private $classificadorService;
    private $analiseMidiaService;
    public function __construct(
        ContextDetectorService $contextDetectorService,
        ClassificadorService $classificadorService,
        AnaliseMidiaService $analiseMidiaService,
        RegexService $regexService,
        PedidoRepository $pedidoRepository
    ) {
        $this->contextDetectorService = $contextDetectorService;
        $this->classificadorService = $classificadorService;
        $this->analiseMidiaService = $analiseMidiaService;
        $this->regexService = $regexService;
        $this->pedidoRepository = $pedidoRepository;
    }

    public function analisarTextoArquivo(EntradaPedidoDTO $entrada): PersistenciaDecisaoDTO
    {
        $pedido = $this->criarPedido($entrada);
        $decisao = $this->executarAnaliseBase($entrada, $pedido->id);

        if ($decisao->resultado === 'Limpo') {
            $decisao = $this->analiseMidiaService->analisarArquivo($entrada->arquivo, $pedido->id);
        }

        return $this->resolveCriacao($decisao);
    }

    private function executarAnaliseBase(EntradaPedidoDTO $entrada, int $pedido_id): DecisaoPedidoDTO
    {
        $detecoes_regex = $this->regexService->detectarRegex($entrada->texto, $pedido_id);
        $detecoes_contexto = [];

        if (empty($detecoes_regex)) {
            $detecoes_contexto = $this->contextDetectorService->detectarContextoPorArquivo($entrada->texto);
        }
        return $this->classificadorService->decide($detecoes_regex, $detecoes_contexto, $pedido_id);
    }
    public function analisarTexto(EntradaPedidoDTO $entrada): PersistenciaDecisaoDTO
    {
        $pedido = $this->pedidoRepository->criarPedido($entrada);
        $decisao = $this->executarAnaliseBase($entrada, $pedido->id);
        if ($decisao->resultado == 'Limpo') {
            $decisao = $this->analiseMidiaService->analisarTexto($entrada, $pedido->id);
        }

        return $this->resolveCriacao($decisao);

    }
    public function analisarAudioArquivo(EntradaPedidoDTO $entrada): PersistenciaDecisaoDTO
    {
        $pedido = $this->pedidoRepository->criarPedido($entrada);
        $decisao = $this->analiseMidiaService->analisarAudio($entrada, $pedido->id);

        return $this->resolveCriacao($decisao);
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
    public function resolveCriacao($decisao): PersistenciaDecisaoDTO
    {
        return new PersistenciaDecisaoDTO(
            pedido: $this->pedidoRepository->atualizaPedido($decisao),
            evidencias: $this->registrarEvidencias(
                $decisao->evidencias,
                $decisao->pedido_id,
            )
        );
    }

}
