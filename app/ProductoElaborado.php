<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoElaborado extends Model
{
    protected $table = 'productos_elaborados';
    protected $fillable = [
        'fechaElaboracion',
        'precio',
        'cod_barras',
    ];
    protected $hidden = [
        'prespuesto_id', 'subcategoria_id', 'almacen_id', 'created_at', 'id'
    ];
    public function facturaCliente(){
        return $this->belongsTo("App\FacturaCliente");
    }
    public function presupuestoDetalle(){
        return $this->belongsTo("App\PresupuestoDetalle");
    }
    public function subcategoria(){
        return $this->belongsTo("App\Subcategoria");
    }
    public function almacen(){
        return $this->belongs("App\Almacen");
    }

    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
}
