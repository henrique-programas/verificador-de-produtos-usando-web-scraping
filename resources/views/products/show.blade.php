<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }}</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #f5f5f5; }
        .price { font-size: 2rem; font-weight: bold; color: #16a34a; }
    </style>
</head>
<body>
    <a href="{{ route('products.index') }}">← Voltar</a>
    <h1>{{ $product->name }}</h1>
    <p><a href="{{ $product->url }}" target="_blank">{{ $product->url }}</a></p>
    <p class="price">{{ $product->current_price ? 'R$ ' . number_format($product->current_price, 2, ',', '.') : 'Aguardando...' }}</p>

    <h2>Histórico de preços</h2>
    <table>
        <thead>
            <tr><th>Preço</th><th>Data</th></tr>
        </thead>
        <tbody>
            @forelse($history as $h)
            <tr>
                <td>R$ {{ number_format($h->price, 2, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($h->scraped_at)->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="2">Nenhum histórico ainda.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>