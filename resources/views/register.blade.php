<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registrar</title>
    </head>
    <body>
        @include('sys.errors')
        
        <form action="{{ route('nRegister') }}" method="POST" accept-charset="utf-8">
            {{ csrf_field() }}
            <input type="text" name="username" placeholder="Nombre de usuario">
            <input type="email" name="email" placeholder="Correo">
            <input type="password" name="password" placeholder="Contraseña">
            <select name="typeuser">
                <option value="Root">Administrador</option>
                <option value="Admin">Moderador</option>
            </select>
            <input type="submit" value="Enviar">
        </form>
        <a href="{{ route('login') }}">Iniciar Sesión</a>

    </body>
</html>
