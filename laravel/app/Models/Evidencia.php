<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipo',
        'valor',
        'score',
        'pedido_id'
    ];

    public function analise()
    {
        return $this->belongsTo(Pedido::class);
    }
}
