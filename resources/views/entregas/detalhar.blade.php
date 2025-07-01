<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Entrega</title>
</head>
<body>
    <h1>Detalhes da Entrega:</h1>
    <p>Destinatário: {{ $entrega->destinatario_nome }}</p>
    <p>Transportadora: {{ $entrega->transportadora?->fantasia }}</p>
    <p>CNPJ da Transportadora: {{ $entrega->transportadora?->cnpj }}</p>
    <p>Último Status: <b>{{ $entrega->rastreamentos->last()?->message ?? 'Status não disponível' }}</b></p>
    <p>Endereço: {{ $entrega->destinatario_endereco }}</p>
    <p>CEP: {{ $entrega->destinatario_cep }}</p>
    <p>Estado: {{ $entrega->destinatario_estado }}</p>
    <p>Volumes: {{ $entrega->volumes }}</p>
    <p>Remetente: {{ $entrega->remetente_nome }}</p>

    <h2>Rastreamentos</h2>
    <ul>
        @foreach($entrega->rastreamentos as $rastreamento)
            <li>{{ $rastreamento->message }} - {{ $rastreamento->date->format('d/m/Y | H:i:s') }}</li>
        @endforeach
    </ul>
    
    <h2>Voltar para a tela inicial</h2>
    <a href="{{ route('entregas.index') }}"><< Nova Busca</a>
</body>
</html> 