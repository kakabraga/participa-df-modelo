<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadArquivoRequest extends FormRequest
{
    private const MAX_FILE_SIZE = 51200;
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
                'max:' . self::MAX_FILE_SIZE, // 50MB
                'mimetypes:' . implode(',', $this->tiposPermitidos())
            ],
        ];
    }

    public function tiposPermitidos(): array
    {
        return array_merge($this->tiposImage(), $this->tiposTexto(), $this->tiposVideo(), $this->tiposAudio());
    }
    private function tiposImage(): array
    {
        return [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];
    }
    private function tiposVideo(): array
    {
        return [
            // vídeos
            'video/mp4',
            'video/webm',
            'video/ogg',
            'video/quicktime',
        ];
    }
    private function tiposTexto(): array
    {
        return [
            'text/plain'
        ];
    }
    private function tiposAudio(): array
    {
        return [
            // áudios comuns
            'audio/mpeg',      // .mp3
            'audio/wav',       // .wav
            'audio/ogg',       // .ogg
            'audio/oga',       // .oga
            'audio/webm',      // .webm (áudio)
            'audio/aac',       // .aac
            'audio/flac',      // .flac
            'audio/x-m4a',     // .m4a (muito comum em mobile)
            'audio/mp4',       // .m4a (variação MIME)
        ];
    }

}
