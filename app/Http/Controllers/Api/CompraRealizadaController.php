<?php

namespace App\Http\Controllers\Api;

use App\Compra;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchTableController;
use App\Http\Controllers\ValidController;
use App\Http\Requests\CompraRequest;
use App\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompraRealizadaController extends Controller
{
	private $table = 'compras';
	private $data = [
		'titleForm' => 'Compras Realizadas',
		'linkRegistro' => true,
		'linkEliminar' => true,
		'linkActualizar' => true,


		'autocompleteAjax' => true,

		'orderBy' => false,
		'export' => true,

		'linkExcel' => true,
		'linkPdf' => true,
		'linkPrint' => true,

		'add' => true,
		'delete' => true,
		'edit' => true,
		'Card' => [
			'titleCard' => 'Compras Realizadas',
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
				'name' => 'numFactura',
				'id' => 'numFactura',
				'title' => 'Número Factura',
				'attr' => ['require' => '', 'placeholder'  => 'Introduzca una respuesta'],
			],
			1 => [
				'name' => 'limitGarantia',
				'id' => 'limitGarantia',
				'title' => 'Garantía',
				'type' => 'date',
				'attr' => ['placeholder'  => 'Ejemplo: 30 días'],
			],

		],
		'select' => [
			0 => [
				'name' => 'proveedor_id',
				'id' => 'proveedor_id',
				'title' => 'Proveedor',
				'option' => [
					'' => 'SELECCIONE UN PROVEEDOR',
				],
				'attr' => ['require' => ''],
			],
		],
	];

	public function index(Request $request)
	{
		$tabla = null;
		$data = $this->data;
		$inputsTable = [];
		if ( isset(Compra::All()[0]) ){
			$inputsTable = Compra::All()[0]->getFillable();
		}else{
			// NO EXISTEN REGISTRO EN LA TABLA
			$noHayRegistro = true;
		}
		$columns = [
			'Proveedor',
			'Factura',
			'Monto Total',
			'Garantía',
			'Fecha de Emisión',
		];
		$data['nameTable'] = $this->table;
		$data['routePath'] = $this->table; // nombre de la ruta sin index, create, o edit
		$data['pathTabla'] = 'compras'; // nombre de la tabla blade

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
							return Compra::orderBy($coincidencia, 'asc')->paginate($numberPag);
						}, $inputsTable);
				} else if ($searchIsNULL) {
					if ($inputIsNULL) {
						// TENEMOS UNA BUSQUEDA POR CAMPO
						$tabla =
							SearchTableController::searchWhere(
								[$request->input],
								$request->search,
								$numberPag, function ($campo, $value, $numberPag) {
								return Compra::where($campo, $value)->paginate($numberPag
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
									return Compra::where($campo, $value)->paginate($numberPag
									);
								});
					}
				}
			}

			$tabla = ($tabla == null) ? Compra::orderBy('fechaCompra', 'desc')
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
			// RELACION PROVEEDORES
			foreach ($tabla as $indice => $valor) {
				$tabla[$indice]->_proveedor = $valor->proveedor->razonSocial;
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
				return view('dashboard._api.forms.form.resource', [
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
				'view' => 'dashboard._api.forms.form.resource',
			]);
		}


		return view('dashboard._api.forms.index', [
			'data' => $data,
			'view' => 'dashboard._api.forms.form.resource',
			// SI LLEGA HASTA AQUÍ NO HAY REGISTRO EN DB
			'noHayRegistro' => true, // VARIABLE OPCIONAL
		]);
	}


	public function create()
	{
		$forms = $this->form;
		$forms['action'] = route('compras.store');
		$forms['btn']['title'] = 'Registrar';
		$data = $this->data;
		$data['relations'] = true;
		$data['Relacionado'][0] = 'show compra';
		$data['Relacionado'][1] = 'show proveedores';
		$data['Relacionado'][2] = 'register proveedores';
		$title = 'Registrar Compra';

		// DAR VALORES AL SELECT
		$proveedor = Proveedor::orderBy('nombre')->select('id', 'razonSocial')->get();
		foreach ($proveedor as $proveedor) {
			$forms['select'][0]['option'][$proveedor->id] = $proveedor->razonSocial;
		}
		$migas_de_pan = [
			'Compras' => [
				'name' => 'Compras',
				'route' => route('compras.index'),
			],
			'Detalles' => [
				'name' => 'Crear una compra',
				'route' => '',
			],
		];
		return view('dashboard._api.forms.create', [
			'tabla' => $this->table,
			'data' => $data,
			'forms' => $forms,
			'title' => $title,
			'migas_de_pan' => $migas_de_pan,
		]);
	}

	public function store(CompraRequest $request)
	{
		$compra = new Compra($request->all());
		$compra->proveedor_id = $request->proveedor_id;
		$compra->limitGarantia = Carbon::parse($request->limitGarantia)->format('Y-m-d');
		$compra->fechaCompra = Carbon::now()->format('Y-m-d h:i:s');
		$compra->save();

		$respuesta = [
			'messenger' => 'La compra ha sido registrado correctamente',
			'result' => 'success',
			'redirect' => route('compras.articulos.create',
				[
					'numFactura' => $compra->numFactura,
				]
			)
		];
		return JsonResponse::create($respuesta);
	}

	public function show(Compra $compra)
    {
    	$data = $this->data;
    	$data['titleForm'] = 'Detalles de la Compra #'.$compra->numFactura;
    	$migas_de_pan = [
    		'Compras' => [
    			'name' => 'Compras',
				'route' => route('compras.index'),
			],
    		'Detalles' => [
				'name' => 'Detalles',
				'route' => '',
			],
		];
		return view('dashboard._api.forms.show.compras', [
			'compra' => $compra,
			'data' => $data,
			'migas_de_pan' => $migas_de_pan,
			'dropdown' => 'Compras',
		]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function edit(Compra $compra)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Compra $compra)
    {
        //
    }
}
