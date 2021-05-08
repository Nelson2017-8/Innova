<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsumoAlmacenado extends Model
{
    protected $table = 'materia_prima_alm';

	protected $fillable = [
	];
    protected $hidden = [
        'almacen_id', 'created_at', 'id', 'materia_prima_id'
    ];
    public function almacen(){
        return $this->belongsTo("App\Almacen");
    }
    public function compraProv(){
        return $this->belongsTo("App\CompraProv");
    }

    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }

}
