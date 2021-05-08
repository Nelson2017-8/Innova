<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Restablecer contraseña</title>
		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet"href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('plugins/adminLTE/css/adminlte.min.css') }}">
	</head>
	<body class="hold-transition login-page">
	@include('sys.errors')
	<div class="login-box">
		<div class="login-logo">
			<a href="{{ url('/') }}">
				<img src="{{ asset('img/favicon.ico') }}" alt="logo" width="80">
				<b>Innova</b> C.A.
			</a>
		</div>
		<!-- /.login-logo -->
		<div class="card">
			<div class="card-body login-card-body">
				<p class="login-box-msg">Ingrese una contraseña nueva</p>

				<form action="{{ route('saveNewPassword') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" name="securityToken" value="{{ $token }}">
					<input type="hidden" name="email" value="{{ $user['email'] }}">
					<div class="input-group mb-3">
						<input type="password" minlength="8" class="form-control" name="password" placeholder="Contraseña" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-envelope"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<button type="submit" class="btn btn-primary btn-block">Crear nueva contraseña</button>
						</div>
						<!-- /.col -->
					</div>
				</form>

				<p class="mt-3 mb-1">
					<a href="{{ route('login') }}">Cancelar</a>
				</p>
			</div>
			<!-- /.login-card-body -->
		</div>
	</div>
	<!-- /.login-box -->

	</body>
{{--    </head>--}}
{{--    <body>--}}
{{--        <h5>Ingrese una contraseña nueva</h5>--}}
{{--        @include('sys.errors')--}}
{{--        <form action="{{ route('saveNewPassword') }}" method="POST" accept-charset="utf-8">--}}
{{--            {{ csrf_field() }}--}}
{{--            <input type="hidden" name="securityToken" value="{{ $token }}">--}}
{{--            <input type="hidden" name="email" value="{{ $user['email'] }}">--}}
{{--            <input type="password" name="password" placeholder="Contraseña">--}}
{{--            <input type="submit" value="Enviar">--}}
{{--        </form>--}}
{{--        <a href="{{ route('login') }}">Volver</a>--}}
{{--    </body>--}}
</html>
