<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 40px auto; padding: 0 20px; }
        input { width: 100%; padding: 8px; margin: 6px 0 16px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        label { font-weight: bold; font-size: 14px; }
        .btn { padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; }
        .error { color: #ef4444; font-size: 13px; margin-top: -12px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Adicionar Produto</h1>
    <a href="{{ route('products.index') }}">← Voltar</a>

    <form action="{{ route('products.store') }}" method="POST" style="margin-top: 20px">
        @csrf
        <label>Nome do produto</label>
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Notebook Dell">
        @error('name') <p class="error">{{ $message }}</p> @enderror

        <label>URL do produto</label>
        <input type="url" name="url" value="{{ old('url') }}" placeholder="https://...">
        @error('url') <p class="error">{{ $message }}</p> @enderror

        <label>Seletor CSS do preço</label>
        <input type="text" name="css_selector" value="{{ old('css_selector') }}" placeholder="Ex: .price, #product-price">
        @error('css_selector') <p class="error">{{ $message }}</p> @enderror

        <label>Alertar se preço cair abaixo de (opcional)</label>
        <input type="number" name="alert_below" value="{{ old('alert_below') }}" step="0.01" placeholder="Ex: 1500.00">

        <label>E-mail para alerta (opcional)</label>
        <input type="email" name="email_alert" value="{{ old('email_alert') }}" placeholder="seu@email.com">

        <button type="submit" class="btn">Salvar e verificar preço</button>
    </form>
</body>
</html>