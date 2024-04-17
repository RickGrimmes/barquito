<!DOCTYPE html>
<html>
<head>
    <title>Verificación de 2 pasos</title>
</head>
<body>
    <h2>Hola {{ $name }},</h2>
    <p>Gracias por registrarte en nuestra aplicación. Por favor, haz clic en el enlace de abajo para verificar tu dirección de correo electrónico:</p>
    <a>{{ $code }}</a>
    <p>Si no te has registrado o has iniciado sesión en nuestra aplicación, por favor, ignora este correo electrónico.</p>
</body>
</html>