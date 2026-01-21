<?php

namespace App\Services;
use App\Integrations\Python\PythonRunner;
use App\Services\PedidoService;
class AnaliseMidiaService
{
    private $pedidoService;
    public function __construct(
        protected PythonRunner $pythonRunner
    ) {
    }

    public function analisarArquivo(array $input, $arquivo, $id_pedido)
    {
        $args = [
            '--file=' . $arquivo,
            '--type=' . $input['tipo_arquivo'],
            '--pedido-id=' . $id_pedido,
        ];

        $resultado = $this->pythonRunner->run($args);
        $resultado['pedido_id'] = $id_pedido;
        // dd($resultado);
        return $resultado;
    }

    public function montaArgumentos()
    {

    }
}
