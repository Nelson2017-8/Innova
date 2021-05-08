<?php
$titleCard = $data['Card']['titleCard']; // EL TITULO DE LA TARJETA
$icoCard = $data['Card']['icoCard']; // ICONO DE LA TARJETA
$nameTable = $data['nameTable']; // TABLA DEL MYSQL
$routePath = $data['routePath'];
$fields = $tabla[0]->getFillable();


// SI DESEAMOS ELIMINAR LA PAGINACION, CREAMOS UNA VARIABLE LLAMADA pag_links
if ( !isset($pag_links) ) {
	$paginate_link = $tabla->links();
	$pagination = [
		'total_result' => $tabla->links()->getData()['paginator']->total(),
		'page_actual' => $tabla->links()->getData()['paginator']->currentPage(),
		'page_total' => $tabla->links()->getData()['paginator']->lastPage() ,

	];
}else{
	$paginate_link = '';
}

// RUTA DE ANADIR REGISTRO
if ( $data['linkRegistro'] === true  ) {
	$add_register = route( $nameTable.'.create');
}else{
	$add_register = '';
}

// INPUT AUTOCOMPLETE AJAX
if ( $data['autocompleteAjax'] === true  ) {
	$dataUrl = route($nameTable.'.index');
	$dataUrlInputs = url('/search/t/'.$nameTable.'/inputs');
}else{
	$dataUrl = '';
	$dataUrlInputs = '';
}
$edit = '';

?>
@extends('dashboard._layout.table_template')

@section('table-'.$nameTable)
	<div class="dropdown menuContextualDropdown">
		<div class="dropdown-menu py-1">
			<a class="dropdown-item details" href="#">Ver Detalles</a>
			<a class="dropdown-item copy" href="#">Copiar</a>
			<a class="dropdown-item destroy" href="#">Eliminar</a>
			<a class="dropdown-item edit" href="#">Editar</a>
			<div class="dropdown-divider m-0" style="border-color: #dee2e6;"></div>
			<a class="dropdown-item edit-window" href="#">Editar en una ventana</a>
		</div>
	</div>
	@include('tablas.'.$data['pathTabla'])
@endsection

