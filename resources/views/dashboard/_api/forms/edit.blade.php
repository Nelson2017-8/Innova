@extends('dashboard._layout.main')

@section('title', $title)
@section('title-p', $title)

@section('links')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/general-from.css') }}">
@endsection

@section('body')
    <div id="sys-errors" class="col-12 mb-5">
        @include('sys.errors')
    </div>
    @include('dashboard._api.forms.form.create-edit')
	@if( $data['relations'] === true )
		<?php $related_options = $data['Relacionado']; ?>
		@include('dashboard._layout.other_option')
	@endif
@endsection

@section('scripts')
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}" ></script>
	<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}" ></script>
	<script src="{{ asset('js/validations/create/'.$tabla.'.js') }}" ></script>
	<script src="{{ asset('js/ajax/form-general.js') }}" ></script>
@endsection
