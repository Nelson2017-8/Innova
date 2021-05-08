<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Recuperar contraseña</title>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet"
		  href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
			<p class="login-box-msg">Ingrese su dirección de correo, para poder crear una nueva contraseña</p>

			<form action="{{ route('nPasswordreset') }}" method="post">
				{{ csrf_field() }}
				<div class="input-group mb-3">
					<input type="email" class="form-control" name="email" placeholder="Correo Electronico" required>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-envelope"></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<button type="submit" class="btn btn-primary btn-block">Restablecer contraseña</button>
					</div>
					<!-- /.col -->
				</div>
			</form>

			<p class="mt-3 mb-1">
				<a href="{{ route('nLogin') }}">Iniciar Sesión</a>
			</p>
		</div>
		<!-- /.login-card-body -->
	</div>
</div>
<!-- /.login-box -->

</body>
</html>
