<?php

namespace App\Services;
use App\Integrations\Python\PythonRunner;
class AnaliseMidiaService
{
    public function __construct(
        protected PythonRunner $pythonRunner
    ) {
    }

    public function analisarArquivo(array $input, $arquivo)
    {
        $args = [
            '--file=' . $arquivo,
            '--type=' . $input['tipo_arquivo'],
            '--pedido-id=' . 1234,
        ];
        
        $resultado = $this->pythonRunner->run($args);

        dd($resultado);
    }
}
