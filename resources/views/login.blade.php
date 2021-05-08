<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Inicio de Sesión</title>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/adminLTE/css/adminlte.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/toastr/toastr.min.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
	<!-- /.login-logo -->
	<div class="card card-outline card-primary">
		<div class="card-header text-center">
			<a href="{{ url('/') }}" class="h5">
				<img src="{{ asset('img/logo-180.png') }}" alt="logo" width="110">
				Accediendo al sistema
			</a>
		</div>
		<div class="card-body">
			<p class="login-box-msg text-muted">Sistema de facturación e inventarios</p>

			<form class="validate" action="{{ route('nLogin') }}" method="post">
				{{ csrf_field() }}
				<div class="input-group mb-3">
					<input type="email" name="email" class="form-control" placeholder="Correo Electrónico" required>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-envelope"></span>
						</div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" name="password" class="form-control" placeholder="Contraseña" minlength="8"
						   required>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-7">
						<div class="icheck-primary">
							<input type="checkbox" id="remember">
							<label for="remember">
								Recordar me
							</label>
						</div>
					</div>
					<!-- /.col -->
					<div class="col-5">
						<button type="submit" class="btn btn-primary btn-block">Acceder
							<span class="fas fa-paper-plane ml-2"></span>
						</button>
					</div>
					<!-- /.col -->
				</div>
			</form>
			<p class="mb-1">
				<a href="{{ route('password_reset') }}">¿Olvido su contraseña?</a>
			</p>

		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
</div>
<!-- /.login-box -->

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/adminLTE/js/adminlte.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<script>
	$(document).ready(function () {
		$('form').submit(function (e) {
			e.preventDefault();
			let action = $(this).attr('action');
			let method = $(this).attr('method');
			let serialize = $(this).serialize();
			$('[type="submit"]').attr('disabled', 'disabled');
			$.ajax({
				url: action,
				type: method,
				data: serialize,
			})
				.done(function (data) {
					// Reenvio automatico, si el serve lo solicita
					if (data.redirect != undefined) {
						setInterval(function () {
							location.href = data.redirect
						}, 3000, data.redirect)
					}

					if (data.result == 'success') {
						toastr.success(data.messenger)
					} else {
						toastr.error('Credenciales incorrectas o el usuario no existe')
					}
				})
				.fail(function (data) {
					console.log("error");
					console.log(data);
				})
				.always(function () {
					$('[type="submit"]').removeAttr('disabled')
				});

		});

	});
</script>
</body>
</html>
