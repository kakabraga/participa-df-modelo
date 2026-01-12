<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'hash_texto',
        'resultado',
        'origem',
        'confianca',
        'tipo_dado',
        'arquivo',
        'tipo_arquivo'
    ];

    public static function criar(array $input, $decisao): self
    {
        return self::create([
            'hash_texto' => hash('sha256', $input['texto']),
            'resultado' => $decisao['resultado'],
            'origem' => $decisao['origem'],
            'confianca' => $decisao['confianca'],
            'tipo_dado' => $decisao['tipo_dado'],
            'arquivo' => $input['isArquivo'],
            'tipo_arquivo' => $input['tipo_arquivo'] ?? null,
        ]);
    }

    // public static function criarComDeteccoes(array $detecoes, $input): self
    // {
    //     return self::create([
    //         'hash_texto' => hash('sha256', $input['texto']),
    //         'resultado' => 'Detectado',
    //         'origem' => $detecoes[0]['origem'],
    //         'confianca' => 0.8,
    //         'tipo_dado' => $detecoes[0]['tipo_dado'],
    //         'arquivo' => $input['isArquivo'],
    //         'tipo_arquivo' => $input['tipo_arquivo'] ?? null
    //     ]);
    // }
}
// Undefined array key "origem"