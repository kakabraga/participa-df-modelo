<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Services\PedidoService;
use App\Http\Controllers\ImportacaoPedidoController;
use App\Services\ImportacaoService;
use App\Services\OcrService;

class PedidoController extends Controller
{
    private $pedidoService;
    private $importacaoService;
    public function __construct(PedidoService $pedidoService, ImportacaoService $importacaoService)
    {
        $this->pedidoService = $pedidoService;
        $this->importacaoService = $importacaoService;
    }
    public function index()
    {
        return view('site.index', ['accept' => $this->montaAccept()]);
    }

    public function storeTexto(Request $request)
    {
        $input = $this->prepararInput($request);
        $pedido = $input['isArquivo']
            ? $this->pedidoService->analisarTextoArquivo($input, $request->arquivo)
            : $this->pedidoService->analisarTexto($input);
        return redirect()->route('home')->with('resultado', $this->montaResultadoView($pedido));
    }

    public function prepararInput(Request $request)
    {
        if ($request->hasFile('arquivo')) {
            $extension = $request->arquivo->extension();
            $mime      = $request->file('arquivo')->extension();
            return $this->processaInputArquivo($request, $extension, $mime);
        }
        if ($request->filled('texto')) {
            return $this->getInputTexto($request);
        }
        return [
            'texto' => '',
            'isArquivo' => false
        ];
    }
    public function processaInputArquivo(Request $request, $extension, $mime)
    {

        if ($extension == 'txt') {
            return $this->getInputArquivoTexto($request, $extension);
        }

        if (in_array($extension, $this->montaArrayImageAccept(), true)) {
            return $this->getInputArquivoImagem($request, $extension);
        }
        if (in_array($extension, ['jpg', 'jpeg'])) {
            return $this->getInputArquivoImagem($request, $extension);
        }

    }
    public function getInputArquivoTexto(Request $request, string $extension)
    {
        return [
            'texto' => $this->importacaoService->processaArquivoTexto(arquivo: $request->file('arquivo')),
            'isArquivo' => false,
            'tipo_arquivo' => $extension
        ];
    }
    public function getInputArquivoImagem(Request $request, string $extension)
    {
        return [
            'texto' => $this->importacaoService->processaArquivoImagem($request->file('arquivo')),
            'isArquivo' => true,
            'tipo_arquivo' => $extension
        ];
    }
    public function getInputTexto(Request $request)
    {
        return [
            'texto' => $request->input('texto'),
            'isArquivo' => false
        ];
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
    private function montaArrayVideoAccept()
    {
        return [
            'video/mp4',
            'video/webm',
            'video/ogg',
            'video/quicktime',
        ];
    }
    private function montaArrayAudioAccept()
    {
        return [
            'audio/mpeg',
            'audio/wav',
            'audio/ogg',
            'audio/webm',
            'audio/aac',
            'audio/mp4'
        ];
    }
    private function montaArrayImageAccept()
    {
        return [
            'image/jpeg',
            'image/jpg',
        ];
    }
    private function montaAccept()
    {
        return array_merge($this->montaArrayImageAccept(), $this->montaArrayAudioAccept(), $this->montaArrayVideoAccept());
    }
}
