<?php 
namespace App\DTO;
use Illuminate\Http\UploadedFile;

class EntradaPedidoDTO {
    public function __construct(
        public string $tipo,              // texto | imagem | audio | video
        public bool $isArquivo = true,
        public ?string $texto = null,
        public ?UploadedFile $arquivo = null,
        public ?string $mime = null,
    ) {}
}