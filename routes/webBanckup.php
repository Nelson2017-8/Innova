<?php
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\ProveedoresExport;
use App\Exports\ClientesExport;
use App\Exports\VendedoresExport;
use App\Exports\SucusalesExport;
use App\Exports\CategoryExport;
use App\Exports\SubcategoryExport;
use App\Exports\MateriaPrimaExport;
use App\Exports\AlmacenesExports;

// MÉTODO GET: INCIO SESION - PAGINA PRINCIPAL
Route::get('/', 'UserController@getLogin')->name('home');

// MÉTODO GET: INCIO SESION
Route::get('/login', 'UserController@getLogin')->name('login');

// MÉTODO GET: REGISTRAR USUARIO
Route::get('/register', 'UserController@getRegister')->name('register');

// MÉTODO GET: RECUPERAR CONTRASEÑA
Route::get('/password_reset', 'UserController@getPassReset')->name('password_reset');

// CERRAR SESION
Route::get('/outLogin', function () {
	if ( !isset($_SESSION) ) {
		session_start();
	}
	session_destroy();
	session_unset();
    return redirect()->route('login');
})->name('closeSession');

// MÉTODO POST: INCIO SESION
Route::post('/login', 'UserController@login')->name('nLogin');
// MÉTODO POST: REGISTRAR USUARIO
Route::post('/register', 'UserController@register')->name('nRegister');
// MÉTODOS POSTS: RECUPERAR CONTRASEÑA
Route::post('/forgotten', 'UserController@forgotten')->name('nPasswordreset');
Route::get('/password_reset/t/{token}', 'UserController@passwordReset')->name('rPasswordreset');
Route::post('/password_reset/v/save', 'UserController@saveNewPassword')->name('saveNewPassword');
Route::get('/password_reset/v/save/err', 'UserController@errSaveNewPassword')->name('errSaveNewPassword');

// INFO DE LOS DESARROLLADORES
Route::get('/developers', function () {
	return 'DESARROLLADORES';
});

// BUSQUEDAS
Route::group(['prefix' => '/search/json'], function () {
	Route::get('/vendedores', 'DatajsonController@sellers')->name('api.json.sellers.all');
	Route::get('/clientes', 'DatajsonController@customers')->name('api.json.customers.all');
	Route::get('/materia_prima', 'DatajsonController@rawMaterial')->name('api.json.rawMaterial.all');
	Route::get('/categoria', 'DatajsonController@category')->name('api.json.category.all');
	Route::get('/subcategoria', 'DatajsonController@subcategory')->name('api.json.subcategory.all');
	Route::get('/proveedores', 'DatajsonController@suppliers')->name('api.json.suppliers.all');
	Route::get('/users', 'DatajsonController@ssers')->name('api.json.usersall');
	Route::get('/branch', 'DatajsonController@branch')->name('api.json.branch.all');
	Route::get('/warehouse', 'DatajsonController@warehouse')->name('api.json.warehouse.all');
});
Route::get('/s/json/clientes', 'DatajsonController@clientJson')->name('api.json.clientes');

// GENERAR ARCHIVO EXCEL
Route::group(['prefix' => '/export/excel'], function () {
	// users -> TABLE DEL MYSQL
	Route::get('/users', function () {
		$name = 'report-users-'.date('d-m-Y h:s:i');
		return Excel::download(new UsersExport, $name.'.xlsx');
	});

	Route::get('/clientes', function () {
		$name = 'report-clientes-'.date('d-m-Y h:s:i');
		return Excel::download(new ClientesExport, $name.'.xlsx');
	});

	Route::get('/proveedores', function () {
		$name = 'report-proveedores-'.date('d-m-Y h:s:i');
		return Excel::download(new ProveedoresExport, $name.'.xlsx');
	});

	Route::get('/vendedores', function () {
		$name = 'report-vendedores-'.date('d-m-Y h:s:i');
		return Excel::download(new VendedoresExport, $name.'.xlsx');
	});
	Route::get('/sucursal', function () {
		$name = 'report-sucursales-'.date('d-m-Y h:s:i');
		return Excel::download(new SucusalesExport, $name.'.xlsx');
	});
	Route::get('/categoria', function () {
		$name = 'report-sucursales-'.date('d-m-Y h:s:i');
		return Excel::download(new SucusalesExport, $name.'.xlsx');
	});
	Route::get('/subcategoria', function () {
		$name = 'report-sucursales-'.date('d-m-Y h:s:i');
		return Excel::download(new SucusalesExport, $name.'.xlsx');
	});
	Route::get('/materia_prima', function () {
		$name = 'report-material-prima-'.date('d-m-Y h:s:i');
		return Excel::download(new SucusalesExport, $name.'.xlsx');
	});

	Route::get('/almacen', function () {
		$name = 'report-almacen-'.date('d-m-Y h:s:i');
		return Excel::download(new AlmacenesExports, $name.'.xlsx');
	});
});

// GENERAR VISTA PREVIA PARA IMPRIMIR
Route::get('/preview/print/{table}', 'ExportFileController@print')->name('export-print');

// GENERAR VISTA DE PDF
Route::get('/view/table/{table}', 'ExportFileController@view')->name('export-pdf');
Route::get('/info', function (){
	phpinfo();
});

Route::group(['prefix' => '/panelControl'], function () {
});

// RUTAS: PANEL DE CONTROL
Route::group(['prefix' => '/dashboard'], function () {
	// PAGINA PRINCIPAL
	Route::get('/', 'DashboardController@index')->name('dashboard.index');

	// GRUPO DE USUARIOS: OPERACIONES CRUD DE LOS USUARIOS
	Route::group(['prefix' => '/users'], function () {

		// CONSULTAR USUARIOS: TODO TIPO DE USUARIO
		Route::get('/{user}/query', 'DashboardUserController@show')->name('dashboard.user.query');

		// CREAR USUARIO NUEVO: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/{user}/create', 'DashboardUserController@index')->name('dashboard.user.create');
		Route::post('/{user}/create', 'DashboardUserController@store')->name('dashboard.user.create');

		// ELIMINAR: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/detele/{id}', 'DashboardUserController@destroy')->name('dashboard.user.delete');

		// ACTUALIZAR: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/{id}/update', 'DashboardUserController@viewUpdate')->name('dashboard.user.view.update');
		Route::post('/{id}/update', 'DashboardUserController@update')->name('dashboard.user.update');
	});

	// GRUPO DE CLIENTES: OPERACIONES CRUD DE LOS CLIENTES (CUSTOMERS)
	Route::group(['prefix' => '/customers'], function () {

		// CONSULTAR CLIENTES: TODO TIPO DE USUARIO
		Route::get('/query', 'CustomersController@show')->name('dashboard.customers.query');

		// CREAR CLIENTE NUEVO: TODO TIPO DE USUARIO
		Route::get('/create', 'CustomersController@index')->name('dashboard.customers.view.create');
		Route::post('/create', 'CustomersController@store')->name('dashboard.customers.create');

		// ELIMINAR CLIENTE: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/delete/{id}', 'CustomersController@destroy')->name('dashboard.customers.delete');

		// ACTUALIZAR CLIENTE: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/update/{id}', 'CustomersController@viewUpdate')->name('dashboard.customers.view.update');
		Route::post('/update/{id}', 'CustomersController@update')->name('dashboard.customers.update');
	});

	// GRUPO DE PROVEEDORES: OPERACIONES CRUD DE LOS PROVEEDORES (Suppliers)
	Route::group(['prefix' => '/suppliers'], function () {

		// CONSULTAR PROVEEDORES: TODO TIPO DE USUARIO
		Route::get('/query', 'SupplierController@show')->name('dashboard.suppliers.query');

		// CREAR PROVEEDORES NUEVO: TODO TIPO DE USUARIO
		Route::get('/create', 'SupplierController@index')->name('dashboard.suppliers.view.create');
		Route::post('/create', 'SupplierController@store')->name('dashboard.suppliers.create');

		// ELIMINAR PROVEEDORES: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/detele/{id}', 'SupplierController@destroy')->name('dashboard.suppliers.delete');

		// ACTUALIZAR PROVEEDORES: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/update/{id}', 'SupplierController@viewUpdate')->name('dashboard.suppliers.view.update');
		Route::post('/update/{id}', 'SupplierController@update')->name('dashboard.suppliers.update');
	});

	// GRUPO DE CATEGORIA: OPERACIONES CRUD DE LOS CATEGORIA (Category)
	Route::group(['prefix' => '/category'], function () {

		// CONSULTAR CATEGORIA: TODO TIPO DE USUARIO
		Route::get('/query', 'CategoryController@show')->name('dashboard.category.query');

		// CREAR CATEGORIA NUEVA: TODO TIPO DE USUARIO
		Route::get('/create', 'CategoryController@index')->name('dashboard.category.view.create');
		Route::post('/create', 'CategoryController@store')->name('dashboard.category.create');

		// ELIMINAR CATEGORIA: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/detele/{id}', 'CategoryController@destroy')->name('dashboard.category.delete');

		// ACTUALIZAR CATEGORIA: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/update/{id}', 'CategoryController@viewUpdate')->name('dashboard.category.view.update');
		Route::post('/update/{id}', 'CategoryController@update')->name('dashboard.category.update');
	});

	// GRUPO DE SUBCATEGORIA: OPERACIONES CRUD DE LOS SUBCATEGORIA (Subcategory)
	Route::group(['prefix' => '/subcategory'], function () {

		// CONSULTAR SUBCATEGORIA: TODO TIPO DE USUARIO
		Route::get('/query', 'SubcategoryController@show')->name('dashboard.subcategory.query');

		// CREAR SUBCATEGORIA NUEVA: TODO TIPO DE USUARIO
		Route::get('/create', 'SubcategoryController@index')->name('dashboard.subcategory.view.create');
		Route::post('/create', 'SubcategoryController@store')->name('dashboard.subcategory.create');

		// ELIMINAR SUBCATEGORIA: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/detele/{id}', 'SubcategoryController@destroy')->name('dashboard.subcategory.delete');

		// ACTUALIZAR SUBCATEGORIA: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/update/{id}', 'SubcategoryController@viewUpdate')->name('dashboard.subcategory.view.update');
		Route::post('/update/{id}', 'SubcategoryController@update')->name('dashboard.subcategory.update');
	});

	// GRUPO DE SUCURSAL: OPERACIONES CRUD DE LOS SUCURSAL (Branch)
	Route::group(['prefix' => '/branch'], function () {

		// CONSULTAR SUCURSAL: TODO TIPO DE USUARIO
		Route::get('/query', 'BranchController@show')->name('dashboard.branch.query');

		// CREAR SUCURSAL NUEVA: TODO TIPO DE USUARIO
		Route::get('/create', 'BranchController@index')->name('dashboard.branch.view.create');
		Route::post('/create', 'BranchController@store')->name('dashboard.branch.create');

		// ELIMINAR SUCURSAL: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/detele/{id}', 'BranchController@destroy')->name('dashboard.branch.delete');

		// ACTUALIZAR SUCURSAL: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/update/{id}', 'BranchController@viewUpdate')->name('dashboard.branch.view.update');
		Route::post('/update/{id}', 'BranchController@update')->name('dashboard.branch.update');
	});

	// GRUPO DE WAREHOUSE: OPERACIONES CRUD DE LOS WAREHOUSE (Warehouse)
	Route::group(['prefix' => '/warehouse'], function () {

		// CONSULTAR WAREHOUSE: TODO TIPO DE USUARIO
		Route::get('/query', 'WarehouseController@show')->name('dashboard.warehouse.query');

		// CREAR WAREHOUSE NUEVA: TODO TIPO DE USUARIO
		Route::get('/create', 'WarehouseController@index')->name('dashboard.warehouse.view.create');
		Route::post('/create', 'WarehouseController@store')->name('dashboard.warehouse.create');

		// ELIMINAR WAREHOUSE: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/detele/{id}', 'WarehouseController@destroy')->name('dashboard.warehouse.delete');

		// ACTUALIZAR WAREHOUSE: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/update/{id}', 'WarehouseController@viewUpdate')->name('dashboard.warehouse.view.update');
		Route::post('/update/{id}', 'WarehouseController@update')->name('dashboard.warehouse.update');
	});

	// GRUPO DE MATERIA PRIMA: OPERACIONES CRUD DE LOS MATERIA PRIMA (rawMaterial)
	Route::group(['prefix' => '/raw_material'], function () {

		// CONSULTAR MATERIA PRIMA: TODO TIPO DE USUARIO
		Route::get('/query', 'RawMaterialController@show')->name('dashboard.raw_material.query');

		// CREAR MATERIA PRIMA NUEVA: TODO TIPO DE USUARIO
		Route::get('/create', 'RawMaterialController@index')->name('dashboard.raw_material.view.create');
		Route::post('/create', 'RawMaterialController@store')->name('dashboard.raw_material.create');

		// ELIMINAR MATERIA PRIMA: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/detele/{id}', 'RawMaterialController@destroy')->name('dashboard.raw_material.delete');

		// ACTUALIZAR MATERIA PRIMA: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/update/{id}', 'RawMaterialController@viewUpdate')->name('dashboard.raw_material.view.update');
		Route::post('/update/{id}', 'RawMaterialController@update')->name('dashboard.raw_material.update');
	});

	// GRUPO DE COMPRA A LOS PROVEEDORES: (suppliers_sale)
	Route::group(['prefix' => '/suppliers/sale'], function () {

		// BUSCAR
		Route::get('/search', 'SupplierSaleController@search')->name('dashboard.suppliers.sale.squery');

		// CONSULTAR COMPRA A LOS PROVEEDORES: TODO TIPO DE USUARIO
		Route::get('/query/{id}', 'SupplierSaleController@query')->name('dashboard.suppliers.sale.query.details');
		Route::get('/query', 'SupplierSaleController@show')->name('dashboard.suppliers.sale.query');

		// CREAR COMPRA A LOS PROVEEDORES: TODO TIPO DE USUARIO
		Route::get('/create', 'SupplierSaleController@index')->name('dashboard.suppliers.sale.view.create');
		Route::post('/create', 'SupplierSaleController@store')->name('dashboard.suppliers.sale.create');

		// ELIMINAR COMPRA A LOS PROVEEDORES: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/detele/{id}', 'SupplierSaleController@destroy')->name('dashboard.suppliers.sale.delete');

		// ACTUALIZAR COMPRA A LOS PROVEEDORES: REQUERIDO SER USUARIO TIPO ROOT
		Route::get('/update/{id}', 'SupplierSaleController@viewUpdate')->name('dashboard.suppliers.sale.view.update');
		Route::post('/update/{id}', 'SupplierSaleController@update')->name('dashboard.suppliers.sale.update');
	});

	// GRUPO DE PRESUPUESTOS: (estimate)
	Route::group(['prefix' => '/estimate'], function () {

		// BUSCAR
		// Route::get('/search', 'SupplierSaleController@search')->name('dashboard.suppliers.sale.squery');

		// CONSULTAR
		// Route::get('/query/{id}', 'SupplierSaleController@query')->name('dashboard.suppliers.sale.query.details');
		Route::get('/query', 'EstimateController@index')->name('dashboard.estimate.query');

		// // CREAR COMPRA A LOS PROVEEDORES: TODO TIPO DE USUARIO
		Route::get('/create', 'EstimateController@show')->name('dashboard.estimate.create');
		Route::post('/create', 'EstimateController@store')->name('dashboard.estimate.create');

		// // ELIMINAR COMPRA A LOS PROVEEDORES: REQUERIDO SER USUARIO TIPO ROOT
		// Route::get('/detele/{id}', 'SupplierSaleController@destroy')->name('dashboard.suppliers.sale.delete');

		// // ACTUALIZAR COMPRA A LOS PROVEEDORES: REQUERIDO SER USUARIO TIPO ROOT
		// Route::get('/update/{id}', 'SupplierSaleController@viewUpdate')->name('dashboard.suppliers.sale.view.update');
		// Route::post('/update/{id}', 'SupplierSaleController@update')->name('dashboard.suppliers.sale.update');
	});

	Route::resource('/productosElaborados', ProductosElaborados::class)->only([
		'index', 'show', 'create', 'update', 'edit'
	]);


});
