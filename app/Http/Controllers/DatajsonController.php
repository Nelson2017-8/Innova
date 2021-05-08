<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuthenticateController;
use App\Vendedor;
use App\Cliente;
use App\User;
use App\Proveedor;
use App\Sucursal;
use App\Categoria;
use App\Subcategoria;
use App\InsumoComprado;
use App\Almacen;
use Carbon\Carbon;

class DatajsonController extends Controller
{
	protected $user = array();
    protected $auth = '';
    protected $authRoute = 'login';

    // REGISTRA LA VARIABLE $this->user Y COMIENZA LA SESSIÓN
    public function __construct(){
        $this->auth = new AuthenticateController();
        $this->user = $this->auth->start();
    }

    // VISTA DE INSERTAR: CUANDO UN CLIENTE SOLICITA UN PRESUPUESTO
    public function clientJson() {
        // NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return false; }

        $client = Cliente::all();
		$clientes = array();
		// $clientes = '';
        foreach ($client as $key) {
        	if ($key !== '') {
		        $clientes[] = array('nombre' => $key->primerNombre, 'apellido' => $key->primerApellido, 'cedula' => $key->cedula);
        	}
        }
        return json_encode($clientes);
    }
    public function users() {
        // NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return false; }

        $use = User::all();
        $uses = array();
        foreach ($use as $key) {
            if ($key !== '') {
                $uses['Nombre'] [] =  $key->username;
                $uses['Correo'][] =  $key->email;
                $uses['Se registro'][] =  Carbon::parse($key->dateInit)->format('d/m/Y');
            }
        }
        $uses['Nombre'] = array_unique($uses['Nombre']) ;
        $uses['Correo'] =  array_unique($uses['Correo']) ;
        $uses['Se registro'] =  array_unique($uses['Se registro']) ;
        return json_encode($uses);
    }

    public function suppliers() {
        // NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return false; }

        $use = Proveedor::all();
        $uses = array();
        foreach ($use as $key) {
            if ($key !== '') {
                $uses['Nombre'][] =  $key->nombre;
                $uses['Razon'][] =  $key->razonSocial;
                $uses['Correo'][] =  $key->correo;
                $uses['Código'][] =  $key->cod_proveedor;
                $uses['Se registro'][] =  Carbon::parse($key->fechaRegistro)->format('d/m/Y');
            }
        }
        $uses['Nombre'] = ['nombre', array_unique($uses['Nombre'])];
        $uses['Razon'] = ['razonSocial', array_unique($uses['Razon'])];
        $uses['Código'] = ['cod_proveedor', array_unique($uses['Código'])];
        $uses['Correo'] = ['correo', array_unique($uses['Correo'])];
        $uses['Se registro'] = ['fechaRegistro', array_unique($uses['Se registro'])];
        return json_encode($uses);
    }


    public function customers() {
        // NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return false; }

        $use = Cliente::all();
        $uses = array();
        foreach ($use as $key) {
            if ($key !== '') {
				$uses['Apellido'][] =  $key->primerApellido;
				$uses['Nombre'][] =  $key->primerNombre;
				$uses['Cédula'][] =  $key->cedula;
				$uses['Correo'][] = $key->correo;
			}
        }

        $uses['Nombre'] = ['primerNombre', array_unique($uses['Nombre'])];
        $uses['Apellido'] = ['primerApellido', array_unique($uses['Apellido'])];
        $uses['Cédula'] = ['cedula', array_unique($uses['Cédula'])];
        $uses['Correo'] = ['correo', array_unique($uses['Correo'])];
        return json_encode($uses);
    }

    public function sellers() {
        // NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return false; }

        $use = Vendedor::all();
        $uses = array();
        foreach ($use as $key) {
            if ($key !== '') {
                $uses['Nombre'][] =  $key->primerNombre;
                $uses['Apellido'][] =  $key->primerApellido;
                $uses['Cédula'][] =  $key->cedula;
                $uses['Correo'][] = $key->correo;
            }
        }

        $uses['Nombre'] = ['primerNombre', array_unique($uses['Nombre'])];
        $uses['Apellido'] = ['primerApellido', array_unique($uses['Apellido'])];
        $uses['Cédula'] = ['cedula', array_unique($uses['Cédula'])];
        $uses['Correo'] = ['correo', array_unique($uses['Correo'])];
        return json_encode($uses);
    }
    public function branch() {
        // NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return false; }

        $use = Sucursal::all();
        $uses = array();
        foreach ($use as $key) {
            if ($key !== '') {
                $uses['Nombre'][] =  $key->nombre;
                $uses['Ubicación'][] =  $key->ubicacion;
                $uses['Fecha de Creación'][] = Carbon::parse( $key->created_at )->format('d/m/Y');
                $uses['Fecha de Actualización'][] = Carbon::parse( $key->updated_at )->format('d/m/Y');
            }
        }

        $uses['Nombre'] = ['nombre', array_unique($uses['Nombre'])];
        $uses['Ubicación'] = ['ubicacion', array_unique($uses['Ubicación'])];
        $uses['Fecha de Creación'] = ['created_at', array_unique($uses['Fecha de Creación'])];
        $uses['Fecha de Actualización'] = ['updated_at', array_unique($uses['Fecha de Actualización'])];
        return json_encode($uses);
    }

    public function category() {
        // NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return false; }

        $use = Categoria::all();
        $uses = array();
        foreach ($use as $key) {
            if ($key !== '') {
                $uses['Nombre'][] =  $key->nombre;
                $uses['Descripción'][] =  $key->descripcion;
                $uses['Tipo'][] = $key->type;
            }
        }

        $uses['Nombre'] = ['nombre', array_unique($uses['Nombre'])];
        $uses['Descripción'] = ['description', array_unique($uses['Descripción'])];
        $uses['Tipo'] = ['type', array_unique($uses['Tipo'])];
        return json_encode($uses);
    }
    public function subcategory() {
        // NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return false; }

        $use = Subcategoria::all();
        $uses = array();
        foreach ($use as $key) {
            if ($key !== '') {
                $uses['Nombre'][] =  $key->nombre;
                $uses['Descripción'][] =  $key->descripcion;
            }
        }

        $uses['Nombre'] = ['nombre', array_unique($uses['Nombre'])];
        $uses['Descripción'] = ['description', array_unique($uses['Descripción'])];
        return json_encode($uses);
    }

    public function rawMaterial() {
        // NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return false; }

        $use = InsumoComprado::all();
        $uses = array();
        foreach ($use as $key) {
            if ($key !== '') {
                $uses['Nombre'][] =  $key->nombre;
                $uses['Código de Barras'][] =  $key->codigo_barras;
                $uses['Precio'][] =  $key->precioUnitarioCotizacion;
            }
        }

        $uses['Nombre'] = ['nombre', array_unique($uses['Nombre'])];
        $uses['Código de Barras'] = ['codigo_barras', array_unique($uses['Código de Barras'])];
        $uses['Precio'] = ['precioUnitarioCotizacion', array_unique($uses['Precio'])];
        return json_encode($uses);
    }

    public function warehouse() {
        // NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return false; }

        $use = Almacen::all();
        $uses = array();
        foreach ($use as $key) {
            if ($key !== '') {
                $uses['Nombre'][] =  $key->nombre;
            }
        }

        $uses['Nombre'] = ['nombre', array_unique($uses['Nombre'])];
        return json_encode($uses);
    }


}
