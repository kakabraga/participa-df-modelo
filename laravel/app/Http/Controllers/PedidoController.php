<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
class PedidoController extends Controller
{
    public function index()
    {
        return view('site.index');
    }

    public function analisar(Request $request)
    {
        $request->validate([
            'texto' => 'required|string|min:10'
        ]);

        $texto = $request->texto;
        $detecoes = $this->detectarRegex($texto);

        if (empty($detecoes)) {
            return response()->json(['status' => 'limpo']);
        }

        return (new Pedido)->savePedidoRegex($detecoes, $request->texto);
    }

    public function detectarCpf(string $texto): array
    {
        preg_match_all(
            '/\b\d{3}\.\d{3}\.\d{3}\-\d{2}\b|\b\d{11}\b/',
            $texto,
            $matches
        );

        return $matches[0];
    }

    private function detectarTelefone(string $texto): array
    {
        preg_match_all(
            '/\(\d{2}\)\s?\d{4,5}\-\d{4}/',
            $texto,
            $matches
        );
        return $matches[0];
    }

    private function detectarEmail(string $texto): array
    {
        preg_match_all(
            '/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i',
            $texto,
            $matches
        );

        return $matches[0];
    }

    private function detectarRegex($texto): array
    {
        $detecoes = [];

        if ($cpf = $this->detectarCpf($texto)) {
            $detecoes[] = [
                'tipo' => 'CPF',
                'quantidade' => count($cpf)
            ];
        }

        if ($email = $this->detectarEmail($texto)) {
            $detecoes[] = [
                'tipo' => 'EMAIL',
                'quantidade' => count($email)
            ];
        }

        if ($telefone = $this->detectarTelefone($texto)) {
            $detecoes[] = [
                'tipo' => 'TELEFONE',
                'quantidade' => count($telefone)
            ];
        }

        return $detecoes;
    }

}
