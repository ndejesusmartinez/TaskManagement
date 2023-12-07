<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>


<form action="{{ route('RegisterUser') }}" method="post">
    @csrf
    <input type="text" name="name" id="name" placeholder="Nombre">
    <input type="email" name="email" id="email" placeholder="Correo">
    <input type="password" name="password" id="password" placeholder="Contraseña">

    <button type="submit">Registrar</button>
</form>


<a href="{{ route('login') }}"> <button>Estas registrado, inicia sesión</button></a>

</body>
</html>
