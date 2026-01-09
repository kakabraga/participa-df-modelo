<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PedidoController;
use ResourceBundle;

class ImportacaoPedidoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(
            $this->validaArquivo(),
        );
        return (new PedidoController)->analisarArquivoTexto($this->pegaTexto($request->file('arquivo')));
    }

    public function validaArquivo()
    {
        return [
            'arquivo' => 'file',
            'mimes:txt'
        ];
    }

    public function pegaTexto($arquivo)
    {
        return file_get_contents($arquivo->getRealPath());
    }
}
