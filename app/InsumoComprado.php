<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsumoComprado extends Model
{
    protected $table = 'insumos_comprados';
    protected $fillable = [
        'nombre',
		'cantidad',
		'precioUnitario',
		'detalles',
        'codigo_barras',
    ];
    protected $hidden = [
        'compra_id', 'subcategoria_id', 'id', 'created_at'
    ];
    public function compra(){
        return $this->belongsTo("App\Compra");
    }
    public function subcategoria(){
        return $this->belongsTo("App\Subcategoria");
    }

    public function productosMateriales(){
        return $this->hasMany("App\ProductoElaborado");
    }

    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
}
