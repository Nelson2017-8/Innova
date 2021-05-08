<?php

namespace App\Http\Controllers;

use App\Almacen;
use App\Http\Controllers\TokenController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthenticateController;
use App\Sucursal;
use App\Http\Requests\WarehouseRequest;

class WarehouseController extends Controller
{
	protected $user = array();
	protected $auth = '';
	protected $authRoute = 'login';

	// REGISTRA LA VARIABLE $this->user Y COMIENZA LA SESSIÓN
	public function __construct()
	{
		$this->auth = new AuthenticateController();
		$this->user = $this->auth->start();
	}


	// VISTA DE CONSULTAR ALMACEN: EXTRAE LOS DATOS NECESARIO A LA VISTA.
	// SI SE ENVIA UNA NOTIFICACIÓN HASTA AQUÍ LA MUESTRA
	public function show(Request $request)
	{
		// REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
		if (is_array($this->user) == false) {
			return $this->auth->redirect($this->authRoute);
		}

		$warehouse = null;
		// NUMERO DE PAGINACION
		$numberPag = SearchTableController::checkPaginate($request->numberPag);
		$orderByIsNULL = ValidController::inputIsNull($request->orderBy);
		$inputIsNULL = ValidController::inputIsNull($request->input);
		$searchIsNULL = ValidController::inputIsNull($request->search);
		$inputsTable = ['nombre', 'sucursal_id', 'ubicacion', 'updated_at'];

		if ($request->t === 'almacen') {
			if ($orderByIsNULL) {

				$warehouse = SearchTableController::orderBy($request->orderBy, $numberPag, function ($campo, $coincidencia, $numberPag) {
					return Almacen::orderBy($coincidencia, 'asc')->paginate($numberPag);
				}, $inputsTable);

			} else if ($searchIsNULL) {

				if ($inputIsNULL) {
					// TENEMOS UNA BUSQUEDA POR CAMPO
					$warehouse =
						SearchTableController::searchWhere(
							[$request->input],
							$request->search,
							$numberPag, function ($campo, $value, $numberPag) {
							return Almacen::where($campo, $value)->paginate($numberPag);
						});

				} else {
					// NO HAY CAMPO ESPECIFICADO
					$warehouse =
						SearchTableController::searchWhere(
							$inputsTable,
							$request->search,
							$numberPag,
							function ($campo, $value, $numberPag) { // BUSQUEDA 1
								return Almacen::where($campo, $value)->paginate($numberPag);
							});
				}
			}
		}

		$warehouse = ($warehouse == null) ? Almacen::orderBy('updated_at', 'desc')->paginate($numberPag) : $warehouse;
		return view('dashboard.warehouse.query',
			[
				'user' => $this->user,
				'warehouse' => $warehouse,
				'notifications' => $this->auth->notifications(),
			]
		);
	}

	// VISTA DE CREAR ALMACEN, SOLO TIPO ROOT
	// SI SE ENVIA UNA NOTIFICACIÓN HASTA AQUÍ LA MUESTRA
	public function index()
	{
		// REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
		if (is_array($this->user) == false) {
			return $this->auth->redirect($this->authRoute);
		}


		return view('dashboard.warehouse.create',
			[
				'user' => $this->user,
				'notifications' => $this->auth->notifications(),
				'sucursal' => Sucursal::all(),
			]
		);
	}

	// PROCESO DE REGISTRO DE ALMACEN: SOLO USUARIO ROOT. REGISTRA,
	// REDIMENCIONA A LA VISTA DE CREAR CLIENTE Y ENVIA UNA NOTIFICACIÓN
	public function store(WarehouseRequest $request)
	{
		// REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
		if (is_array($this->user) == false) {
			return $this->auth->redirect($this->authRoute);
		}

		$warehouse = new Almacen($request->all());
		$warehouse->sucursal_id = TokenController::desencriptar($request->sucusal);
		$warehouse->save();

		$respuesta = ['messenger' => 'El registro ha sido insertado con éxito', 'result' => 'success'];
		return json_encode($respuesta);

	}

	// PROCESO DE ELIMINACIÓN DE ALMACEN: SOLO USUARIO ROOT. ELIMINA, REDIMENCIONA Y
	// ENVIA UNA NOTIFICACIÓN A LA VISTA CONSULTA
	public function destroy($id)
	{
		// REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
		if (is_array($this->user) == false) {
			return $this->auth->redirect($this->authRoute);
		}

		if ($this->auth->isRoot() === false) {

			$_SESSION['notifications'] = array(
				'error' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
			);
			return redirect()->route('dashboard.index');

		}
		// ID DEL USUARIO DESENCRIPTADO
		$id = TokenController::desencriptar($id);
		Almacen::find($id)->delete();
		$_SESSION['notifications'] = array('warning' => 'El Almacen ha sido eliminado');

		return redirect()->route('dashboard.warehouse.query');


	}

	// VISTA DE ACTUALIZACIÓN DE ALMACEN: REQUERIDO SER USUARIO TIPO ROOT
	public function viewUpdate($id)
	{
		// REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
		if (is_array($this->user) == false) {
			return $this->auth->redirect($this->authRoute);
		}

		// ID DEL USUARIO DESENCRIPTADO
		$_id = TokenController::desencriptar($id);
		return view('dashboard.warehouse.update', [
			'id' => $id,
			'user' => $this->user,
			'warehouse' => Almacen::find($_id),
			'sucursal' => Sucursal::all(),
		]);

	}

	// PROCESO DE ACTUALIZACIÓN DE ALMACEN: SOLO USUARIO ROOT
	public function update(Request $request, $id)
	{
		// REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
		if (is_array($this->user) == false) {
			return $this->auth->redirect($this->authRoute);
		}


		// ID DEL USUARIO DESENCRIPTADO
		$id = TokenController::desencriptar($id);
		$warehouse = Almacen::find($id);

		if ($request->sucursal != '' && $request->sucursal != NULL) {
			$warehouse->sucursal_id = TokenController::desencriptar($request->sucursal);
		}
		$warehouse->nombre = $request->nombre;
		$warehouse->descripcion = $request->descripcion;
		$warehouse->save();


		$respuesta = ['messenger' => 'El registro ha sido actualizado con éxito', 'result' => 'success'];
		return json_encode($respuesta);

	}
}
