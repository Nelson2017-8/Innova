<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\ValidController;
use App\Http\Controllers\SearchTableController;

class CategoryController extends Controller
{
    protected $user = array();
    protected $auth = '';
    protected $authRoute = 'login';

    // REGISTRA LA VARIABLE $this->user Y COMIENZA LA SESSIÓN
    public function __construct(){
        $this->auth = new AuthenticateController();
        $this->user = $this->auth->start();
    }
    

    // VISTA DE CONSULTAR CATEGORIAS: EXTRAE LOS DATOS NECESARIO A LA VISTA.
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

        $category = null;
        // NUMERO DE PAGINACION
        $numberPag = SearchTableController::checkPaginate($request->numberPag);
        $orderByIsNULL = ValidController::inputIsNull($request->orderBy);
        $inputIsNULL = ValidController::inputIsNull($request->input);
        $searchIsNULL = ValidController::inputIsNull($request->search);

        if ( $request->t === 'categoria') {
            if ( $orderByIsNULL ) {

                $category = SearchTableController::orderBy($request->orderBy, $numberPag, function ($campo, $coincidencia, $numberPag) {
                    return Categoria::orderBy($coincidencia, 'asc')->paginate($numberPag);
                }, 'nombre','descripcion', 'type');
                
            }
            else if ( $searchIsNULL ) {
                
                if ( $inputIsNULL ) {
                   // TENEMOS UNA BUSQUEDA POR CAMPO
                    $category = 
                    SearchTableController::searchWhere(
                        [$request->input], 
                        $request->search,
                        $numberPag, function ($campo, $value, $numberPag) {
                            return Categoria::where($campo, $value)->paginate($numberPag
                        );
                    });
                
                }else{
                    // NO HAY CAMPO ESPECIFICADO
                    $category = 
                    SearchTableController::searchWhere(
                        ['nombre','descripcion', 'type'], 
                        $request->search, 
                        $numberPag, 
                        function ($campo, $value, $numberPag) { // BUSQUEDA 1
                            return Categoria::where($campo, $value)->paginate($numberPag
                        );
                    });
                }
            }
        }

        $category = ($category == null) ? Categoria::orderBy('updated_at', 'desc')->paginate($numberPag) : $category;


        return view('dashboard.category.query', 
            [
                'user' => $this->user, 
                'notifications' => $this->auth->notifications(), 
                'category' => $category,
            ]
        );
    }

    // VISTA DE CREAR CategoriaES NUEVO, SOLO TIPO ROOT
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

        return view('dashboard.category.create', 
            [
                'user' => $this->user, 
                'notifications' => $this->auth->notifications(),
            ]
        );

    }

    // PROCESO DE REGISTRO DE CATEGORIA: SOLO USUARIO ROOT. REGISTRA, 
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
        $category = new Categoria($request->all());
        $category->descripcion = ValidController::emptyString($request->descripcion);
        $category->save();
        $respuesta = ['messenger' => 'La categoria ha sido registrado con éxito', 'result' => 'success'];
        return json_encode($respuesta);
    }

    // PROCESO DE ELIMINACIÓN DE Categoria: SOLO USUARIO ROOT. ELIMINA, REDIMENCIONA Y 
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
        // ID DEL USUARIO DESENCRIPTADO
        $id = TokenController::desencriptar($id);
        Categoria::find($id)->delete();
        $_SESSION['notifications'] = array( 'warning' => 'La Categoria ha sido eliminado');
        return redirect()->route('dashboard.category.query');

    }

    // VISTA DE ACTUALIZACIÓN DE Categoria: REQUERIDO SER USUARIO TIPO ROOT
    public function viewUpdate($id) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        if ($this->auth->isRoot() === false) {

            $_SESSION['notifications'] = array(
                'error' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
            );
            return redirect()->route('dashboard.index');

        }
        
        // ID DEL USUARIO DESENCRIPTADO
        $_id = TokenController::desencriptar($id);
        return view('dashboard.category.update', [
            'id' => $id,
            'user' => $this->user, 
            'category' => Categoria::find($_id),
        ]);

    }

    // PROCESO DE ACTUALIZACIÓN DE Categoria: SOLO USUARIO ROOT
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
        $categoria = Categoria::find($id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = ValidController::emptyString($request->descripcion);
        $categoria->type = ValidController::emptyDefault($request->type, $categoria->type);
        $categoria->save();
        
        $respuesta = ['messenger' => 'La Categoria ha sido actualizada', 'result' => 'success'];
        return json_encode($respuesta);
    }
}
