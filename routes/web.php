<?php
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\TableExport;

use App\Http\Controllers as Controllers;

// MÉTODO GET: INCIO SESION - PAGINA PRINCIPAL
Route::get('/', 'UserController@getLogin')->name('home');

// MÉTODO GET: INCIO SESION
Route::get('/login', 'UserController@getLogin')->name('login');

// MÉTODO GET: REGISTRAR USUARIO
//Route::get('/register', 'UserController@getRegister')->name('register');

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

Route::middleware([App\Http\Middleware\AuthCheck::class])->group(function () {
	// MÉTODO POST: INCIO SESION
	Route::post('/login', 'UserController@login')->name('nLogin');
	// MÉTODO POST: REGISTRAR USUARIO
	// Route::post('/register', 'UserController@register')->name('nRegister');
	// MÉTODOS POSTS: RECUPERAR CONTRASEÑA
	Route::post('/forgotten', 'UserController@forgotten')->name('nPasswordreset');
	Route::get('/password_reset/t/{token}', 'UserController@passwordReset')->name('rPasswordreset');
	Route::post('/password_reset/v/save', 'UserController@saveNewPassword')->name('saveNewPassword');
	Route::get('/password_reset/v/save/err', 'UserController@errSaveNewPassword')->name('errSaveNewPassword');

});


// BUSQUEDAS
Route::group(['prefix' => '/search/json'], function () {
	Route::get('/clientes', 'DatajsonController@customers')->name('api.json.customers.all');
});

// GENERAR ARCHIVO EXCEL
Route::get('/export/excel/', function (\Illuminate\Http\Request $request) {
	$name = 'report-'.$request->table.'-'.date('d-m-Y h:s:i');
	return Excel::download(new TableExport, $name.'.xlsx');
})->name('export-excel');

// GENERAR VISTA PREVIA PARA IMPRIMIR
Route::get('/preview/print/{table}', 'ExportFileController@print')->name('export-print');

// GENERAR VISTA DE PDF
Route::get('/view/table/{table}', 'ExportFileController@pdf')->name('export-pdf');

Route::get('/data/pdf/t/{table}', 'Api\ajax\SearchController@dataPdf')->name('get-data-pdf');
Route::middleware([App\Http\Middleware\Auth::class])->group(function () {
	// SEARCH AJAX
	Route::get('/search/t/{table}', 'Api\ajax\SearchController@index')->name('get-data-table');
	Route::get('/search/t/{table}/input', 'Api\ajax\SearchController@input')->name('get-data-input');
	Route::get('/search/t/{table}/inputs', 'Api\ajax\SearchController@inputs')->name('get-data-inputs');

	Route::group(['prefix' => '/panelControl'], function () {
		// PAGINA PRINCIPAL
		Route::get('/', 'DashboardController@index')->name('dashboard.index');

		route::resource('/clientes', 'Api\ClienteController');
		route::resource('/proveedores', 'Api\ProveedorController');
		route::resource('/compras', 'Api\CompraRealizadaController');
		Route::resource('/compras/articulos', 'Api\CompraArticulosController',
			['as' => 'compras']
		);

		// SOLO LOS USUARIOS ROOT TIENE PERMITIDO ENTRAR EN ESTAS RUTAS
		Route::middleware([App\Http\Middleware\IfRoot::class])->group(function () {
			route::resource('/users', 'Api\UsuarioController');
			route::resource('/sucursal', 'Api\SucursalController');
			route::resource('/categoria', 'Api\CategoriaController');
			route::resource('/subcategoria', 'Api\SubcategoriaController');
			route::resource('/almacen', 'Api\AlmacenController');

		});

	});
});
