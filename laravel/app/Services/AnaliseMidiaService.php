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

    public function analisarArquivo(string $arquivo, int $id_pedido)
    {
        $args = [
            '--file=' . realpath($arquivo),
            '--type=image',
            '--pedido_id=' . $id_pedido,
        ];

        $resultado = $this->pythonRunner->run($args);

        $resultado['pedido_id'] = $id_pedido;

        return DecisaoPedidoDTO::fromPythonResult($resultado);
    }

    public function analisarTexto($entrada, $id_pedido)
    {
        $args = [
            '--type=text',
            '--text',
            $entrada->texto,
            '--pedido_id',
            (string) $id_pedido,
        ];

        $resultado = $this->pythonRunner->run($args);

        $resultado['pedido_id'] = $id_pedido;

        return DecisaoPedidoDTO::fromPythonResult($resultado);
    }

    public function analisarAudio($entrada, $id_pedido)
    {
        $args = [
            '--file=' . $entrada->arquivo,
            '--type=' . "audio",
            '--pedido_id=' . $id_pedido,

        ];

        $resultado = $this->pythonRunner->run($args);
        $resultado['pedido_id'] = $id_pedido;
        $decisaoDTO = DecisaoPedidoDTO::fromPythonResult($resultado);
        return $decisaoDTO;
    }
}
