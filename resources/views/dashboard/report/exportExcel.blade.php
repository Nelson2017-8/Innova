<!DOCTYPE html>
<html>
<head>
	<title>Export Excel</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/report-view-pdf.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontisto/css/fontisto.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/adminLTE/css/adminlte.min.css') }}">
</head>
<body>
	<h1>Reporte de {{ $title }} </h1>

	<?php $pag_links = true; ?>
	@include($view)
	@yield('table-'.$table)

</body>
</html>