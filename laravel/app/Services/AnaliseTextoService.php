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
    public function analisarTexto(array $input, $id_pedido)
    {
        $args = [
            '--text=' . $input['texto'],
            '--pedido_id=' . $id_pedido,
            '--type=' . 'texto',
        ];

        $resultado = $this->pythonRunner->run($args);
        $decisaoDTO = DecisaoPedidoDTO::fromPythonResult($resultado);
        return $decisaoDTO;
    }
}
