@extends('dashboard._layout.main')

@section('title', 'Registrar compra paso 2')
@section('title-p', 'Registrar compra paso 2')

@section('links')
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/toastr/toastr.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/general-from.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/articulos-create.css') }}">
@endsection

@section('body')
	<div id="sys-errors" class="col-12 mb-5">
		@include('sys.errors')
	</div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<!-- left column -->
				<form id="articulos" class="ajax-send" action="{{ route('compras.articulos.store') }}" method="POST">
					@csrf
					<input type="hidden" name="numFactura" value="{{ $_REQUEST['numFactura'] }}">
					<div class="col-sm-12 p-0">
						<table id="articles" class="table table-hover table-bordered table-striped small">
							<thead>
								<tr>
									<th>ID</th>
									<th>Nombre <span class="text-danger">(*)</span></th>
									<th width="120">Cantidad <span class="text-danger">(*)</span></th>
									<th>($) Precio Unitario <span class="text-danger">(*)</span></th>
									<th>Subcategoria <span class="text-danger">(*)</span></th>
									<th width="150">Código de Barras</th>
									<th>Detalles</th>
									<th>Opciones</th>
								</tr>
							</thead>
							<tbody>
								<tr id="item-tab-1">
									<td class="item-id">1</td>
									<td><input type="text" class="form-control form-control-sm" placeholder="Nombre" name="nombre[]" required></td>
									<td><input type="number" class="form-control form-control-sm" placeholder="Cantidad" name="cantidad[]" required></td>
									<td><input type="text" class="form-control form-control-sm" placeholder="En Dólar" name="precio[]"></td>
									<td>
										<select name="subcategoria[]" class="custom-select custom-select-sm">
											<option value="">SELECCIONE UNA OPCIÓN</option>
											@foreach($subcategorias as $subcategoria)
												<option value="{{ $subcategoria->id }}">{{ $subcategoria->nombre }}</option>
											@endforeach
										</select>
									</td>
									<td><input type="text" class="form-control form-control-sm" placeholder="Código de Barras" name="codBarras[]"></td>
									<td><textarea name="detalles[]" class="form-control form-control-sm" placeholder="Detalles" cols="20" rows="1"></textarea></td>
									<td>
										<div class="btn-group btn-group-sm">
											<button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="sr-only">Toggle Dropdown</span>
											</button>
											<button class="btn btn-success" type="button">Acción</button>
											<div class="dropdown-menu menu-content-tab">
												<a href="#" onclick="DeleteItem (this)" class="dropdown-item small"><span class="pr-2 fas fa-trash"></span>Eliminar</a>
												<a href="#" onclick="AddItem (this)" class="dropdown-item small"><span class="pr-2 fas fa-plus"></span>Agregar</a>
												<a href="#" class="dropdown-item small"><span class="pr-2 fas fa-save"></span>Guardar</a>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-sm-12 p-0">
						<button type="submit" class="btn btn-outline-primary"><span class="pr-2 fas fa-paper-plane"></span>Guardar y Enviar</button>
					</div>
				</form>
			</div>
			<!-- /.row -->
		</div><!-- /.container-fluid -->
	</section>


	<section class="btn-floating">
		<ul class="btn-floating-container">
			<li>
				<a title="Eliminar el último elemento " data-toggle="tooltip" data-placement="right" class="btn-circular btn btn-danger btn-remove-last-item" href="#"><span class="fas fa-trash"></span></a>
			</li>
			<li>
				<a title="Agregar Elemento " data-toggle="tooltip" data-placement="right" class="btn-circular btn btn-primary btn-add-last-item" href="#"><span class="fas fa-plus"></span></a>
			</li>
			<li>
				<a title="Guardar todo " data-toggle="tooltip" data-placement="right" href="#" class="btn-circular btn btn-success btn-save"><span class="fas fa-save"></span>Guardar</a>
			</li>
		</ul>
		<a class="btn-circular btn btn-dark" href="#"><span class="fas fa-chevron-up"></span></a>
	</section>

@endsection

@section('scripts')
	<script src="{{ asset('plugins/toastr/toastr.min.js') }}" ></script>
	<script src="{{ asset('js/ajax/form-general.js') }}"></script>
	<script src="{{ asset('js/articulos-create.js') }}"></script>
@endsection
