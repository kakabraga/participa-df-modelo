<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class OcrService
{
    private string $tesseractPath = 'C:/Program Files/Tesseract-OCR/tesseract.exe';
    public function extrairTexto(string $imagemPath): string
    {
        $process = $this->processaTessaract($imagemPath);
        $process->run();

        $this->capturaErros($process);
        $texto = $process->getOutput();
        $texto_normalizado = $this->normalizarTexto($texto);
        $texto_corrigido = $this->corrigirErrosComuns($texto_normalizado);
        return $this->prepararParaAnalise($texto_corrigido);
    }

    public function processaTessaract($imagemPath)
    {
        return new Process([
            $this->tesseractPath,
            $imagemPath,
            'stdout',
            '-l',
            'por',
            '--oem',
            '3',
            '--psm',
            '6'
        ]);
    }
    private function capturaErros(Process $process): void
    {
        if (! $process->isSuccessful()) {
            throw new \RuntimeException(
                'OCR falhou: ' . $process->getErrorOutput()
            );
        }
    }

    private function normalizarTexto(string $texto): string
    {
        $texto = mb_convert_encoding($texto, 'UTF-8', 'UTF-8');

        // 2️⃣ Remove caracteres "lixo" que não são letras, números ou pontuação básica
        $texto = preg_replace('/[^\p{L}\p{N}\s\.\,\-\:\;]/u', '', $texto);

        // 3️⃣ Substitui múltiplos espaços por um único espaço
        $texto = preg_replace('/\s+/', ' ', $texto);

        // 4️⃣ Quebra linhas entre comandos ou frases mais longas
        $texto = preg_replace('/(\.|\;|\:)/', "$1\n", $texto);

        // 5️⃣ Trim para remover espaços no início/fim
        $texto = trim($texto);

        return $texto;
    }
    private function corrigirErrosComuns(string $texto): string
    {
        $map = [
            'Ã¡' => 'á',
            'Ã©' => 'é',
            'Ã­' => 'í',
            'Ã³' => 'ó',
            'Ãº' => 'ú',
            'Ã£' => 'ã',
            'Ãµ' => 'õ',
            'Ã§' => 'ç',
            'Ã‰' => 'É',
            'Ã'  => 'Á',
            '0'  => 'O', // contexto textual
            '1'  => 'I',
        ];

        return str_replace(array_keys($map), array_values($map), $texto);
    }
    private function prepararParaAnalise(string $texto): string
    {
        $texto = mb_strtolower($texto, 'UTF-8');
        $texto = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $texto);
        $texto = preg_replace('/\s+/', ' ', $texto);

        return trim($texto);
    }
}
