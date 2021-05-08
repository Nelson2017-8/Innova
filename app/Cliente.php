<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
	protected $hidden = [
        'created_at', 'id'
    ];
	protected $fillable = [
		'primerNombre',
		'primerApellido',
		'cedula',
		'correo',
		'telefono_1',
		'telefono_2',
		'direccion',
		'updated_at',
	];
	public function facturas(){
        return $this->hasMany("App\FacturaCliente");
    }
    public function presupuestos(){
        return $this->hasMany("App\Presupuesto");
    }

    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
}
