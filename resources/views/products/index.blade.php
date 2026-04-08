<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verificador de Preços</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 40px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #f5f5f5; }
        .btn { padding: 8px 14px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-red { background: #ef4444; }
        .alert { padding: 10px; background: #d1fae5; border-radius: 6px; margin-bottom: 16px; }
    </style>
</head>
<body>
    <h1>Verificador de Preços</h1>
    <a href="{{ route('products.create') }}" class="btn">+ Adicionar produto</a>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço atual</th>
                <th>Alerta abaixo de</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></td>
                <td>{{ $product->current_price ? 'R$ ' . number_format($product->current_price, 2, ',', '.') : '—' }}</td>
                <td>{{ $product->alert_below ? 'R$ ' . number_format($product->alert_below, 2, ',', '.') : '—' }}</td>
                <td>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-red" onclick="return confirm('Remover?')">Remover</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4">Nenhum produto cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>