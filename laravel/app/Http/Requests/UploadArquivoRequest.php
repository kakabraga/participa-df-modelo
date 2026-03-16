<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadArquivoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'arquivo' => [
                'file',
                'max:51200', // 50MB
                'mimetypes:' . implode(',', $this->tiposPermitidos())
            ],
        ];
    }

    private function tiposPermitidos(): array
    {
        return [
            // imagens
            'image/jpeg',
            'image/png',
            'image/webp',

            // vídeos
            'video/mp4',
            'video/webm',
            'video/ogg',
            'video/quicktime',

            // áudios
            'audio/mpeg',
            'audio/wav',
            'audio/ogg',
            'audio/webm',
            'audio/aac',
            'audio/mp4',
        ];
    }
}
