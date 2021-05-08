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
				data-url="{{ ( $data['edit'] === true ) ? route($nameTable.'.edit', $tabla[$i]->id) : '' }}"
				data-detroy="{{ ( $data['delete'] === true ) ? route($nameTable.'.destroy', $tabla[$i]->id) : '' }}">

				<td style="padding: 1em;" title="Editar Registro" oncontextmenu="menuContextualInput (event, this)">{{ $tabla[$i]->incremental + 1 }}</td>
				@foreach($fields as $key => $item)
					<td title="Editar Registro" oncontextmenu="menuContextualInput (event, this)">
						<?php
//						dd($fields);
						$x = $fields[$key];
						if ($tabla[$i]->$item === NULL){
							echo '';
						}else{
							echo $tabla[$i]->$x;
						}
						?>
					</td>
				@endforeach
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
