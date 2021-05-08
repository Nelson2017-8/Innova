<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria';
    protected $fillable = [
        'nombre',
        'descripcion',
		'updated_at',
    ];
	protected $hidden = [
		'created_at', 'id'
	];
    public function subcategorias(){
        return $this->hasMany("App\Subcategoria");
    }
    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
}
