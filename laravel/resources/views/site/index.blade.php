<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Análise de Pedido</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-6 rounded shadow w-full max-w-xl">
        <h1 class="text-xl font-bold mb-4">
            Análise de Pedido Público
        </h1>

@if(session('resultado'))
    <div class="bg-green-100 text-green-700 p-3 rounded">
        Resultado: {{ session('resultado') }}
    </div>
@endif

        {{-- Erros de validação --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="text-sm">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pedido.analisar') }}" enctype="multipart/form-data">
            @csrf

            {{-- Texto manual --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">
                    Texto do pedido
                </label>
                <textarea
                    name="texto"
                    rows="6"
                    class="w-full border rounded p-2"
                    placeholder="Cole aqui o texto do pedido..."
                >{{ old('texto') }}</textarea>
            </div>

            {{-- Upload de arquivo --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">
                    Ou envie um arquivo (.txt)
                </label>
                <input
                    type="file"
                    name="arquivo"
                    accept=".txt"
                    class="w-full border rounded p-2"
                >
            </div>

            <button
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
                Analisar
            </button>
        </form>
    </div>

</body>
</html>
