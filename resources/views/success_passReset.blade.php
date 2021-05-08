<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Contraseña Recuperada</title>
    </head>
    <body>
        <h5>Exito: Su contraseña ha sido cambiada exitosamente</h5>
        
        <p>Por favor, inicie sesión con su nueva contraseña. Si usted no es redimencionado automaticamente en 5 segundos, por favor ingrese desde el siguiente enlace: <a href="{{ route('login') }}">Iniciar Sesión</a></p>
        <script>
            setInterval(function(){
                location.href = "{{ route('login') }}"
            }, 5000);
        </script>       
    </body>
</html>
