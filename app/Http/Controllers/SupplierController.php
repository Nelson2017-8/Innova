<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\AuthenticateController;
use App\Http\Requests\ProveedorRequest;
use App\Http\Controllers\ValidController;
use App\Http\Controllers\SearchTableController;

class SupplierController extends Controller
{
    protected $user = array();
    protected $auth = '';
    protected $authRoute = 'login';

    // REGISTRA LA VARIABLE $this->user Y COMIENZA LA SESSIÓN
    public function __construct(){
        $this->auth = new AuthenticateController();
        $this->user = $this->auth->start();
    }



    // VISTA DE CONSULTAR PROVEEDORES: EXTRAE LOS DATOS NECESARIO A LA VISTA.
    // SI SE ENVIA UNA NOTIFICACIÓN HASTA AQUÍ LA MUESTRA
    public function show(Request $request) {

        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }


        $suppliers = null;
        // NUMERO DE PAGINACION
        $numberPag = SearchTableController::checkPaginate($request->numberPag);
        $orderByIsNULL = ValidController::inputIsNull($request->orderBy);
        $inputIsNULL = ValidController::inputIsNull($request->input);
        $searchIsNULL = ValidController::inputIsNull($request->search);
        $inputsTables = [
			'nombre',
			'correo',
			'razonSocial',
			'direccion',
			'cod_postal',
			'telefono_1',
			'telefono_2',
			'updated'
		];

        if ( $request->t === 'proveedores') {
            if ( $orderByIsNULL ) {

                $suppliers = SearchTableController::orderBy($request->orderBy, $numberPag, function ($campo, $coincidencia, $numberPag) {
                    return Proveedor::orderBy($coincidencia, 'desc')->paginate($numberPag);
                }, $inputsTables);

            }
            else if ( $searchIsNULL ) {

                if ( $inputIsNULL ) {
                   // TENEMOS UNA BUSQUEDA POR CAMPO
                    $suppliers =
                    SearchTableController::searchWhere(
                        [$request->input],
                        $request->search,
                        $numberPag, function ($campo, $value, $numberPag) {
                            return Proveedor::where($campo, $value)->paginate($numberPag
                        );
                    });

                }else{
                    // NO HAY CAMPO ESPECIFICADO
                    $suppliers =
                    SearchTableController::searchWhere(
						$inputsTables.
                        $request->search,
                        $numberPag,
                        function ($campo, $value, $numberPag) { // BUSQUEDA 1
                            return Proveedor::where($campo, $value)->paginate($numberPag
                        );
                    });
                }
            }
        }

        $suppliers = ($suppliers == null) ? Proveedor::orderBy('updated_at', 'desc')->paginate($numberPag) : $suppliers;

        return view('dashboard.suppliers.query',
            [
                'user' => $this->user,
                'suppliers' => $suppliers,
                'notifications' => $this->auth->notifications(),
            ]
        );
    }

    // VISTA DE CREAR PROVEEDORES NUEVO,
    // SI SE ENVIA UNA NOTIFICACIÓN HASTA AQUÍ LA MUESTRA
    public function index() {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        return view('dashboard.suppliers.create',
            [
                'user' => $this->user,
                'notifications' => $this->auth->notifications(),
            ]
        );
    }

    // PROCESO DE REGISTRO DE PROVEEDORES: REGISTRA, REDIMENCIONA A LA VISTA DE CREAR PROVEEDOR
    // Y ENVIA UNA NOTIFICACIÓN
    public function store(ProveedorRequest $request) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }


        $supplier = new Proveedor($request->all());
        $supplier->correo = $request->correo;
		$supplier->fechaRegistro = date("Y-m-d H:i:s");
		$supplier->cod_proveedor = TokenController::obtenToken(10);

		if ($request->telefono_2 == NULL) {
			$supplier->telefono_2 = '';
		}else{
			$supplier->telefono_2 = $request->telefono_2;
		}

        $supplier->save();
        $respuesta = ['messenger' => 'El Proveedor ha sido registrado correctamente', 'result' => 'success'];

        return json_encode($respuesta);

    }

    // PROCESO DE ELIMINACIÓN DE PROVEEDOR: SOLO CLIENTE ROOT. ELIMINA, REDIMENCIONA Y
    // ENVIA UNA NOTIFICACIÓN A LA VISTA CONSULTA
    public function destroy($id) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }


        if ($this->auth->isRoot() === false) {

            $_SESSION['notifications'] = array(
                'error' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
            );
            return redirect()->route('dashboard.suppliers.query');

        }
        // ID DEL USUARIO DESENCRIPTADO
        $id = TokenController::desencriptar($id);
        Proveedor::find($id)->delete();
        $_SESSION['notifications'] = array('warning' => 'El proveedor ha sido eliminado');
        return redirect()->route('dashboard.suppliers.query');

    }

    // VISTA DE ACTUALIZACIÓN DE PROVEEDOR: REQUERIDO SER USUARIO TIPO ROOT
    public function viewUpdate($id) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }


        if ($this->auth->isRoot() === false) {

            $_SESSION['notifications'] = array(
                'error' => 'No tienes permisos necesario para realizar esta operación'
            );
            return redirect()->route('dashboard.suppliers.query');

        }

        // ID DEL USUARIO DESENCRIPTADO
        $_id = TokenController::desencriptar($id);
        $suppliers = Proveedor::find($_id);
        return view('dashboard.suppliers.update', [
            'id' => $id,
            'user' => $this->user,
            'suppliers' => $suppliers,
        ]);

    }

    // PROCESO DE ACTUALIZACIÓN DE PROVEEDOR: SOLO USUARIO ROOT
    public function update(Request $request, $id) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }


        if ($this->auth->isRoot() === false) {

            $respuesta = ['messenger' => 'No tienes permisos necesario para realizar esta operación', 'result' => 'danger'];
            return json_encode($respuesta);

        }
        // ID DEL USUARIO DESENCRIPTADO
        $id = TokenController::desencriptar($id);
        $cliente = Proveedor::find($id);
        $cliente->nombre = $request->nombre;
        $cliente->razonSocial = $request->razonSocial;
        $cliente->direccion = $request->direccion;
        $cliente->cod_postal = $request->cod_postal;
        $cliente->telefono_1 = $request->telefono_1;
		$cliente->correo = $request->correo;

        if ($request->telefono_2 != NULL) {
			$cliente->telefono_2 = $request->telefono_2;
		}

        $cliente->save();

        $respuesta = ['messenger' => 'El Proveedor ha sido actualizado', 'result' => 'success'];
        return json_encode($respuesta);

    }
}
