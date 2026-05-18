<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nueva consulta</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Nueva consulta desde el sitio web</h2>
    
    <p><strong>Nombre:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Teléfono/WhatsApp:</strong> {{ $data['phone'] }}</p>
    
    @if(isset($data['property_id']) && $data['property_id'])
        <p><strong>Propiedad de interés:</strong> #{{ $data['property_id'] }}</p>
    @endif

    <hr>
    <p><strong>Mensaje:</strong></p>
    <p>{{ nl2br($data['message']) }}</p>

    <p style="margin-top: 30px; font-size: 0.9em; color: #666;">
        Enviado desde LitoralHogar - {{ now()->format('d/m/Y H:i') }}
    </p>
</body>
</html>