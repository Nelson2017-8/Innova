<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchTableController;
use App\Http\Controllers\ValidController;
use App\Sucursal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
	private $table = 'sucursal';
	private $data = [
		'titleForm' => 'Mostrar Sucursales',
		'linkRegistro' => true,
		'linkEliminar' => true,
		'linkActualizar' => true,


		'autocompleteAjax' => false,

		'orderBy' => false,
		'export' => false,

		'linkExcel' => false,
		'linkPdf' => false,
		'linkPrint' => false,

		'add' => true,
		'delete' => true,
		'edit' => true,
		'Card' => [
			'titleCard' => 'Mostrar todas las sucursales',
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
		'textarea' => [
			0 => [
				'name' => 'ubicacion',
				'id' => 'ubicacion',
				'attr' => ['require' => '', 'placeholder' => 'Introduzca una ubicación'],
				'title' => 'Dirección',
			],
		],
	];

	public function index(Request $request)
	{
		$tabla = null;
		$data = $this->data;
		$inputsTable = [];
		if ( isset(Sucursal::All()[0]) ){
			$inputsTable = Sucursal::All()[0]->getFillable();
		}else{
			// NO EXISTEN REGISTRO EN LA TABLA
			$noHayRegistro = true;
		}
		$columns = [
			'Nombre',
			'Ubicación',
			'Actualizado'
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
							return Sucursal::orderBy($coincidencia, 'asc')->paginate($numberPag);
						}, $inputsTable);
				} else if ($searchIsNULL) {
					if ($inputIsNULL) {
						// TENEMOS UNA BUSQUEDA POR CAMPO
						$tabla =
							SearchTableController::searchWhere(
								[$request->input],
								$request->search,
								$numberPag, function ($campo, $value, $numberPag) {
								return Sucursal::where($campo, $value)->paginate($numberPag
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
									return Sucursal::where($campo, $value)->paginate($numberPag
									);
								});
					}
				}
			}

			$tabla = ($tabla == null) ? Sucursal::orderBy('updated_at', 'desc')
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

			$fillable = $tabla[0]->getFillable();
			$data['inputs'] = [];
			$data['camposSearch'] = [];
			foreach ($fillable as $key => $value) {
				$data['camposSearch'][$columns[$key]] = $value;

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
		$forms['action'] = route('sucursal.store');
		$forms['btn']['title'] = 'Registrar';
		$data = $this->data;
		$data['relations'] = true;
		$data['Relacionado'][0] = 'show sucursal';
		$data['Relacionado'][1] = 'show usuario';
		$data['Relacionado'][2] = 'show cliente';
		$title = 'Registrar Sucursal';

		return view('dashboard._api.forms.create', [
			'tabla' => $this->table,
			'data' => $data,
			'forms' => $forms,
			'title' => $title,
		]);
    }


    public function store(Request $request)
    {
		$sucursal = new Sucursal($request->all());
		$sucursal->save();

		$respuesta = ['messenger' => 'La sucursal ha sido registrado correctamente', 'result' => 'success'];
		return JsonResponse::create($respuesta);
    }


    public function show(Sucursal $sucursal)
    {
        //
    }

    public function edit(Sucursal $sucursal)
    {
		$forms = $this->form;
		$forms['action'] = route('sucursal.update', $sucursal->id);
		$forms['data-update'] = route('sucursal.index');
		$forms['btn']['title'] = 'Actualizar';
		$forms['method'] = 'PUT';
		// valores de la DB
		$forms['input'][0]['attr']['data-value'] = $sucursal->nombre;
		$forms['textarea'][0]['attr']['data-value'] = $sucursal->ubicacion;
		$data = $this->data;
		$data['relations'] = true;
		$data['Relacionado'][2] = 'show sucursal';
		$data['Relacionado'][0] = 'register sucursal';
		$data['Relacionado'][1] = 'show cliente';

		$title = 'Actualizar "' . $sucursal->nombre. '"';

		return view('dashboard._api.forms.edit', [
			'title' => $title,
			'forms' => $forms,
			'data' => $data,
			'tabla' => $this->table,
		]);
    }

    public function update(Request $request, Sucursal $sucursal)
    {
		// SI EL CAMPO ES NULL-VACIO NO ACTUALIZA
		$sucursal->nombre = $request->nombre;
		$sucursal->ubicacion = $request->ubicacion;
		// GUARDO
		$sucursal->update();
		$messenger = 'La sucursal "' . $sucursal->nombre . '" ha sido actualizado';

		$_SESSION['notifications'] = array('success' => $messenger);
		$respuesta = ['messenger' => $messenger, 'result' => 'success'];
		return JsonResponse::create($respuesta);
    }


    public function destroy(Sucursal $sucursal)
    {
		$messenger = 'La sucursal "' . $sucursal->nombre. '" ha sido eliminado';
		$sucursal->delete();
		// NOTIFICO QUE LA OPERACIÓN FUE EXITOSA
		$_SESSION['notifications'] = array('success' => $messenger);
		return 'success';
    }
}
