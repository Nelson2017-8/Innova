<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $table = 'almacen';
    protected $fillable = [
        'nombre',
		'_sucursal',
        'descripcion',
        'updated_at',
    ];
    protected $hidden = [
        'sucursal_id', 'created_at', 'id'
    ];
    public function sucursal(){
        return $this->belongsTo("App\Sucursal");
    }
    public function insumosComprados(){
        return $this->hasMany("App\InsumoComprado");
    }
    public function productosElaborados(){
        return $this->hasMany("App\ProductoElaborado");
    }
    public function subcategoriasAlm(){
        return $this->hasMany("App\SubcategoriaAlm");
    }
    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
}
