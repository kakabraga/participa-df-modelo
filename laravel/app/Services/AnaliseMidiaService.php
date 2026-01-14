<?php

namespace App\Services;
use App\Integrations\Python\PythonRunner;
class AnaliseMidiaService
{
    public function __construct(
        protected PythonRunner $pythonRunner
    ) {
    }

    public function analisarArquivo(array $input)
    {
        // $file = $request->file('arquivo');

        // $path = $file->store('uploads_temp');

        // $fullPath = storage_path('app/' . $path);
        // $args = [
        //     '--file=' . $input['path'],
        //     '--type=' . $input['tipo_arquivo'],
        //     '--pedido-id=' . $input['pedido_id'],
        // ];

        // $resultado = $this->pythonRunner->run($args);

        dd($input);
    }
}
