<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadArquivoRequest;
use App\Services\PedidoService;
use App\Resolvers\EntradaPedidoResolver;

class PedidoController extends Controller
{
    public function __construct(
        private PedidoService $pedidoService,
        private EntradaPedidoResolver $entradaResolver
    ) {
    }

    public function index()
    {
        $accept = ['image/jpg', 'image/jpeg'];
        return view('site.index', ['accept' => $accept]);
    }

    public function storeTexto(UploadArquivoRequest $request)
    {
        $entrada = $this->entradaResolver->resolve($request);
        $pedido = match ($entrada->tipo) {
            'texto' => $this->pedidoService->analisarTexto($entrada),
            'imagem' => $this->pedidoService->analisarTextoArquivo($entrada),
            'audio' => $this->pedidoService->analisarAudioArquivo($entrada),
            default => throw new \InvalidArgumentException('Tipo de entrada não suportado'),
        };

        return redirect()
            ->route('home')
            ->with('resultado', $this->montaResultadoView($pedido));
    }

    private function montaResultadoView($resultado)
    {
        return [
            'resultado' => $resultado->pedido->resultado,
            'origem' => $resultado->pedido->origem,
            'confianca' => $resultado->pedido->confianca,
            'evidencias' => $resultado->evidencias,
        ];
    }
}
