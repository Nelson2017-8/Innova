<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Panel de Control')</title>
        <link rel="shortcut icon" type="image/ico" href="{{ $_SESSION['data']['favicon'] }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontisto/css/fontisto.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/adminLTE/css/adminlte.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/dashboard.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        @yield('links')
    </head>
    <body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-purple navbar-dark">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="{{ route('dashboard.index') }}" class="nav-link">Inicio</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="#" class="nav-link">Soporte Técnico</a>
				</li>
			</ul>

			<!-- SEARCH FORM -->
			<form class="form-inline ml-3">
				<div class="input-group input-group-sm">
					<input class="form-control form-control-navbar" type="search" placeholder="Buscar" aria-label="Search">
					<div class="input-group-append">
						<button class="btn btn-navbar" type="submit">
							<i class="fas fa-search"></i>
						</button>
					</div>
				</div>
			</form>

			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">
				<!-- Messages Dropdown Menu -->
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="far fa-comments"></i>
						<span class="badge badge-danger navbar-badge">3</span>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<a href="#" class="dropdown-item">
							<!-- Message Start -->
							<div class="media">
								<img src="{{ asset('img/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
								<div class="media-body">
									<h3 class="dropdown-item-title">
										Brad Diesel
										<span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
									</h3>
									<p class="text-sm">Call me whenever you can...</p>
									<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
								</div>
							</div>
							<!-- Message End -->
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<!-- Message Start -->
							<div class="media">
								<img src="{{ asset('img/user8-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
								<div class="media-body">
									<h3 class="dropdown-item-title">
										John Pierce
										<span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
									</h3>
									<p class="text-sm">I got your message bro</p>
									<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
								</div>
							</div>
							<!-- Message End -->
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<!-- Message Start -->
							<div class="media">
								<img src="{{ asset('img/user3-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
								<div class="media-body">
									<h3 class="dropdown-item-title">
										Nora Silvester
										<span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
									</h3>
									<p class="text-sm">The subject goes here</p>
									<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
								</div>
							</div>
							<!-- Message End -->
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
					</div>
				</li>
				<!-- Notifications Dropdown Menu -->
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="far fa-bell"></i>
						<span class="badge badge-warning navbar-badge">15</span>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<span class="dropdown-item dropdown-header">15 Notifications</span>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-envelope mr-2"></i> 4 new messages
							<span class="float-right text-muted text-sm">3 mins</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-users mr-2"></i> 8 friend requests
							<span class="float-right text-muted text-sm">12 hours</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-file mr-2"></i> 3 new reports
							<span class="float-right text-muted text-sm">2 days</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-widget="fullscreen" href="#" role="button">
						<i class="fas fa-expand-arrows-alt"></i>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
						<i class="fas fa-th-large"></i>
					</a>
				</li>
			</ul>
		</nav>
		<!-- /.navbar -->
		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-light-purple elevation-4">
			<!-- Brand Logo -->
			<a href="{{ route('dashboard.index') }}" class="brand-link">
				<img src="{{ $_SESSION['data']['favicon'] }}" alt="{{ $_SESSION['data']['title-all'] }}" class="brand-image favicon" style="display: none;">
				<img src="{{ $_SESSION['data']['logo'] }}" alt="{{ $_SESSION['data']['title-all'] }}" class="brand-image logo">
				<span class="brand-text font-weight-light"><strong>{{ $_SESSION['data']['title'] }}</strong></span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar user (optional) -->
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						{{-- <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">--}}
						<span class="fi fi-person bg-dark fi-border ellipse" style="font-size: 1.2em;"></span>
					</div>
					<div class="info text-center">
						<a href="#" class="d-block pt-1 pl-2">
							<strong>Usuario: </strong>
							{{ ucfirst($_SESSION['username']) }}
						</a>
					</div>
				</div>

				<!-- SidebarSearch Form -->
				<div class="form-inline">
					<div class="input-group" data-widget="sidebar-search">
						<input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Search">
						<div class="input-group-append">
							<button class="btn btn-sidebar">
								<i class="fas fa-search fa-fw"></i>
							</button>
						</div>
					</div>
					<div class="sidebar-search-results">
						<div class="list-group">
							<a href="#" class="list-group-item">
								<div class="search-title"></div>
								<div class="search-path"></div>
							</a>
						</div>
					</div>
				</div>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul id="sidebar-menu" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
						<!-- Add icons to the links using the .nav-icon class
							 with font-awesome or any other icon font library -->
{{--						--}}
{{--						<li class="nav-item menu-is-opening menu-open">--}}
{{--							<a href="#" class="nav-link active">--}}
{{--								<i class="nav-icon fas fa-tachometer-alt"></i>--}}
{{--								<p>--}}
{{--									Dashboard--}}
{{--									<i class="right fas fa-angle-left"></i>--}}
{{--								</p>--}}
{{--							</a>--}}
{{--							<ul class="nav nav-treeview">--}}
{{--								<li class="nav-item">--}}
{{--									<a href="../../index.html" class="nav-link">--}}
{{--										<i class="far fa-circle nav-icon"></i>--}}
{{--										<p>Dashboard v1</p>--}}
{{--									</a>--}}
{{--								</li>--}}
{{--								<li class="nav-item">--}}
{{--									<a href="../../index2.html" class="nav-link">--}}
{{--										<i class="far fa-circle nav-icon"></i>--}}
{{--										<p>Dashboard v2</p>--}}
{{--									</a>--}}
{{--								</li>--}}
{{--								<li class="nav-item">--}}
{{--									<a href="../../index3.html" class="nav-link">--}}
{{--										<i class="far fa-circle nav-icon"></i>--}}
{{--										<p>Dashboard v3</p>--}}
{{--									</a>--}}
{{--								</li>--}}
{{--							</ul>--}}
{{--						</li>--}}
{{--						<li class="nav-item">--}}
{{--							<a href="../widgets.html" class="nav-link">--}}
{{--								<i class="nav-icon fas fa-th"></i>--}}
{{--								<p>--}}
{{--									Widgets--}}
{{--									<span class="right badge badge-danger">New</span>--}}
{{--								</p>--}}
{{--							</a>--}}
{{--						</li>--}}
{{--						--}}

						<li class="nav-item">
							<a href="{{ route('dashboard.index') }}" class="nav-link">
								<i class="nav-icon fas fa-tachometer-alt"></i>
								<p>Panel de Control</p>
							</a>
						</li>
						<?php
							// $rutaActual = 'https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
							$rutaActual = \Illuminate\Support\Facades\URL::current();
							$rutas = [
								0 => [
									'ico' => 'fas fa-users',
									'route' => '#',
									'name' => 'Usuarios',
									'routes' => [
										0 => [
											'ico' => 'fas fa-clipboard-list',
											'route' => route('users.index'),
											'name' => 'Mostrar',
										],
										1 => [
											'ico' => 'fas fa-edit',
											'route' => route('users.create'),
											'name' => 'Crear',
										],
									]
								],
								1 => [
									'ico' => 'fas fa-user-alt',
									'route' => '#',
									'name' => 'Clientes',
									'routes' => [
										0 => [
											'ico' => 'fas fa-clipboard-list',
											'route' => route('clientes.index'),
											'name' => 'Mostrar',
										],
										1 => [
											'ico' => 'fas fa-edit',
											'route' => route('clientes.create'),
											'name' => 'Crear',
										],
									]
								],
								2 => [
									'ico' => 'fas fa-user-tie',
									'route' => '#',
									'name' => 'Proveedores',
									'routes' => [
										0 => [
											'ico' => 'fas fa-clipboard-list',
											'route' => route('proveedores.index'),
											'name' => 'Mostrar',
										],
										1 => [
											'ico' => 'fas fa-edit',
											'route' => route('proveedores.create'),
											'name' => 'Crear',
										],
									]
								],
								3 => [
									'ico' => 'fas fa-code-branch',
									'route' => '#',
									'name' => 'Sucursal',
									'routes' => [
										0 => [
											'ico' => 'fas fa-clipboard-list',
											'route' => route('sucursal.index'),
											'name' => 'Mostrar',
										],
										1 => [
											'ico' => 'fas fa-edit',
											'route' => route('sucursal.create'),
											'name' => 'Crear',
										],
									]
								],
								4 => [
									'ico' => 'fas fa-warehouse',
									'route' => '#',
									'name' => 'Almacen',
									'routes' => [
										0 => [
											'ico' => 'fas fa-clipboard-list',
											'route' => route('almacen.index'),
											'name' => 'Mostrar',
										],
										1 => [
											'ico' => 'fas fa-edit',
											'route' => route('almacen.create'),
											'name' => 'Crear',
										],
									]
								],
								5 => [
									'ico' => 'fas fa-box',
									'route' => '#',
									'name' => 'Categoria',
									'routes' => [
										0 => [
											'ico' => 'fas fa-clipboard-list',
											'route' => route('categoria.index'),
											'name' => 'Mostrar',
										],
										1 => [
											'ico' => 'fas fa-edit',
											'route' => route('categoria.create'),
											'name' => 'Crear',
										],
									]
								],
								6 => [
									'ico' => 'fas fa-box',
									'route' => '#',
									'name' => 'Subcategoria',
									'routes' => [
										0 => [
											'ico' => 'fas fa-clipboard-list',
											'route' => route('subcategoria.index'),
											'name' => 'Mostrar',
										],
										1 => [
											'ico' => 'fas fa-edit',
											'route' => route('subcategoria.create'),
											'name' => 'Crear',
										],
									]
								],
								/**6 => [
									'ico' => 'fas fa-sitemap',
									'route' => '#',
									'name' => 'Inventario',
									'routes' => [
										0 => [
											'ico' => 'fas fa-clipboard-list',
											'route' => route('subcategoria.index'),
											'name' => 'Mostrar',
										],
										1 => [
											'ico' => 'fas fa-edit',
											'route' => route('subcategoria.create'),
											'name' => 'Crear',
										],
									]
								],**/
								7 => [
									'ico' => 'fas fa-weight-hanging',
									'route' => '#',
									'name' => 'Compras',
									'routes' => [
										0 => [
											'ico' => 'fas fa-clipboard-list',
											'route' => route('compras.index'),
											'name' => 'Mostrar',
										],
										1 => [
											'ico' => 'fas fa-edit',
											'route' => route('compras.create'),
											'name' => 'Crear',
										],
									]
								],

							];
							if (!isset($dropdown)){
								$dropdown = '';
							}
						?>
						@foreach($rutas as $ruta)
							<li data-padre="true" class="nav-item {{ ($ruta['route'] === $rutaActual || $ruta['name'] === $dropdown ) ? 'menu-is-opening menu-open' : '' }}">
								<a href="{{ $ruta['route'] }}" class="nav-link {{ ($ruta['route'] === $rutaActual || $ruta['name'] === $dropdown ) ? 'active' : '' }}">
									<i class="nav-icon {{ $ruta['ico'] }}"></i>
									<p>
                                        {{ $ruta['name'] }}
										<i class="right fas fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
								@foreach($ruta['routes'] as $r)
									<li class="pl-2 nav-item">
										<a href="{{ $r['route'] }}" class="nav-link {{ ($r['route'] === $rutaActual) ? 'event-active' : '' }}">
											<i class="{{ $r['ico'] }} nav-icon"></i>
											<p>{{ $r['name'] }}</p>
										</a>
									</li>
								@endforeach
								</ul>
							</li>
						@endforeach

						<li class="nav-header"><strong>Versión: </strong>1.0</li>

						<li class="nav-item">
							<a href="{{ route('closeSession') }}" class="nav-link">
								<i class="nav-icon fas fa-power-off"></i>
								<p>Cerrar Sesión</p>
							</a>
						</li>

					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper" style="min-height: 1403.62px;">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1>@yield('title-p')</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								@if( isset($migas_de_pan) )
									<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Panel de Control</a></li>
									@foreach($migas_de_pan as $miga)

										@if( $miga['route'] == '' )
											<li class="breadcrumb-item active">{{ $miga['name'] }}</li>
										@else
											<li class="breadcrumb-item"><a href="{{ $miga['route'] }}">{{ $miga['name'] }}</a></li>
										@endif

									@endforeach
								@else
									<li class="breadcrumb-item">Panel de Control</li>
								@endif
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<div class="row">
						<!-- left column -->
						<div class="col-md-12">
							@yield('body')
						</div>
						<!--/.col (left) -->
					</div>
					<!-- /.row -->
				</div><!-- /.container-fluid -->
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
		<footer class="main-footer">
			<div class="float-right d-none d-sm-block">
				<span class="small"><strong>Desarrollado por: </strong><a href="">{{ $_SESSION['data']['developers'][0]['name'] }}</a> y
				<a href="">{{ $_SESSION['data']['developers'][1]['name'] }}</a></span>

			</div>
			<span class="small">
				<strong>Copyright © {{ \Carbon\Carbon::now()->format('Y') }} <a href="{{ route('dashboard.index') }}">{{ $_SESSION['data']['title-all'] }}</a></strong> Todos los derechos reservados
			</span>
		</footer>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
			<div class="p-3 control-sidebar-content"><h5>Customize AdminLTE</h5><hr class="mb-2"><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>No Navbar border</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Body small text</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Navbar small text</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Sidebar nav small text</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Footer small text</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Sidebar nav flat style</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Sidebar nav legacy style</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Sidebar nav compact</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Sidebar nav child indent</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Sidebar nav child hide on collapse</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Main Sidebar disable hover/focus auto expand</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Brand small text</span></div><div class="mb-4"><input type="checkbox" value="1" class="mr-1"><span>Dark Mode</span></div><h6>Navbar Variants</h6><div class="d-flex"><div class="d-flex flex-wrap mb-3"><div class="bg-primary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-secondary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-info elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-success elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-danger elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-indigo elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-purple elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-pink elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-navy elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-lightblue elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-teal elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-cyan elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-dark elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-gray-dark elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-gray elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-light elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-warning elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-white elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-orange elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div></div></div><h6>Accent Color Variants</h6><div class="d-flex"></div><div class="d-flex flex-wrap mb-3"><div class="bg-primary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-warning elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-info elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-danger elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-success elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-indigo elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-lightblue elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-navy elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-purple elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-fuchsia elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-pink elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-maroon elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-orange elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-lime elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-teal elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-olive elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div></div><h6>Dark Sidebar Variants</h6><div class="d-flex"></div><div class="d-flex flex-wrap mb-3"><div class="bg-primary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-warning elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-info elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-danger elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-success elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-indigo elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-lightblue elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-navy elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-purple elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-fuchsia elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-pink elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-maroon elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-orange elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-lime elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-teal elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-olive elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div></div><h6>Light Sidebar Variants</h6><div class="d-flex"></div><div class="d-flex flex-wrap mb-3"><div class="bg-primary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-warning elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-info elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-danger elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-success elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-indigo elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-lightblue elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-navy elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-purple elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-fuchsia elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-pink elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-maroon elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-orange elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-lime elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-teal elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-olive elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div></div><h6>Brand Logo Variants</h6><div class="d-flex"></div><div class="d-flex flex-wrap mb-3"><div class="bg-primary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-secondary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-info elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-success elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-danger elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-indigo elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-purple elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-pink elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-navy elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-lightblue elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-teal elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-cyan elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-dark elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-gray-dark elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-gray elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-light elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-warning elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-white elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-orange elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><a href="#">clear</a></div></div></aside>
		<!-- /.control-sidebar -->
		<div id="sidebar-overlay"></div></div>


        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}" ></script>
        <script src="{{ asset('plugins/adminLTE/js/adminlte.min.js') }}"></script>
        <script src="{{ asset('js/dashboard.js') }}"></script>
		<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
		<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
		<script src="{{ asset('js/ajax/generate-pdf.js') }}"></script>
        @yield('scripts')
    </body>
</html>
