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

    ];
    public function savePedidoRegex(array $detecoes, string $texto)
    {
        return self::create([
            'hash_texto' => hash('sha256', $texto),
            'resultado' => 'dados_pessoais_detectados',
            'origem' => 'regex',
            'confianca' => 1.0,
            'tipo_dado' => implode(',', $tiposDetectados = array_column($detecoes, 'tipo'))
        ]);
    }
}
