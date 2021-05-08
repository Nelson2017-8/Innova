<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchTableController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidController;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TheSeer\Tokenizer\Token;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
	private $table = 'users';
	private $data = [
		'titleForm' => 'Mostrar Usuarios',
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
			'titleCard' => 'Mostrar todos los usuarios',
			'icoCard' => 'fas fa-users',
		],
		'Relacionado' => [],
		'relations' => false,
	];
	private $form = [
		'method' => 'POST',
		'class' => 'validate',
		'select' => [
			0 => [
				'title' => 'Tipo de Usuario',
				'name' => 'typeuser',
				'id' => 'typeuser',
				'option' => [
					'' => 'SELECCIONE UNA OPCIÓN',
					'Root' => 'Administrador',
					'Admin' => 'Moderador',
				],
				'attr' => [
					'require' => '',
				],
			],
		],

		'input' => [
			0 => [
				'name' => 'username',
				'id' => 'username',
				'col' => 'col-sm-12',
				'title' => 'Nombre de Usuario',
				'attr' => [
					'placeholder' => 'Introduzca un nombre de usuario',
					'require' => '',
				],
			],
			1 => [
				'name' => 'email',
				'id' => 'email',
				'col' => 'col-sm-12',
				'title' => 'Correo',
				'type' => 'email',
				'attr' => [
					'placeholder' => 'Introduzca un correo electrónico',
					'require' => '',
				],
			],
			2 => [
				'name' => 'password',
				'id' => 'password',
				'col' => 'col-sm-6',
				'type' => 'password',
				'title' => 'Contraseña',
				'attr' => [
					'placeholder' => '********',
				],
			],
			3 => [
				'name' => 'password2',
				'id' => 'password2',
				'type' => 'password',
				'col' => 'col-sm-6',
				'title' => 'Repetir Contraseña',
				'attr' => [
					'placeholder' => '********',
				],
			],

		],
	];

	public function index(Request $request)
	{

		$tabla = null;
		$data = $this->data;
		$inputsTable = User::All()[0]->getFillable();
		$columns = [
			'Nombre de Usuario',
			'Correo Electrónico',
			'Tipo de Usuario',
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

		if ($request->t === $this->table) {
			if ($orderByIsNULL) {
				$tabla = SearchTableController::orderBy($request->orderBy, $numberPag,
					function ($campo, $coincidencia, $numberPag) {
						return User::orderBy($coincidencia, 'asc')->paginate($numberPag);
					}, $inputsTable);
			} else if ($searchIsNULL) {
				if ($inputIsNULL) {
					// TENEMOS UNA BUSQUEDA POR CAMPO
					$tabla =
						SearchTableController::searchWhere(
							[$request->input],
							$request->search,
							$numberPag, function ($campo, $value, $numberPag) {
							return User::where($campo, $value)->paginate($numberPag
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
								return User::where($campo, $value)->paginate($numberPag
								);
							});
				}
			}
		}

		$tabla = ($tabla == null) ? User::orderBy('updated_at', 'desc')
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


	public function create()
	{
		$forms = $this->form;
		$forms['action'] = route('users.store');
		$forms['btn']['title'] = 'Registrar';
		$data = $this->data;
		$data['relations'] = true;
		$data['Relacionado'][0] = 'show usuario';
		$data['Relacionado'][1] = 'show cliente';
		$data['Relacionado'][2] = 'show proveedores';
		$title = 'Registrar Usuario';

		return view('dashboard._api.forms.create', [
			'tabla' => $this->table,
			'data' => $data,
			'forms' => $forms,
			'title' => $title,

		]);
	}


	public function store(UserRequest $request)
	{
		if ( UserController::unsing($request) === true ) {
			$respuesta = ['messenger' => 'El Usuario ha sido registrado correctamente', 'result' => 'success'];
		}else{
			$respuesta = ['messenger' => 'Ha ocurrido un error', 'result' => 'danger'];
		}
		return JsonResponse::create($respuesta);
	}


	public function show(User $user)
	{

	}


	public function edit(User $user)
	{
		$forms = $this->form;
		$forms['action'] = route('users.update', $user->id);
		$forms['data-update'] = route('users.index');
		$forms['btn']['title'] = 'Actualizar';
		$forms['method'] = 'PUT';
		$forms['select'][0]['attr']['data-value'] = $user->typeuser;
		$forms['input'][0]['attr']['data-value'] = $user->username;
		$forms['input'][1]['attr']['data-value'] = $user->email;
		$data = $this->data;
		$data['relations'] = true;
		$data['Relacionado'][0] = 'register usuario';
		$data['Relacionado'][1] = 'show usuario';
		$data['Relacionado'][2] = 'show cliente';

		$title = 'Actualizar "' . $user->username . '"';

		return view('dashboard._api.forms.edit', [
			'title' => $title,
			'forms' => $forms,
			'data' => $data,
			'tabla' => $this->table,
		]);


	}


	public function update(UserUpdateRequest $request, User $user)
	{
		// SI EL CAMPO ES NULL-VACIO NO ACTUALIZA
		$user->username = ValidController::emptyDefault($request->username, $user->username);
		$user->email = ValidController::emptyDefault($request->email, $user->email);
		$user->password = ValidController::emptyDefault(md5($request->password), md5($user->password));
		$user->typeuser = ValidController::emptyDefault($request->typeuser, $user->typeuser);
		// GUARDO
		$user->save();
		// NOTIFICO QUE LA OPERACIÓN FUE EXITOSA
		$messenger = 'El usuario "' . $user->username . '" ha sido actualizado';
		$_SESSION['notifications'] = array('success' => $messenger);
		$respuesta = ['messenger' => $messenger, 'result' => 'success'];
		return JsonResponse::create($respuesta);
	}


	public function destroy(User $user)
	{
		$messenger = 'El usuario "' . $user->username . '" ha sido eliminado';
		$user->delete();
		// NOTIFICO QUE LA OPERACIÓN FUE EXITOSA
		$_SESSION['notifications'] = array('success' => $messenger);
		return 'success';
	}
}
