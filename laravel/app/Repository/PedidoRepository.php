<?php

namespace App\Repository;
use App\DTO\DecisaoPedidoDTO;
use App\Models\Pedido;
use App\DTO\EntradaPedidoDTO;


class PedidoRepository
{


    public function criarPedido(EntradaPedidoDTO $entrada): Pedido
    {
        return Pedido::create([
            'arquivo' => $entrada->isArquivo,
            'hash_texto' => hash('sha256', $entrada->texto),
            'resultado' => "Aguardando Análise",
            'tipo_arquivo' => $entrada->tipo_arquivo ?? "texto",
            'status' => 'Pendente',
            'origem' => 'Pendente'
        ]);
    }

    public function atualizaPedido(DecisaoPedidoDTO $decisao): Pedido
    {
        $pedido = Pedido::findOrFail($decisao->pedido_id);
        $pedido->update([
            'resultado' => $decisao->resultado,
            'origem' => $decisao->origem,
            'confianca' => $decisao->confianca,
        ]);

        return $pedido;
    }
}
