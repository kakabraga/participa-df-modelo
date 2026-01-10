<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ImportacaoService
{

    public function validaArquivo(UploadedFile $arquivo): void
    {
        $validator = Validator::make(
            ['arquivo' => $arquivo],
            ['arquivo' => 'required|file|mimes:txt|max:2048']
        );
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function pegaTexto(UploadedFile $arquivo): bool|string
    {
        return file_get_contents($arquivo->getRealPath());
    }

    public function processaArquivo(UploadedFile  $arquivo) {
        $this->validaArquivo($arquivo);
        return $this->pegaTexto($arquivo);
    }
}
