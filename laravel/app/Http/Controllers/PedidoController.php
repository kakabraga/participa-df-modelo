<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Services\PedidoService;
use App\Http\Controllers\ImportacaoPedidoController;
use App\Services\ImportacaoService;

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
        $pedido = $this->pedidoService->analisarTexto($input['texto'], $input['isArquivo']);
        return redirect()->route('home')->with('resultado', $pedido);
    }

    public function prepararInput(Request $request)
    {
        if ($request->hasFile('arquivo')) {
            return $this->getInputArquivo($request);
        }
        if ($request->filled('texto')) {
            return $this->getInputTexto($request);
        }
        return [
            'texto' => '',
            'isArquivo' => false
        ];
    }
    public function getInputArquivo(Request $request)
    {
        return [
            'texto' => $this->importacaoService->processaArquivo($request->file('arquivo')),
            'isArquivo' => true
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
