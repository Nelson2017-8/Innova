<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Panel de Control</title>
    </head>
    <body>
    	<h1>Panel de Control</h1>
        Hola bienvenido {{ $user['username'] }}

        @include('sys.errors')

		<br>
		<p><b>Datos del usuario</b></p>
		<ul>
			<li>ID: {{ $user['id'] }}</li>
			<li>Nombre Usuario: {{ $user['username'] }}</li>
			<li>Correo: {{ $user['email'] }}</li>
			<li>Tipo de usuario: {{ $user['typeuser'] }}</li>
			<li>Fecha de registro: {{ $user['date'] }}</li>
		</ul>

		<br>
		<p><b>Lista de opciones</b></p>
		<p>
			<ul>
				<li>Usuarios:</li>
				<ul>
					<li><a href="{{ route('dashboard.user.query', $user['id']) }}">Consultar</a></li>
					<li><a href="{{ route('dashboard.user.create', $user['id']) }}">Crear</a></li>
				</ul>
				<li>Clientes</li>
				<ul>
					<li><a href="{{ route('dashboard.customers.query') }}">Consultar</a></li>
					<li><a href="{{ route('dashboard.customers.view.create') }}">Crear</a></li>
				</ul>
				<li>Proveedores</li>
				<ul>
					<li><a href="{{ route('dashboard.suppliers.query') }}">Consultar</a></li>
					<li><a href="{{ route('dashboard.suppliers.view.create') }}">Crear</a></li>
				</ul>
				<li>Sucursal</li>
				<ul>
					<li><a href="{{ route('dashboard.branch.query') }}">Consultar</a></li>
					<li><a href="{{ route('dashboard.branch.view.create') }}">Crear</a></li>
				</ul>
				<li>Categoria</li>
				<ul>
					<li><a href="{{ route('dashboard.category.query') }}">Consultar</a></li>
					<li><a href="{{ route('dashboard.category.view.create') }}">Crear</a></li>
				</ul>
				<li>Sucategoria</li>
				<ul>
					<li><a href="{{ route('dashboard.subcategory.query') }}">Consultar</a></li>
					<li><a href="{{ route('dashboard.subcategory.view.create') }}">Crear</a></li>
				</ul>
				<li>Materia Prima</li>
				<ul>
					<li><a href="{{ route('dashboard.raw_material.query') }}">Consultar</a></li>
					<li><a href="{{ route('dashboard.raw_material.view.create') }}">Crear</a></li>
				</ul>
				<li>Almacen</li>
				<ul>
					<li><a href="{{ route('dashboard.warehouse.query') }}">Consultar</a></li>
					<li><a href="{{ route('dashboard.warehouse.view.create') }}">Crear</a></li>
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
					<li><a href="{{ route('dashboard.estimate.query') }}">Consultar</a></li>
					<li><a href="{{ route('dashboard.estimate.create') }}">Insertar</a></li>
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
		</p>
		<p><b>Otras opciones </b></p>
		<p>
			<ul>
				<li>Compra a Proveedores</li>
				<ul>
					<li><a href="{{ route('dashboard.suppliers.sale.query') }}">Consultar</a></li>
					<li><a href="{{ route('dashboard.suppliers.sale.view.create') }}">Crear</a></li>
					<li><a href="{{ route('dashboard.suppliers.sale.squery') }}">Buscar</a></li>
				</ul>
				<li>Inventario</li>
				<li><a href="{{ route('closeSession') }}">Cerrar Sesión</a></li>
			</ul>
		</p>

    </body>
</html>
