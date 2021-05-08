<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\ValidController;
use App\Http\Controllers\SearchTableController;
use App\Sucursal;

class BranchController extends Controller
{
    protected $user = array();
    protected $auth = '';
    protected $authRoute = 'login';

    // REGISTRA LA VARIABLE $this->user Y COMIENZA LA SESSIÓN
    public function __construct(){
        $this->auth = new AuthenticateController();
        $this->user = $this->auth->start();
    }  
    

    // VISTA DE CONSULTAR SUCURSAL: EXTRAE LOS DATOS NECESARIO A LA VISTA.
    // SI SE ENVIA UNA NOTIFICACIÓN HASTA AQUÍ LA MUESTRA
    public function show(Request $request) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        if ($this->auth->isRoot() === false) {

            $_SESSION['notifications'] = array(
                'error' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
            );
            return redirect()->route('dashboard.index');

        }

        $branch = null;
        // NUMERO DE PAGINACION
        $numberPag = SearchTableController::checkPaginate($request->numberPag);
        $orderByIsNULL = ValidController::inputIsNull($request->orderBy);
        $inputIsNULL = ValidController::inputIsNull($request->input);
        $searchIsNULL = ValidController::inputIsNull($request->search);

        if ( $request->t === 'sucursal') {
            if ( $orderByIsNULL ) {

                $branch = SearchTableController::orderBy($request->orderBy, $numberPag, function ($campo, $coincidencia, $numberPag) {
                    return Sucursal::orderBy($coincidencia, 'asc')->paginate($numberPag);
                }, 'nombre','ubicacion', 'razonSocial', 'created_at', 'updated_at');
                
            }
            else if ( $searchIsNULL ) {
                
                if ( $inputIsNULL ) {
                   // TENEMOS UNA BUSQUEDA POR CAMPO
                    $branch = 
                    SearchTableController::searchWhere(
                        [$request->input], 
                        $request->search,
                        $numberPag, function ($campo, $value, $numberPag) {
                            return Sucursal::where($campo, $value)->paginate($numberPag
                        );
                    });
                
                }else{
                    // NO HAY CAMPO ESPECIFICADO
                    $branch = 
                    SearchTableController::searchWhere(
                        ['nombre','ubicacion', 'razonSocial', 'created_at', 'updated_at'], 
                        $request->search, 
                        $numberPag, 
                        function ($campo, $value, $numberPag) { // BUSQUEDA 1
                            return Sucursal::where($campo, $value)->paginate($numberPag
                        );
                    });
                }
            }
        }

        $branch = ($branch == null) ? Sucursal::orderBy('updated_at', 'desc')->paginate($numberPag) : $branch;


        return view('dashboard.branch.query', 
            [
                'user' => $this->user, 
                'notifications' => $this->auth->notifications(), 
                'branch' => $branch,
            ]
        );

    }

    // VISTA DE CREAR SUCURSAL, SOLO TIPO ROOT
    // SI SE ENVIA UNA NOTIFICACIÓN HASTA AQUÍ LA MUESTRA
    public function index() {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        if ($this->auth->isRoot() === false) {

            $_SESSION['notifications'] = array(
                'error' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
            );
            return redirect()->route('dashboard.index');

        }


        return view('dashboard.branch.create', 
            [
                'user' => $this->user, 
                'notifications' => $this->auth->notifications(), 
            ]
        );

    }

    // PROCESO DE REGISTRO DE SUCURSAL: SOLO USUARIO ROOT. REGISTRA, 
    // REDIMENCIONA A LA VISTA DE CREAR branch Y ENVIA UNA NOTIFICACIÓN
    public function store(Request $request) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        if ($this->auth->isRoot() === false) {

            $_SESSION['notifications'] = array(
                'error' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
            );
            return redirect()->route('dashboard.index');

        }

        $branch = new Sucursal($request->all());
        $branch->save();
            
        $respuesta = ['messenger' => 'La sucursal ha sido registrado con éxito', 'result' => 'success'];
        return json_encode($respuesta);


    }

    // PROCESO DE ELIMINACIÓN DE SUCURSAL: SOLO USUARIO ROOT. ELIMINA, REDIMENCIONA Y 
    // ENVIA UNA NOTIFICACIÓN A LA VISTA CONSULTA
    public function destroy($id) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        if ($this->auth->isRoot() === false) {

            $_SESSION['notifications'] = array(
                'error' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
            );
            return redirect()->route('dashboard.index');

        }
        
        $id = TokenController::desencriptar($id);
        Sucursal::find($id)->delete();
        $_SESSION['notifications'] = array( 'warning' => 'La Sucursal ha sido eliminado');
        return redirect()->route('dashboard.branch.query');

    }

    // VISTA DE ACTUALIZACIÓN DE SUCURSAL: REQUERIDO SER USUARIO TIPO ROOT
    public function viewUpdate($id) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        if ($this->auth->isRoot() === false) {
            $_SESSION['notifications'] = array(
                'error' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
            );
            return redirect()->route('dashboard.index');
        }
        $_id = TokenController::desencriptar($id);
        return view('dashboard.branch.update', [
            'id' => $id,
            'branch' => Sucursal::find($_id),
            'user' => $this->user, 
        ]);
    }

    // PROCESO DE ACTUALIZACIÓN DE SUCURSAL: SOLO USUARIO ROOT
    public function update(Request $request, $id) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }


        if ($this->auth->isRoot() === false) {
            $_SESSION['notifications'] = array(
                'error' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
            );
            return redirect()->route('dashboard.index');
        }
        
        // ID DEL USUARIO DESENCRIPTADO
        $id = TokenController::desencriptar($id);
        $branch = Sucursal::find($id);
        $branch->nombre = $request->nombre;
        $branch->ubicacion = $request->ubicacion;
        $branch->save();
        
        $respuesta = ['messenger' => 'La Sucursal ha sido actualizada', 'result' => 'success'];
        return json_encode($respuesta);
    }
}
