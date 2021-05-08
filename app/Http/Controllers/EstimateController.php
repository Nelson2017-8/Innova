<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Presupuesto;
use App\Cliente;
use App\Http\Controllers\AuthenticateController;

class EstimateController extends Controller
{
	protected $user = array();
    protected $auth = '';
    protected $authRoute = 'login';

    // REGISTRA LA VARIABLE $this->user Y COMIENZA LA SESSIÓN
    public function __construct(){
        $this->auth = new AuthenticateController();
        $this->user = $this->auth->start();
    }
    
    // VISTA DE CONSULTA: MUESTRA TODOS LOS RESULTADOS DE PRESUPUESTO
    public function show() {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        return view('dashboard.estimate.create', 
            [
                'user' => $this->user, 
                'notifications' => $this->auth->notifications(),
                'clientes' => Cliente::all(),
            ]
        );
    }


    // VISTA DE INSERTAR: CUANDO UN CLIENTE SOLICITA UN PRESUPUESTO
    public function index() {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        if ($this->auth->isRoot() === false) {

            $_SESSION['notifications'] = array(
                'error' => 'No tienes el acceso requerido, se solicita ser usuario Administrador'
            );
            return redirect()->route('dashboard.index');

        }

        $estimate = null;
        // NUMERO DE PAGINACION
        $numberPag = SearchTableController::checkPaginate($request->numberPag);
        $orderByIsNULL = ValidController::inputIsNull($request->orderBy);
        $inputIsNULL = ValidController::inputIsNull($request->input);
        $searchIsNULL = ValidController::inputIsNull($request->search);

        if ( $request->t === 'presupuesto') {
            if ( $orderByIsNULL ) {

                $estimate = SearchTableController::orderBy($request->orderBy, $numberPag, function ($campo, $coincidencia, $numberPag) {
                    return Presupuesto::orderBy($coincidencia, 'asc')->paginate($numberPag);
                }, 'cliente_id','fechaInicial', 'fechaFinal', 'cotizacion', 'aprobado', 'updated_at');
                
            }
            else if ( $searchIsNULL ) {
                
                if ( $inputIsNULL ) {
                   // TENEMOS UNA BUSQUEDA POR CAMPO
                    $estimate = 
                    SearchTableController::searchWhere(
                        [$request->input], 
                        $request->search,
                        $numberPag, function ($campo, $value, $numberPag) {
                            return Presupuesto::where($campo, $value)->paginate($numberPag
                        );
                    });
                
                }else{
                    // NO HAY CAMPO ESPECIFICADO
                    $estimate = 
                    SearchTableController::searchWhere(
                        ['cliente_id','fechaInicial', 'fechaFinal', 'cotizacion', 'aprobado', 'updated_at'], 
                        $request->search, 
                        $numberPag, 
                        function ($campo, $value, $numberPag) { // BUSQUEDA 1
                            return Presupuesto::where($campo, $value)->paginate($numberPag
                        );
                    });
                }
            }
        }

        $estimate = ($estimate == null) ? Presupuesto::orderBy('updated_at', 'desc')->paginate($numberPag) : $estimate;


        return view('dashboard.estimate.query', 
            [
                'user' => $this->user, 
                'notifications' => $this->auth->notifications(), 
                'estimate' => $estimate,
            ]
        );
    }

   

}
