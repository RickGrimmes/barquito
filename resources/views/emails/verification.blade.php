<!DOCTYPE html>
<html>
<head>
    <title>Verificación de correo electrónico</title>
</head>
<body>
    <h2>Hola {{ $name }},</h2>
    <p>Gracias por registrarte en nuestra aplicación. Por favor, haz clic en el enlace de abajo para verificar tu dirección de correo electrónico:</p>
    <a href="{{ url('/api/verifyemail/'.$user->verification_token) }}">Verificar correo electrónico</a>
    <p>Si no te has registrado en nuestra aplicación, por favor, ignora este correo electrónico.</p>
</body>
</html>