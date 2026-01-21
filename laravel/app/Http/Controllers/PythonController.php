<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Integrations\Python\PythonRunner;

class PythonController extends Controller
{
    public function testePython(PythonRunner $python)
    {
        $saida = $python->run();

        return json_decode($saida);
    }

    public function store(Request $request, PythonRunner $python)
    {
        $file = $request->file('arquivo');
        $path = $file->store('uploads_temp');

        $fullPath = storage_path('app/' . $path);

        $saida = $python->run([
            "--file",
            $fullPath,
            "--type",
            $file->extension(),
            "--pedido-id",
            123
        ]);
        return response()->json($saida);

    }

    

}
