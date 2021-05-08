<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subcategoria;
use App\InsumoComprado;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\AuthenticateController;
use App\Http\Requests\RawMaterialRequest;
use App\Http\Controllers\ImageController;
// import the storage facade
use Illuminate\Support\Facades\Storage;

class RawMaterialController extends Controller
{
	protected $user = array();
	protected $auth = '';
    protected $authRoute = 'login';

    // REGISTRA LA VARIABLE $this->user Y COMIENZA LA SESSIÓN
    public function __construct(){
    	$this->auth = new AuthenticateController();
    	$this->user = $this->auth->start();
    }


    // VISTA DE CONSULTAR MATERIA PRIMA: EXTRAE LOS DATOS NECESARIO A LA VISTA.
    // SI SE ENVIA UNA NOTIFICACIÓN HASTA AQUÍ LA MUESTRA
    public function show(Request $request) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        $raw_material = null;
        // NUMERO DE PAGINACION
        $numberPag = SearchTableController::checkPaginate($request->numberPag);
        $orderByIsNULL = ValidController::inputIsNull($request->orderBy);
        $inputIsNULL = ValidController::inputIsNull($request->input);
        $searchIsNULL = ValidController::inputIsNull($request->search);

        if ( $request->t === 'categoria') {
            if ( $orderByIsNULL ) {

                $raw_material = SearchTableController::orderBy($request->orderBy, $numberPag, function ($campo, $coincidencia, $numberPag) {
                    return InsumoComprado::orderBy($coincidencia, 'asc')->paginate($numberPag);
                }, 'nombre','detalles', 'codigo_barras', 'precioUnitarioCotizacion', 'updated_at', 'subcategoria_id');

            }
            else if ( $searchIsNULL ) {

                if ( $inputIsNULL ) {
                   // TENEMOS UNA BUSQUEDA POR CAMPO
                    $raw_material =
                    SearchTableController::searchWhere(
                        [$request->input],
                        $request->search,
                        $numberPag, function ($campo, $value, $numberPag) {
                            return InsumoComprado::where($campo, $value)->paginate($numberPag
                        );
                    });

                }else{
                    // NO HAY CAMPO ESPECIFICADO
                    $raw_material =
                    SearchTableController::searchWhere(
                        ['nombre','detalles', 'codigo_barras', 'precioUnitarioCotizacion', 'updated_at', 'subcategoria_id'],
                        $request->search,
                        $numberPag,
                        function ($campo, $value, $numberPag) { // BUSQUEDA 1
                            return InsumoComprado::where($campo, $value)->paginate($numberPag
                        );
                    });
                }
            }
        }

        $raw_material = ($raw_material == null) ? InsumoComprado::orderBy('updated_at', 'desc')->paginate($numberPag) : $raw_material;


        return view('dashboard.raw_material.query',
            [
                'user' => $this->user,
                'notifications' => $this->auth->notifications(),
                'subcategory' => Subcategoria::all(),
                'raw_material' => $raw_material,
            ]
        );
    }

    // VISTA DE CREAR MATERIA PRIMA
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


        return view('dashboard.raw_material.create',
        	[
        		'user' => $this->user,
        		'notifications' => $this->auth->notifications(),
        		'subcategory' => Subcategoria::all(),
        	]
        );

    }

    // PROCESO DE REGISTRO DE MATERIA PRIMA: REGISTRA,
    // REDIMENCIONA A LA VISTA DE CREAR CLIENTE Y ENVIA UNA NOTIFICACIÓN
    public function store(RawMaterialRequest $request) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        $name = $request->nombre;
		// $paths =  ImageController::upload( $request->file('imagenes'), $request->nombre );


        $raw_material = new InsumoComprado($request->all());
        $raw_material->subcategoria_id = TokenController::desencriptar($request->subcategory);
		// $raw_material->imagenes = $paths;

        $raw_material->codigo_barras = ValidController::emptyDefault($request->codigo_barras, TokenController::obtenToken(8));
        $raw_material->detalles = ValidController::emptyDefault($request->detalles, '');

        $raw_material->save();

		// echo '<img src="'. asset( 'storage/'.trim($paths, '|') ) .'" alt="'.$folder.'">';
        // return redirect()->route('dashboard.raw_material.create');
        $respuesta = ['messenger' => 'Operación exitosa', 'result' => 'success'];
        return json_encode($respuesta);

    }

    // PROCESO DE ELIMINACIÓN DE MATERIA PRIMA: SOLO USUARIO ROOT. ELIMINA, REDIMENCIONA Y
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
        $raw_material = InsumoComprado::find($id);
        // $imagenes = explode('|', $raw_material->imagenes);
        // $folder = 'img/'.str_replace(' ', '_', $raw_material->nombre);

	    // Storage::delete($imagenes);
	    // Storage::deleteDirectory($folder);

        $raw_material->delete();

        $_SESSION['notifications'] = array( 'warning' => 'Se ha eliminado el registro');

        return redirect()->route('dashboard.raw_material.query');


    }

    // VISTA DE ACTUALIZACIÓN DE MATERIA PRIMA
    public function viewUpdate($id) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        // ID DEL USUARIO DESENCRIPTADO
        $_id = TokenController::desencriptar($id);
        return view('dashboard.raw_material.update', [
            'id' => $id,
            'user' => $this->user,
            'raw_material' =>  InsumoComprado::find($_id),
        	'subcategory' => Subcategoria::all(),
        ]);
    }

    // PROCESO DE ACTUALIZACIÓN DE MATERIA PRIMA
    public function update(Request $request, $id) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        // ID DEL USUARIO DESENCRIPTADO
        $id = TokenController::desencriptar($id);
        $raw_material = InsumoComprado::find($id);
        $raw_material->subcategoria_id = ValidController::emptyDefault($raw_material->subcategoria_id, TokenController::desencriptar($request->subcategory));
        $raw_material->nombre = $request->nombre;
        $raw_material->detalles = ValidController::emptyDefault($request->detalles, '');
    	$raw_material->codigo_barras = ValidController::emptyDefault($request->codigo_barras, TokenController::obtenToken(8));

        $raw_material->save();
        $respuesta = ['messenger' => 'El registro ha sido actualizado con éxito', 'result' => 'success'];
        return json_encode($respuesta);

    }
}
