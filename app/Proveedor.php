<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $fillable = [
        'nombre',
        'razonSocial',
		'correo',
        'telefono_1',
        'telefono_2',
		'cod_postal',
		'direccion',
		'updated_at',
	];
    protected $hidden = [
        'created_at', 'id'
    ];
    public function compras(){
        return $this->hasMany("App\Compra");
    }

    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
}
