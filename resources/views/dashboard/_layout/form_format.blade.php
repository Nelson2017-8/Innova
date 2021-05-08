<?php

	/**

	$forms = [
		'action' => route('dashboard.category.create'),
		'data-update' => route('dashboard.category.query' ),
		'method' => 'POST',
		'select' => [
			0 => [
				'name' => 'select-name-1',
				'id' => 'select-name-1',
				'class' => 'class',
				'col' => 'col-sm-6',
				'title' => 'Selecciones una opción',
				'option' => [
					'valor1' => 'option1',
					'valor2' => 'option2',
					'valor3' => 'option3',
				],
			],
			1 => [
				'name' => 'select-name-2',
				'id' => 'select-name-2',
				'class' => 'class',
				'col' => 'col-sm-6',
				'title' => 'Selecciones una opción',
				'optionArray' => true,
				'option' => [
					'option1',
					'option2',
					'option3',
				],
				'attr' => [
					'require' => '',
				],
			],
		],
		'btn' => [
			'title' => 'Registrar',
		],
		'input' => [
			0 => [
				'name' => 'input-name-1',
				'id' => 'input-name-1',
				'col' => 'col-sm-6',
				'class' => 'class',
				'title' => 'Nombre',
				'attr' => [
					'require' => '',
					'placeholder' => 'Nombre completo',
				],
			],
			1 => [
				'name' => 'input-name-2',
				'id' => 'input-name-2',
				'col' => 'col-sm-6',
				'class' => 'class',
				'title' => 'Apellido',
				'attr' => [
					'require' => '',
				],
			],
			2 => [
				'name' => 'input-name-3',
				'title' => 'Sexo',
				'id' => 'input-name-3',
				'class' => 'class',
			],
			3 => [
				'name' => 'input-name-4',
				'type' => 'email',
				'title' => 'Correo',
				'id' => 'input-name-4',
				'attr' => [
					'require' => '',
					'placeholder' => 'Correo@gmail.com',
				],
			],
		],
		'textarea' => [
			0 => [
				'name' => 'text-name-1',
				'id' => 'text-name-1',
				'class' => 'class',
				'title' => 'Dirección',
			],
		],
	];

	**/

	function issetVal($forms, $campo, $val)
	{
		$a = explode(' ', $campo)[0];

		if (isset(explode(' ', $campo)[1])) {
			$b = explode(' ', $campo)[1];
			if(isset( $forms[$a][$b] )){
				return $forms[$a][$b];
			}
			else{
				return $val;
			}
		}else{
			if(isset( $forms[$campo] )){
				return $forms[$campo];
			}
			else{
				return $val;
			}
		}
	}

	$forms['action'] = issetVal( $forms, 'action', '' );
	$forms['data-update'] = issetVal( $forms, 'data-update', '' );
	$forms['method'] = issetVal( $forms, 'method', 'GET' );
	$forms['class'] = issetVal( $forms, 'class', '' );
	$forms['id'] = issetVal( $forms, 'id', 'form-'.random_int(10, 100));
	$forms['body'] = issetVal( $forms, 'body', 'card-body' );
	$forms['footer'] = issetVal( $forms, 'footer', 'card-footer' );
	$forms['btn']['type'] = issetVal( $forms, 'btn type', 'submit' );
	$forms['btn']['title'] = issetVal( $forms, 'btn title', 'Enviar' );
	$forms['btn']['class'] = issetVal( $forms, 'btn class', 'btn btn-primary px-3' );
	$forms['btn']['icon'] = issetVal( $forms, 'btn icon', '<i class="ml-2 fas fa-paper-plane"></i>' );
	$forms['btn']['id'] = issetVal( $forms, 'btn id', '' );

?>



<form  <?php echo !empty( $forms['data-update'] ) ? 'data-update="'.$forms['data-update'].'"' : ''; ?> action="{{ $forms['action'] }}" id="{{ $forms['id'] }}" class="ajax-send{{ ' '.$forms['class'] }}" method="{{ $forms['method'] }}" accept-charset="utf-8" @if(!empty($forms['file'])) enctype="multipart/form-data" @endif>
    {{ csrf_field() }}
    <div class="{{ $forms['body'].' ' }}row">

		@if(!empty($forms['input']))
			@foreach($forms['input'] as $input)
			<div class="form-group {{ ( !empty($input['col']) ) ? $input['col'] : 'col-sm-12' }}">
				<label for="{{ !empty( $input['id'] ) ? $input['id'] : '' }}">{{ $input['title'] }} @if( isset($input['attr']['require']) ) <span class="text-danger">(*)</span> @endif</label>
				<input type="{{ !empty( $input['type'] ) ? $input['type'] : 'text' }}" id="{{ !empty( $input['id'] ) ? $input['id'] : '' }}" class="form-control {{ !empty( $input['class'] ) ? $input['class'] : '' }}" name="{{ $input['name'] }}" @if( !empty($input['attr']) ) @foreach( $input['attr'] as $attr => $val) <?php echo $attr.'="'.$val.'"'; ?> @endforeach @endif >
			</div>
			@endforeach
		@endif

		@if(!empty($forms['select']))
			@foreach($forms['select'] as $select)
			<div class="form-group {{ ( !empty($select['col']) ) ? $select['col'] : 'col-sm-12' }}">
				<label for="{{ !empty( $select['id'] ) ? $select['id'] : '' }}">{{ $select['title'] }} @if( isset($select['attr']['require']) ) <span class="text-danger">(*)</span> @endif</label>
				<select name="{{ $select['name'] }}" class="custom-select {{ !empty( $select['class'] ) ? $select['class'] : '' }}" id="{{ !empty( $select['id'] ) ? $select['id'] : '' }}" {{ ( !empty($select['multiple']) ) ? 'multiple' : '' }} @if( !empty($select['attr']) ) @foreach( $select['attr'] as $attr => $val) <?php echo $attr.'="'.$val.'"'; ?> @endforeach @endif >
					@if(isset($select['option']))
						@if(isset($select['optionArray']))
							@foreach( $select['option'] as $option )
								<option value="{{ $option }}">{{ $option }}</option>
							@endforeach
						@else
							@foreach( $select['option'] as $value =>  $option )
								<option value="{{ $value }}">{{ $option }}</option>
							@endforeach
						@endif
					@endif
				</select>
			</div>
			@endforeach
		@endif

		@if(!empty($forms['textarea']))
			@foreach($forms['textarea'] as $textarea)
			<div class="form-group {{ ( !empty($textarea['col']) ) ? $textarea['col'] : 'col-sm-12' }}">
				<label for="{{ !empty( $textarea['id'] ) ? $textarea['id'] : '' }}">{{ $textarea['title'] }} @if( isset($textarea['attr']['require']) ) <span class="text-danger">(*)</span> @endif</label>
				<textarea id="{{ !empty( $textarea['id'] ) ? $textarea['id'] : '' }}" class="form-control {{ !empty( $textarea['class'] ) ? $textarea['class'] : '' }}" name="{{ $textarea['name'] }}" @if( !empty($textarea['attr']) ) @foreach( $textarea['attr'] as $attr => $val) <?php echo $attr.'="'.$val.'"'; ?> @endforeach @endif >{{ !empty( $textarea['value'] ) ? $textarea['value'] : '' }}</textarea>
			</div>
			@endforeach
		@endif

		@if(!empty($forms['file']))
			@foreach($forms['file'] as $file)
				<div class="form-group {{ ( !empty($file['col']) ) ? $file['col'] : 'col-sm-12' }}">
					<label for="{{ ( !empty($file['id']) ) ? $file['id'] : 'file-input' }}">{{ $file['title'] }}@if( isset($textarea['attr']['require']) ) <span class="text-danger">(*)</span> @endif</label>
					<div class="custom-file">
						<input type="file" class="custom-file-input {{ ( !empty($file['class']) ) ? $file['class'] : '' }}" id="{{ ( !empty($file['id']) ) ? $file['id'] : 'file-input' }}" @if( !empty($file['attr']) ) @foreach( $file['attr'] as $attr => $val) <?php echo $attr.'="'.$val.'"'; ?> @endforeach @endif>
						<label class="custom-file-label" for="{{ ( !empty($file['id']) ) ? $file['id'] : 'file-input' }}"></label>
					</div>
				</div>
			@endforeach
		@endif
    </div>


	<div class="{{ $forms['footer'] }}">
        <button id="{{ $forms['btn']['id'] }}" type="{{ $forms['btn']['type'] }}" class="{{ $forms['btn']['class'] }}">{{ $forms['btn']['title'] }} <?php echo  $forms['btn']['icon']; ?></button>
    </div>
</form>
