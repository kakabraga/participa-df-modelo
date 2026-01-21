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
        'tipo_arquivo',
        'status'
    ];

    public static function criar(array $input, $decisao): self
    {
        return self::create([
            'hash_texto' => hash('sha256', $input['texto']),
            'resultado' => $decisao->resultado,
            'origem' => $decisao->origem,
            'confianca' => $decisao->confianca,
            'tipo_dado' => $decisao->tipo_dado,
            'arquivo' => $input['isArquivo'],
            'status'  => "Concluído",
            'tipo_arquivo' => $input['tipo_arquivo'] ?? null,
        ]);
    }

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class);
    }
}
// Undefined array key "origem"