@extends('dashboard._layout.main')

@section('title', $data['titleForm'])
@section('title-p', $data['titleForm'])

@section('links')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/general-from.css') }}">
@endsection

@section('body')
    <div id="sys-errors" class="col-12 mb-5">
        @include('sys.errors')
    </div>

    <?php $table_template = true; ?>

	@if( !isset($noHayRegistro) )
		@if( isset($view) )
	    	@include($view)
		@else
			@include('dashboard._api.forms.form.index')
		@endif
	@else
		@include('dashboard._api.forms.noHayRegistro')
	@endif

	@if( $data['relations'] === true )
		<?php $related_options = $data['Relacionado']; ?>
		@include('dashboard._layout.other_option')
	@endif

@endsection

@section('scripts')
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}" ></script>
    <script src="{{ asset('js/clipboard.js') }}"></script>
	<script src="{{ asset('js/ajax/ajax-input-search-table.js') }}" ></script>
	<script src="{{ asset('js/ajax/form-general.js') }}"></script>
    <script src="{{ asset('js/table-consult.js') }}"></script>
@endsection
