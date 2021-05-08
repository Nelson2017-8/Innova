<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Panel de Control</title>
		<link rel="icon" href="{{ $_SESSION['data']['favicon'] }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontisto/css/fontisto.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('plugins/adminLTE/css/adminlte.min.css') }}">
	</head>
    <body>
    	<section class="col-10 my-5 py-5 offset-1">
			<h1>Panel de Control</h1>
			Hola bienvenido {{ $_SESSION['username'] }}

			@include('sys.errors')

			<br>
			<p><b>Datos del usuario</b></p>
			<ul>
				<li>ID: {{ $_SESSION['id'] }}</li>
				<li>Nombre Usuario: {{ $_SESSION['username'] }}</li>
				<li>Correo: {{ $_SESSION['email'] }}</li>
				<li>Tipo de usuario: {{ $_SESSION['typeuser'] }}</li>
				<li>Fecha de registro: {{ $_SESSION['date'] }}</li>
			</ul>

			<br>
			<p><b>Lista de opciones</b></p>
			<ul>
				<li>Usuarios:</li>
				<ul>
					<li><a href="{{ route('users.index') }}">Consultar</a></li>
					<li><a href="{{ route('users.create') }}">Crear</a></li>
				</ul>
				<li>Clientes</li>
				<ul>
					<li><a href="{{ route('clientes.index') }}">Consultar</a></li>
					<li><a href="{{ route('clientes.create') }}">Crear</a></li>
				</ul>
				<li>Proveedores</li>
				<ul>
					<li><a href="{{ route('proveedores.index') }}">Consultar</a></li>
					<li><a href="{{ route('proveedores.create') }}">Crear</a></li>
				</ul>
				<li>Sucursal</li>
				<ul>
					<li><a href="{{ route('sucursal.index') }}">Consultar</a></li>
					<li><a href="{{ route('sucursal.create') }}">Crear</a></li>
				</ul>
				<li>Categoria</li>
				<ul>
					<li><a href="{{ route('categoria.index') }}">Consultar</a></li>
					<li><a href="{{ route('categoria.create') }}">Crear</a></li>
				</ul>
				<li>Sucategoria</li>
				<ul>
					<li><a href="{{ route('subcategoria.index') }}">Consultar</a></li>
					<li><a href="{{ route('subcategoria.create') }}">Crear</a></li>
				</ul>
				<li>Almacen</li>
				<ul>
					<li><a href="{{ route('almacen.index') }}">Consultar</a></li>
					<li><a href="{{ route('almacen.create') }}">Crear</a></li>
				</ul>
				<li>Compra realizadas a los Proveedores</li>
				<ul>
					<li><a href="{{ route('compras.index') }}">Consultar</a></li>
					<li><a href="{{ route('compras.create') }}">Crear</a></li>
				</ul>
				<li>Insumos</li>
				<ul>
					<li><a href="">Consultar</a></li>
					<li><a href="">Crear</a></li>
				</ul>
				<li>Insumos Comprados</li>
				<ul>
					<li><a href="">Consultar</a></li>
					<li><a href="">Crear</a></li>
				</ul>

				<li>Productos Elaborados</li>
				<ul>
					<li>Vencidos</li>
					<li>En proceso</li>
					<li>Finalizados y Almacenados: En espera para entregar al cliente</li>
					<li>Entregado al cliente</li>
				</ul>
				<li>Presupuesto</li>
				<ul>
					<li><a href="">Consultar</a></li>
					<li><a href="">Insertar</a></li>
					<li>No aprobados</li>
					<li>En proceso</li>
					<li>Aprobados</li>
					<li>Terminados: El cliente ya recivio su producto</li>
				</ul>
				<li>Facturación</li>
				<ul>
					<li>Pre-factura: abonados o pagados por completos pero el producto no ha sido entregados</li>
					<li>Facturas Recibidas: Las facturas de las compra a los proveedores</li>
					<li>Facturas emitidas: Las facturas emitida por la empresa, solo se emite factura si el producto ya fue elaborado y entregado al cliente</li>
				</ul>
				<li>Garantias</li>
				<ul>
					<li>Vigentes de Materia Prima</li>
					<li>En proceso de devolución o cambio de materia prima</li>
					<li>Vencidas de Materia Prima</li>
					<li>Vigentes de Productos</li>
					<li>En proceso de devolución o cambio de producto/s</li>
					<li>Vencidas de productos</li>
				</ul>
				<li>Devoluciones e intercambio de mercancias</li>
				<ul>
					<li>Devoluciones</li>
					<li>Intercambios</li>
				</ul>
			</ul>
			<p><b>Otras opciones </b></p>
			<ul>
				<li>Inventario</li>
				<li><a href="{{ route('closeSession') }}">Cerrar Sesión</a></li>
			</ul>

		</section>
		<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
		<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
		<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}" ></script>
		<script src="{{ asset('plugins/adminLTE/js/adminlte.min.js') }}"></script>
    </body>
</html>
