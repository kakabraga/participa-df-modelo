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
        return view('site.index');
    }

    public function storeTexto(Request $request)
    {
        $input = $this->prepararInput($request);
        $pedido = $this->pedidoService->analisarTexto($input, $request->arquivo);        
        $message = $pedido[0]['resultado'] != 'Limpo' ? 'Texto contém informações pessoais!' : 'Texto não contém informações pessoais!';
        return redirect()->route('home')->with('resultado', $pedido);
    }

    public function prepararInput(Request $request)
    {
        if ($request->hasFile('arquivo')) {
            return $this->processaInputArquivo($request);
        }
        if ($request->filled('texto')) {
            return $this->getInputTexto($request);
        }
        return [
            'texto' => '',
            'isArquivo' => false
        ];
    }
    public function processaInputArquivo(Request $request)
    {
        $extension = $request->arquivo->extension();
        if($extension == 'txt') {
            return $this->getInputArquivoTexto($request, $extension);
        }

        if (in_array($extension, ['jpg', 'jpeg'])) {
            return $this->getInputArquivoImagem($request, $extension);
        }
    }
    public function getInputArquivoTexto(Request $request, string $extension)
    {
        return [
            'texto' => $this->importacaoService->processaArquivoTexto($request->file('arquivo')),
            'isArquivo' => true,
            'tipo_arquivo' => $extension
        ];
    }
    public function getInputArquivoImagem(Request $request,  string $extension)
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
}
