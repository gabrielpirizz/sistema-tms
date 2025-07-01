<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultados da Busca</title>
</head>
<body>
    <h1>Resultados para sua busca</h1>
    <a href="{{ route('entregas.index') }}"><< Nova Busca</a>
    <hr>

    @if($entregas->isEmpty())
        <p>Nenhuma entrega foi encontrada para o CPF informado.</p>
    @else
        <h3>Foram encontradas {{ $entregas->count() }} entrega(s):</h3>
        @foreach($entregas as $entrega)
        <!-- <pre>{{ print_r($entregas->toArray(), true) }}</pre> -->
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <p><strong>Destinatário:</strong> {{ $entrega->destinatario_nome }}</p>
                <p><strong>Transportadora:</strong> {{ $entrega->transportadora?->fantasia }}</p>
                <p><strong>Último Status:</strong> {{ $entrega->rastreamentos->last()?->message ?? 'Status não disponível' }}</p>
                <a href="{{ route('entregas.detalhar', $entrega->id) }}">Ver Detalhes</a>
            </div>
        @endforeach
    @endif
</body>
</html> 