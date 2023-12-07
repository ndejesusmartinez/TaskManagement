<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>


<form action="{{ route('authenticateUser') }}" method="post">
    @csrf
    <label for="">Iniciar sesión</label>
    <input type="email" name="email" id="email" placeholder="Correo">
    <input type="password" name="password" id="password" placeholder="Contraseña">

    <button type="submit">Iniciar sesión</button>
</form>

<a href="{{ route('register') }}"> <button>Registrarse</button></a>

</body>
</html>
