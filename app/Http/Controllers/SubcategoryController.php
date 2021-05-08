<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subcategoria;
use App\Categoria;
use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\TokenController;

class SubcategoryController extends Controller
{
   	protected $user = array();
    protected $auth = '';
    protected $authRoute = 'login';

    // REGISTRA LA VARIABLE $this->user Y COMIENZA LA SESSIÓN
    public function __construct(){
        $this->auth = new AuthenticateController();
        $this->user = $this->auth->start();
    }
    

    // VISTA DE CONSULTAR SUBCATEGORIAS: EXTRAE LOS DATOS NECESARIO A LA VISTA.
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

        $subcategory = null;
        // NUMERO DE PAGINACION
        $numberPag = SearchTableController::checkPaginate($request->numberPag);
        $orderByIsNULL = ValidController::inputIsNull($request->orderBy);
        $inputIsNULL = ValidController::inputIsNull($request->input);
        $searchIsNULL = ValidController::inputIsNull($request->search);

        if ( $request->t === 'subcategoria') {
            if ( $orderByIsNULL ) {

                $subcategory = SearchTableController::orderBy($request->orderBy, $numberPag, function ($campo, $coincidencia, $numberPag) {
                    return Subcategoria::orderBy($coincidencia, 'asc')->paginate($numberPag);
                }, 'nombre','descripcion', 'type');
                
            }
            else if ( $searchIsNULL ) {
                
                if ( $inputIsNULL ) {
                   // TENEMOS UNA BUSQUEDA POR CAMPO
                    $subcategory = 
                    SearchTableController::searchWhere(
                        [$request->input], 
                        $request->search,
                        $numberPag, function ($campo, $value, $numberPag) {
                            return Subcategoria::where($campo, $value)->paginate($numberPag
                        );
                    });
                
                }else{
                    // NO HAY CAMPO ESPECIFICADO
                    $subcategory = 
                    SearchTableController::searchWhere(
                        ['nombre','descripcion', 'type'], 
                        $request->search, 
                        $numberPag, 
                        function ($campo, $value, $numberPag) { // BUSQUEDA 1
                            return Subcategoria::where($campo, $value)->paginate($numberPag
                        );
                    });
                }
            }
        }

        $subcategory = ($subcategory == null) ? Subcategoria::orderBy('updated_at', 'desc')->paginate($numberPag) : $subcategory;


        return view('dashboard.subcategory.query', 
            [
                'user' => $this->user, 
                'notifications' => $this->auth->notifications(), 
                'subcategory' => $subcategory,
            ]
        );
        
    }

    // VISTA DE CREAR SUBCATEGORIAS, SOLO TIPO ROOT
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
        return view('dashboard.subcategory.create', 
        	[
        		'user' => $this->user, 
        		'notifications' => $this->auth->notifications(), 
        		'category' => Categoria::all(),
        	]
        );

    }

    // PROCESO DE REGISTRO DE SUBCATEGORIAS: SOLO USUARIO ROOT. REGISTRA, 
    // REDIMENCIONA A LA VISTA DE CREAR CLIENTE Y ENVIA UNA NOTIFICACIÓN
    public function store(Request $request) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        if ($this->auth->isRoot() === false) {

            $_SESSION['notifications'] = array(
                'error' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
            );
            return redirect()->route('dashboard.index');

        }
        $subcategory = new Subcategoria($request->all());
        $subcategory->categoria_id = TokenController::desencriptar($request->category);
        $subcategory->descripcion = ValidController::emptyString($request->descripcion);
        $subcategory->save();
        $respuesta = ['messenger' => 'La subcategoria ha sido registrado con éxito', 'result' => 'success'];
        return json_encode($respuesta);
    }

    // PROCESO DE ELIMINACIÓN DE SUBCATEGORIAS: SOLO USUARIO ROOT. ELIMINA, REDIMENCIONA Y 
    // ENVIA UNA NOTIFICACIÓN A LA VISTA CONSULTA
    public function destroy($id) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        if ($this->auth->isRoot() === false) {

            $_SESSION['notifications'] = array(
                'success' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
            );
            return redirect()->route('dashboard.index');

        }
        // ID DEL USUARIO DESENCRIPTADO
        $id = TokenController::desencriptar($id);
        Subcategoria::find($id)->delete();
        $_SESSION['notifications'] = array('warning' => 'La Subcategoria ha sido eliminado');

        return redirect()->route('dashboard.subcategory.query');
    }

    // VISTA DE ACTUALIZACIÓN DE SUBCATEGORIAS: REQUERIDO SER USUARIO TIPO ROOT
    public function viewUpdate($id) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }
        
        // ID DEL USUARIO DESENCRIPTADO
        $_id = TokenController::desencriptar($id);
        $subcategory = Subcategoria::find($_id);
        $subcategory->categoria;
        $category = Categoria::all();
        return view('dashboard.subcategory.update', [
            'id' => $id,
            'subcategory' => $subcategory,
            'user' => $this->user, 
        	'category' => $category,
        ]);
    }

    // PROCESO DE ACTUALIZACIÓN DE SUBCATEGORIAS: SOLO USUARIO ROOT
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
        $subcategory = Subcategoria::find($id);
        if ($request->category != '' && $request->category != NULL) {
            $subcategory->categoria_id = TokenController::desencriptar($request->category);
        }
        $subcategory->nombre = $request->nombre;
        $subcategory->descripcion = ValidController::emptyString($request->descripcion);
        $subcategory->save();
        
        $respuesta = ['messenger' => 'La subcategoria ha sido actualizada con éxito', 'result' => 'success'];
        return json_encode($respuesta);

    }
}
