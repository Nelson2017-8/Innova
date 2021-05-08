<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresupuestoDetalle extends Model
{
    protected $table = 'presupuesto_detalles';
    protected $fillable = [
        'nombreProducto',
        'descripcionProducto',
    ];
    protected $hidden = [
        'presupuesto_id', 'created_at', 'id'
    ];
    public function presupuesto(){
        return $this->belongsTo("App\Presupuesto");
    }
    public function productosElaborados(){
        return $this->hasMany("App\ProductoElaborado");
    }
    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
}
