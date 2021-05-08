<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchTableController;
use App\Http\Controllers\ValidController;
use App\Http\Requests\ProveedorRequest;
use App\Proveedor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
	private $table = 'proveedores';
	private $data = [
		'titleForm' => 'Mostrar Proveedores',
		'linkRegistro' => true,
		'linkEliminar' => true,
		'linkActualizar' => true,


		'autocompleteAjax' => true,

		'orderBy' => true,
		'export' => true,

		'linkExcel' => true,
		'linkPdf' => true,
		'linkPrint' => true,

		'add' => true,
		'delete' => true,
		'edit' => true,
		'Card' => [
			'titleCard' => 'Mostrar todos los proveedores',
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
				'col' => 'col-sm-6',
				'title' => 'Nombre',
				'attr' => ['require' => '', 'placeholder'  => 'Introduzca una respuesta'],
			],
			1 => [
				'name' => 'razonSocial',
				'id' => 'razonSocial',
				'col' => 'col-sm-6',
				'title' => 'Razón Social',
				'attr' => ['require' => '', 'placeholder'  => 'Introduzca una respuesta'],
			],
			2 => [
				'name' => 'correo',
				'id' => 'correo',
				'title' => 'Correo Electrónico',
				'type' => 'email',
				'attr' => ['require' => '', 'placeholder'  => 'Introduzca una respuesta'],
			],

			3 => [
				'name' => 'telefono_1',
				'id' => 'telefono_1',
				'col' => 'col-sm-6',
				'title' => 'Teléfono 1',
				'attr' => ['require' => '', 'placeholder'  => 'Ejemplo: +58 04160000000'],
			],
			4 => [
				'name' => 'telefono_2',
				'id' => 'telefono_2',
				'col' => 'col-sm-6',
				'title' => 'Teléfono 2',
				'attr' => ['placeholder'  => 'Ejemplo: +58 04160000000'],
			],
			5 => [
				'name' => 'cod_postal',
				'id' => 'cod_postal',
				'title' => 'Código Postal',
				'attr' => ['require' => '', 'placeholder'  => 'Introduzca una respuesta'],
			],

		],
		'textarea' => [
			0 => [
				'name' => 'direccion',
				'id' => 'direccion',
				'attr' => ['require' => '', 'placeholder' => 'Introduzca una dirección'],
				'title' => 'Dirección',
			],
		],
	];

	public function index(Request $request)
	{
		$tabla = null;
		$data = $this->data;
		$inputsTable = [];
		if ( isset(Proveedor::All()[0]) ){
			$inputsTable = Proveedor::All()[0]->getFillable();
		}else{
			// NO EXISTEN REGISTRO EN LA TABLA
			$noHayRegistro = true;
		}
		$columns = [
			'Nombre',
			'Razón Social',
			'Correo Electrónico',
			'Teléfono 1',
			'Teléfono 2',
			'Código Postal',
			'Dirección',
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
							return Proveedor::orderBy($coincidencia, 'asc')->paginate($numberPag);
						}, $inputsTable);
				} else if ($searchIsNULL) {
					if ($inputIsNULL) {
						// TENEMOS UNA BUSQUEDA POR CAMPO
						$tabla =
							SearchTableController::searchWhere(
								[$request->input],
								$request->search,
								$numberPag, function ($campo, $value, $numberPag) {
								return Proveedor::where($campo, $value)->paginate($numberPag
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
									return Proveedor::where($campo, $value)->paginate($numberPag
									);
								});
					}
				}
			}

			$tabla = ($tabla == null) ? Proveedor::orderBy('updated_at', 'desc')
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
		$forms['action'] = route('proveedores.store');
		$forms['btn']['title'] = 'Registrar';
		$data = $this->data;
		$data['relations'] = true;
		$data['Relacionado'][0] = 'show proveedores';
		$data['Relacionado'][1] = 'show usuario';
		$data['Relacionado'][2] = 'show cliente';
		$title = 'Registrar Cliente';

		return view('dashboard._api.forms.create', [
			'tabla' => $this->table,
			'data' => $data,
			'forms' => $forms,
			'title' => $title,
		]);
	}




	public function store(ProveedorRequest $request)
	{
		$proveedore = new Proveedor($request->all());
		$proveedore->save();

		$respuesta = ['messenger' => 'El Proveedor ha sido registrado correctamente', 'result' => 'success'];
		return JsonResponse::create($respuesta);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Proveedor  $proveedore
	 * @return \Illuminate\Http\Response
	 */
	public function show(Proveedor $proveedore)
	{
		//
	}


	public function edit(Proveedor $proveedore)
	{
		$forms = $this->form;
		$forms['action'] = route('proveedores.update', $proveedore->id);
		$forms['data-update'] = route('proveedores.index');
		$forms['btn']['title'] = 'Actualizar';
		$forms['method'] = 'PUT';
		// valores de la DB
		$forms['input'][0]['attr']['data-value'] = $proveedore->nombre;
		$forms['input'][1]['attr']['data-value'] = $proveedore->razonSocial;
		$forms['input'][2]['attr']['data-value'] = $proveedore->correo;
		$forms['input'][3]['attr']['data-value'] = $proveedore->telefono_1;
		$forms['input'][4]['attr']['data-value'] = $proveedore->telefono_2;
		$forms['input'][5]['attr']['data-value'] = $proveedore->cod_postal;
		$forms['textarea'][0]['attr']['data-value'] = $proveedore->direccion;
		$data = $this->data;
		$data['relations'] = true;
		$data['Relacionado'][2] = 'show proveedores';
		$data['Relacionado'][0] = 'register proveedores';
		$data['Relacionado'][1] = 'show cliente';

		$title = 'Actualizar "' . $proveedore->nombre. '"';

		return view('dashboard._api.forms.edit', [
			'title' => $title,
			'forms' => $forms,
			'data' => $data,
			'tabla' => $this->table,
		]);
	}


	public function update(Request $request, Proveedor $proveedore)
	{

		// SI EL CAMPO ES NULL-VACIO NO ACTUALIZA
		$proveedore->nombre = $request->nombre;
		$proveedore->razonSocial = $request->razonSocial;
		$proveedore->correo = $request->correo;
		$proveedore->telefono_1 = $request->telefono_1;
		$proveedore->telefono_2 = $request->telefono_2;
		$proveedore->cod_postal = $request->cod_postal;
		$proveedore->direccion = $request->direccion;
		// GUARDO
		$proveedore->update();
		$messenger = 'El proveedor "' . $proveedore->nombre . '" ha sido actualizado';

		$_SESSION['notifications'] = array('success' => $messenger);
		$respuesta = ['messenger' => $messenger, 'result' => 'success'];
		return JsonResponse::create($respuesta);
	}

	public function destroy(Proveedor $proveedore)
	{
		$messenger = 'El proveedor "' . $proveedore->nombre. '" ha sido eliminado';
		$proveedore->delete();
		// NOTIFICO QUE LA OPERACIÓN FUE EXITOSA
		$_SESSION['notifications'] = array('success' => $messenger);
		return 'success';
	}
}
