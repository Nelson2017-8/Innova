<?php

namespace App\Http\Controllers\Api;

use App\Cliente;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchTableController;
use App\Http\Controllers\ValidController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ClienteRequest;

class ClienteController extends Controller
{
	private $table = 'clientes';
	private $data = [
		'titleForm' => 'Mostrar Clientes',
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
			'titleCard' => 'Mostrar todos los clientes',
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
				'name' => 'primerNombre',
				'id' => 'primerNombre',
				'col' => 'col-sm-6',
				'title' => 'Nombre',
				'attr' => ['require' => '', 'placeholder'  => 'Introduzca una respuesta'],
			],
			1 => [
				'name' => 'primerApellido',
				'id' => 'primerApellido',
				'col' => 'col-sm-6',
				'title' => 'Apellido',
				'attr' => ['require' => '', 'placeholder'  => 'Introduzca una respuesta'],
			],
			2 => [
				'name' => 'cedula',
				'id' => 'cedula',
				'title' => 'Cédula',
				'attr' => ['require' => '', 'placeholder'  => 'Ejemplo: V-00000000'],
			],
			3 => [
				'name' => 'correo',
				'id' => 'correo',
				'title' => 'Correo Electrónico',
				'type' => 'email',
				'attr' => ['require' => '', 'placeholder'  => 'Introduzca una respuesta'],
			],
			4 => [
				'name' => 'telefono_1',
				'id' => 'telefono_1',
				'col' => 'col-sm-6',
				'title' => 'Teléfono 1',
				'attr' => ['require' => '', 'placeholder'  => 'Ejemplo: +58 04160000000'],
			],
			5 => [
				'name' => 'telefono_2',
				'id' => 'telefono_2',
				'col' => 'col-sm-6',
				'title' => 'Teléfono 2',
				'attr' => ['placeholder'  => 'Ejemplo: +58 04160000000'],
			],

		],
		'textarea' => [
			0 => [
				'name' => 'direccion',
				'id' => 'direccion',
				'attr' => ['placeholder' => 'Introduzca una dirección'],
				'title' => 'Dirección',
			],
		],
	];

	public function index(Request $request)
	{
		$tabla = null;
		$data = $this->data;
		$inputsTable = [];
		if ( isset(Cliente::All()[0]) ){
			$inputsTable = Cliente::All()[0]->getFillable();
		}else{
			// NO EXISTEN REGISTRO EN LA TABLA
			$noHayRegistro = true;
		}
		$columns = [
			'Nombre',
			'Apellido',
			'Cédula',
			'Correo Electrónico',
			'Teléfono 1',
			'Teléfono 2',
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
							return Cliente::orderBy($coincidencia, 'asc')->paginate($numberPag);
						}, $inputsTable);
				} else if ($searchIsNULL) {
					if ($inputIsNULL) {
						// TENEMOS UNA BUSQUEDA POR CAMPO
						$tabla =
							SearchTableController::searchWhere(
								[$request->input],
								$request->search,
								$numberPag, function ($campo, $value, $numberPag) {
								return Cliente::where($campo, $value)->paginate($numberPag
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
									return Cliente::where($campo, $value)->paginate($numberPag
									);
								});
					}
				}
			}

			$tabla = ($tabla == null) ? Cliente::orderBy('updated_at', 'desc')
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
		$forms['action'] = route('clientes.store');
		$forms['btn']['title'] = 'Registrar';
		$data = $this->data;
		$data['relations'] = true;
		$data['Relacionado'][0] = 'show cliente';
		$data['Relacionado'][1] = 'show usuario';
		$data['Relacionado'][2] = 'show proveedores';
		$title = 'Registrar Cliente';

		return view('dashboard._api.forms.create', [
			'tabla' => $this->table,
			'data' => $data,
			'forms' => $forms,
			'title' => $title,
		]);
	}




    public function store(ClienteRequest $request)
    {
		$cliente = new Cliente($request->all());
		$cliente->save();

		$respuesta = ['messenger' => 'El Cliente ha sido registrado correctamente', 'result' => 'success'];
		return JsonResponse::create($respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
    }


    public function edit(Cliente $cliente)
    {
		$forms = $this->form;
		$forms['action'] = route('clientes.update', $cliente->id);
		$forms['data-update'] = route('clientes.index');
		$forms['btn']['title'] = 'Actualizar';
		$forms['method'] = 'PUT';
		// valores de la DB
		$forms['input'][0]['attr']['data-value'] = $cliente->primerNombre;
		$forms['input'][1]['attr']['data-value'] = $cliente->primerApellido;
		$forms['input'][2]['attr']['data-value'] = $cliente->cedula;
		$forms['input'][3]['attr']['data-value'] = $cliente->correo;
		$forms['input'][4]['attr']['data-value'] = $cliente->telefono_1;
		$forms['input'][5]['attr']['data-value'] = $cliente->telefono_2;
		$forms['textarea'][0]['attr']['data-value'] = $cliente->direccion;
		$data = $this->data;
		$data['relations'] = true;
		$data['Relacionado'][2] = 'show cliente';
		$data['Relacionado'][0] = 'register cliente';
		$data['Relacionado'][1] = 'show usuario';

		$title = 'Actualizar "' . $cliente->primerNombre.' '. $cliente->primerApellido . '"';

		return view('dashboard._api.forms.edit', [
			'title' => $title,
			'forms' => $forms,
			'data' => $data,
			'tabla' => $this->table,
		]);
    }


    public function update(Request $request, Cliente $cliente)
    {

		// SI EL CAMPO ES NULL-VACIO NO ACTUALIZA
		$cliente->primerNombre = $request->primerNombre;
		$cliente->primerApellido = $request->primerApellido;
		$cliente->correo = $request->correo;
		$cliente->cedula = $request->cedula;
		$cliente->telefono_1 = $request->telefono_1;
		$cliente->telefono_2 = $request->telefono_2;
		$cliente->direccion = $request->direccion;
		// GUARDO
		$cliente->update();
		$messenger = 'El cliente "' . $cliente->primerNombre.' '. $cliente->primerApellido . '" ha sido actualizado';

		$_SESSION['notifications'] = array('success' => $messenger);
		$respuesta = ['messenger' => $messenger, 'result' => 'success'];
		return JsonResponse::create($respuesta);
    }

    public function destroy(Cliente $cliente)
    {
		$messenger = 'El cliente "' . $cliente->primerNombre. ' ' . $cliente->primerApellido . '" ha sido eliminado';
		$cliente->delete();
		// NOTIFICO QUE LA OPERACIÓN FUE EXITOSA
		$_SESSION['notifications'] = array('success' => $messenger);
		return 'success';
    }
}
