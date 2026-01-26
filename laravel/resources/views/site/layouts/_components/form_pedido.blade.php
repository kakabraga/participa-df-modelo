<form method="POST" action="{{ route('pedido.analisar') }}" enctype="multipart/form-data">
    @csrf

    {{-- Texto manual --}}
    <div class="mb-4">
        <label class="block font-semibold mb-1">
            Texto do pedido
        </label>
        <textarea name="texto" rows="6" class="w-full border rounded p-2"
            placeholder="Cole aqui o texto do pedido...">{{ old('texto') }}</textarea>
    </div>

    {{-- Upload de arquivo --}}
    <div class="mb-4">
        <label class="block font-semibold mb-1">
            Ou envie um arquivo (.txt)
        </label>
        <input type="file" name="arquivo" accept=".txt, {{ implode(',', $accept) }}" class="w-full border rounded p-2">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Analisar
    </button>
</form>