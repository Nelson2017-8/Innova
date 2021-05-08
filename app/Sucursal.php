<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursal';
    protected $fillable = [
        'nombre',
        'ubicacion',
		'updated_at'
    ];

	protected $hidden = [
		'created_at', 'id'
	];

	public function almacenes(){
        return $this->hasMany("App\Almacen");
    }

    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
}
