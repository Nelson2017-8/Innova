<?php

namespace App\Http\Controllers\Api;

use App\Almacen;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchTableController;
use App\Http\Controllers\ValidController;
use App\Sucursal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
	private $table = 'almacen';
	private $data = [
		'titleForm' => 'Mostrar Almacenes',
		'linkRegistro' => true,
		'linkEliminar' => true,
		'linkActualizar' => true,


		'autocompleteAjax' => true,

		'orderBy' => false,
		'export' => false,

		'linkExcel' => false,
		'linkPdf' => false,
		'linkPrint' => false,

		'add' => true,
		'delete' => true,
		'edit' => true,
		'Card' => [
			'titleCard' => 'Mostrar todos los Almacenes',
			'icoCard' => 'fas fa-users',
		],
		'Relacionado' => [],
		'relations' => false,
	];
	private $form = [
		'method' => 'POST',
		'class' => 'validate',
		'input' => [
			0 => [
				'name' => 'nombre',
				'id' => 'nombre',
				'title' => 'Nombre',
				'attr' => ['require' => '', 'placeholder'  => 'Introduzca una respuesta'],
			],

		],
		'select' => [
			0 => [
				'name' => 'sucursal_id',
				'id' => 'sucursal_id',
				'title' => 'Sucursal',
				'option' => [
					'' => 'SELECCIONE UNA SUCURSAL',
				],
				'attr' => ['require' => '', 'placeholder'  => 'Introduzca una respuesta'],
			],
		],
		'textarea' => [
			0 => [
				'name' => 'descripcion',
				'id' => 'descripcion',
				'attr' => ['placeholder' => 'Introduzca una descripción'],
				'title' => 'Descripción',
			],
		],
	];

	public function index(Request $request)
	{
		$tabla = null;
		$data = $this->data;
		$inputsTable = [];
		if ( isset(Almacen::All()[0]) ){
			$inputsTable = Almacen::All()[0]->getFillable();
		}else{
			// NO EXISTEN REGISTRO EN LA TABLA
			$noHayRegistro = true;
		}
		$columns = [
			'Nombre',
			'Sucursal',
			'Descripción',
			'Actualizado',
		];
		$data['nameTable'] = $this->table;
		$data['routePath'] = $this->table; // nombre de la ruta sin index, create, o edit
		$data['pathTabla'] = 'general'; // nombre de la tabla blade

		// NUMERO DE PAGINACION
		$numberPag = SearchTableController::checkPaginate($request->numberPag);
		$orderByIsNULL = ValidController::inputIsNull($request->orderBy);
		$inputIsNULL = ValidController::inputIsNull($request->input);
		$searchIsNULL = ValidController::inputIsNull($request->search);


		if (!isset($noHayRegistro)){
			if ($request->t === $this->table) {
				if ($orderByIsNULL) {
					$tabla = SearchTableController::orderBy($request->orderBy, $numberPag,
						function ($campo, $coincidencia, $numberPag) {
							return Almacen::orderBy($coincidencia, 'asc')->paginate($numberPag);
						}, $inputsTable);
				} else if ($searchIsNULL) {
					if ($inputIsNULL) {
						// TENEMOS UNA BUSQUEDA POR CAMPO
						$tabla =
							SearchTableController::searchWhere(
								[$request->input],
								$request->search,
								$numberPag, function ($campo, $value, $numberPag) {
								return Almacen::where($campo, $value)->paginate($numberPag
								);
							});
					} else {
						// NO HAY CAMPO ESPECIFICADO
						$tabla =
							SearchTableController::searchWhere(
								$inputsTable,
								$request->search,
								$numberPag,
								function ($campo, $value, $numberPag) { // BUSQUEDA 1
									return Almacen::where($campo, $value)->paginate($numberPag
									);
								});
					}
				}
			}

			$tabla = ($tabla == null) ? Almacen::orderBy('updated_at', 'desc')
				->paginate($numberPag) : $tabla;
			// INCREMENTAL
			foreach ($tabla as $indice => $valor) {
				$i = $indice;
				if ( isset($_GET['page']) ){
					if ( $_GET['page'] > 1 ){
						$max = $numberPag * $_GET['page'];
						$pagAnterior = $_GET['page'] - 1;
						$min = $numberPag * $pagAnterior;
						$indice2 = $indice;
						$i = $min + $indice2;
					}
				}

				$tabla[$indice]->incremental = $i;
			}
			// RELACION CATEGORIA
			foreach ($tabla as $indice => $valor) {
				$tabla[$indice]->_sucursal = $valor->sucursal->nombre;
			}

			$fillable = $tabla[0]->getFillable();
			$data['inputs'] = [];
			$data['camposSearch'] = [];
			foreach ($fillable as $key => $value) {
				if ( $value != '_sucursal' ){
					$data['camposSearch'][$columns[$key]] = $value;
				}

				$data['inputs'][$key]['head'] = $columns[$key];
				$data['inputs'][$key]['value'] = $value;
				$data['inputs'][$key]['name'] = $value;
			}
			//$columns = DB::getSchemaBuilder()->getColumnListing("users");



			if ( isset($_GET['page']) && !empty($request->ajax)){
				return view('dashboard._api.forms.form.index', [
					'data' => $data,
					'table_template' => true,
					'tabla' => $tabla,
					'columns' => $columns,

				]);
			}
			return view('dashboard._api.forms.index', [
				'data' => $data,
				'columns' => $columns,
				'tabla' => $tabla,
			]);
		}


		return view('dashboard._api.forms.index', [
			'data' => $data,
			// SI LLEGA HASTA AQUÍ NO HAY REGISTRO EN DB
			'noHayRegistro' => true, // VARIABLE OPCIONAL
		]);
	}



    public function create()
    {
		$forms = $this->form;
		$forms['action'] = route('almacen.store');
		$forms['btn']['title'] = 'Registrar Almacen';
		$data = $this->data;
		$data['titleForm'] = 'Registrar Almacen';
		$data['relations'] = true;
		$data['Relacionado'][0] = 'show almacen';
		$data['Relacionado'][1] = 'show sucursal';
		$data['Relacionado'][2] = 'register sucursal';
		$title = 'Registrar';

		// DAR VALORES AL SELECT
		$sucursales = Sucursal::orderBy('nombre')->select('id', 'nombre')->get();
		foreach ($sucursales as $sucursal) {
			$forms['select'][0]['option'][$sucursal->id] = $sucursal->nombre;
		}
		return view('dashboard._api.forms.create', [
			'tabla' => $this->table,
			'data' => $data,
			'forms' => $forms,
			'title' => $title,
		]);
    }


    public function store(Request $request)
    {
		$subcategorium = new Almacen($request->all());
		$subcategorium->sucursal_id = $request->sucursal_id;
		$subcategorium->save();

		$respuesta = ['messenger' => 'El almacen ha sido registrado correctamente', 'result' => 'success'];
		return JsonResponse::create($respuesta);
    }


    public function show(Almacen $almacen)
    {
        //
    }

    public function edit(Almacen $almacen)
    {
		$forms = $this->form;
		$forms['action'] = route('almacen.update', $almacen->id);
		$forms['data-update'] = route('almacen.index');
		$forms['btn']['title'] = 'Actualizar';
		$forms['method'] = 'PUT';

		// DAR VALORES AL SELECT
		$sucursales = Sucursal::orderBy('nombre')->select('id', 'nombre')->get();
		foreach ($sucursales as $sucursal) {
			$forms['select'][0]['option'][$sucursal->id] = $sucursal->nombre;
		}

		// valores de la DB
		$forms['input'][0]['attr']['data-value'] = $almacen->nombre;
		$forms['textarea'][0]['attr']['data-value'] = $almacen->descripcion;
		$forms['select'][0]['attr']['data-value'] = $almacen->sucursal_id;
		$data = $this->data;
		$data['relations'] = true;
		$data['Relacionado'][2] = 'show almacen';
		$data['Relacionado'][0] = 'register almacen';
		$data['Relacionado'][1] = 'register sucursal';

		$title = 'Actualizar "' . $almacen->nombre. '"';

		return view('dashboard._api.forms.edit', [
			'title' => $title,
			'forms' => $forms,
			'data' => $data,
			'tabla' => $this->table,
		]);
    }

    public function update(Request $request, Almacen $almacen)
    {
		// SI EL CAMPO ES NULL-VACIO NO ACTUALIZA
		$almacen->nombre = $request->nombre;
		$almacen->descripcion = $request->descripcion;
		$almacen->sucursal_id = $request->sucursal_id;

		// GUARDO
		$almacen->update();
		$messenger = 'El almacen "' . $almacen->nombre . '" ha sido actualizado';

		$_SESSION['notifications'] = array('success' => $messenger);
		$respuesta = ['messenger' => $messenger, 'result' => 'success'];
		return JsonResponse::create($respuesta);
    }


    public function destroy(Almacen $almacen)
    {
		$messenger = 'El almacen "' . $almacen->nombre. '" ha sido eliminado';
		$almacen->delete();
		// NOTIFICO QUE LA OPERACIÓN FUE EXITOSA
		$_SESSION['notifications'] = array('success' => $messenger);
		return 'success';
    }
}
