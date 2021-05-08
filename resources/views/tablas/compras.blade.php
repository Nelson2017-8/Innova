<div class="table-response">
	<table id="{{ $nameTable }}-index" class="table table-bordered table-hover table-striped table-general">
		<thead>
		<tr>
			<th style="padding: 1em;">ID</th>
			@foreach($columns as $cols)
				<th>{{ $cols }}</th>
			@endforeach
			@if( $data['delete'] === true )
				<th class="delete-item-table">Eliminar</th>
			@endif
		</tr>
		</thead>
		<tbody>
		@for($i=0; $i < count($tabla); $i++)
			<tr class="item-table"
				data-show="{{ route($nameTable.'.show', $tabla[$i]->id) }}"
				data-url="{{ ( $data['edit'] === true ) ? route($nameTable.'.edit', $tabla[$i]->id) : '' }}"
				data-detroy="{{ ( $data['delete'] === true ) ? route($nameTable.'.destroy', $tabla[$i]->id) : '' }}">

				<td style="padding: 1em;" title="Editar Registro" oncontextmenu="menuContextualInput (event, this)">{{ $tabla[$i]->incremental + 1 }}</td>
				<td title="Editar Registro" oncontextmenu="menuContextualInput (event, this)">{{ $tabla[$i]->proveedor->razonSocial }}</td>
				<td title="Editar Registro" oncontextmenu="menuContextualInput (event, this)">{{ $tabla[$i]->numFactura }}</td>
				<td title="Editar Registro" oncontextmenu="menuContextualInput (event, this)">{{ $tabla[$i]->precioCompra }}$</td>
				<td title="Editar Registro" oncontextmenu="menuContextualInput (event, this)"><?php
						if ( !is_null( $tabla[$i]->limitGarantia ) ){
							$diferencia = \Carbon\Carbon::now()->between( $tabla[$i]->limitGarantia , $tabla[$i]->fechaCompra );
							$fecha = \Carbon\Carbon::parse( $tabla[$i]->limitGarantia )->format('d/m/Y');
							if ( $diferencia ){
								echo $fecha.' <span class="ml-1 badge badge-success">Disponible</span>';
							}else{
								echo $fecha.' <span class="ml-1 badge badge-danger">Expirado</span>';
							}
						}else{
							echo '';
						}
					?></td>
				<td title="Editar Registro" oncontextmenu="menuContextualInput (event, this)">{{ \Carbon\Carbon::parse( $tabla[$i]->fechaCompra )->format('d/m/Y') }}</td>

				@if(isset($table_template))
					@if( $data['delete'] === true )
						<th class="delete-item-table">
							<a class="delete-item" href="{{ route($nameTable.'.destroy', $tabla[$i]->id) }}"><i class="fi fi-trash pl-4"></i></a>
						</th>
					@endif
				@endif
			</tr>
		@endfor
		</tbody>
	</table>
</div>
