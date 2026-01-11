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
        'arquivo'
    ];

    public static function criarLimpo(string $texto, bool $isArquivo = false): self
    {
        return self::create([
            'hash_texto' => hash('sha256', $texto),
            'resultado' => 'Limpo',
            'origem' => 'regex',
            'confianca' => 1.0,
            'tipo_dado' => null,
            'arquivo' => $isArquivo
        ]);
    }

    public static function criarComDeteccoes(array $detecoes, string $texto, bool $isArquivo = false): self
    {
        return self::create([
            'hash_texto' => hash('sha256', $texto),
            'resultado' => 'Detectado',
            'origem' => $detecoes[0]['origem'],
            'confianca' => 0.8,
            'tipo_dado' => $detecoes[0]['tipo_dado'],
            'arquivo' => $isArquivo
        ]);
    }
}
// Undefined array key "origem"