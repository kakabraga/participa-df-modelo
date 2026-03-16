<?php

namespace App\Resolvers;

use App\DTO\EntradaPedidoDTO;
use App\Services\OcrService;
use Illuminate\Http\Request;
class EntradaPedidoResolver
{
    public function __construct(
        private OcrService $ocrService
    ) {
    }

    public function resolve(Request $request): EntradaPedidoDTO
    {
        if ($request->hasFile('arquivo')) {
            return $this->resolverArquivo($request);
        }

        return new EntradaPedidoDTO(
            texto: $request->input('texto'),
            tipo: 'texto'
        );
    }

    private function resolverArquivo(Request $request): EntradaPedidoDTO
    {
        $arquivo = $request->file('arquivo');
        $mime = $arquivo->getMimeType();

        if (str_starts_with($mime, needle: 'image/')) {
            return $this->resolverImagem($arquivo, $mime);
        }

        if (str_starts_with($mime, 'audio/')) {
            return new EntradaPedidoDTO(
                texto: null,
                tipo: 'audio',
                mime: $mime,
                arquivo: $arquivo
            );
        }

        if ($mime === 'text/plain') {
            return new EntradaPedidoDTO(
                texto: file_get_contents($arquivo->getRealPath()),
                tipo: 'texto',
                isArquivo: false,
                mime: $mime
            );
        }

        throw new \DomainException('Tipo de arquivo não suportado');
    }

    private function resolverImagem($arquivo, string $mime): EntradaPedidoDTO
    {
        $textoExtraido = $this->ocrService->extrairTexto($arquivo);

        return new EntradaPedidoDTO(
            texto: $textoExtraido . "normalizado",
            tipo: 'imagem',
            mime: $mime,
            arquivo: $arquivo
        );
    }
}
