<?php

namespace App\Services;
use App\Integrations\Python\PythonRunner;
use App\Services\PedidoService;
use App\DTO\DecisaoPedidoDTO;
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
            '--type=' . "image",
            '--pedido_id=' . $id_pedido,
        ];

        $resultado = $this->pythonRunner->run($args);
        $resultado['pedido_id'] = $id_pedido;
        // dd($resultado);
        $decisaoDTO = DecisaoPedidoDTO::fromPythonResult($resultado);
        return $decisaoDTO;
    }

    public function analisarTexto(array $input, $id_pedido)
    {
        $args = [
            '--text=' . $input['texto'],
            '--pedido_id=' . $id_pedido,
        ];

        $resultado = $this->pythonRunner->run($args);
        $decisaoDTO = DecisaoPedidoDTO::fromPythonResult($resultado);
        return $decisaoDTO;
    }
}
