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
            @php $res = session('resultado'); @endphp

            <div class="bg-green-100 text-green-700 p-4 rounded">
                <p><strong>Resultado:</strong> {{ $res['resultado'] }}</p>
                <p><strong>Confiança:</strong> {{ number_format($res['confianca'] * 100, 2) }}%</p>

                @if(!empty($res['evidencias']))
                    <hr class="my-2">
                    <ul class="list-disc pl-5">
                        @foreach($res['evidencias'] as $e)
                            <li>
                                {{ ucfirst($e['tipo']) }}:
                                <strong>{{ $e['valor'] }}</strong>
                                (score {{ $e['score'] }})
                            </li>
                        @endforeach
                    </ul>
                @endif
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

        @component('site.layouts._components.form_pedido', ['accept' => $accept]);
        @endcomponent
    </div>

</body>

</html>