<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    protected $table = 'subcategoria';
    protected $fillable = [
		'nombre',
		'_categoria', //NOMBRE QUE LE DOY PARA IMPRIMIR item->categoria->nombre
		'descripcion',
		'updated_at',
    ];
    protected $hidden = [
        'categoria_id', 'created_at', 'id'
    ];

    public function categoria(){
        return $this->belongsTo("App\Categoria");
    }
    public function materiasPrimas(){
        return $this->hasMany("App\MateriaPrima");
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
