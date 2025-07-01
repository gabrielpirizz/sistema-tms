<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Entrega</title>
</head>
<body>
    <h1>Rastrear Entrega</h1>
    <p>Digite o CPF do destinatário para consultar suas entregas:</p>
    
    <form action="{{ route('entregas.buscar') }}" method="POST">
        @csrf
        <label>CPF do Destinatário:</label><br>
        <input type="text" name="cpf" value="{{ old('cpf') }}" required>
        <button type="submit">Buscar</button>
        
        @error('cpf') 
            <p style="color:red;">{{ $message }}</p> 
        @enderror
    </form>
</body>
</html>