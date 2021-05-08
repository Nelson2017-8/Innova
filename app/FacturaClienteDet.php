<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaClienteDet extends Model
{
    protected $table = 'factura_clientes_det';
    protected $fillable = [
        'precioUnitario',
        'cantidad',
    ];
    protected $hidden = [
        'factura_id', 'producto_elaborado_id', 'created_at', 'id'
    ];
    public function facturaDetalles(){
        return $this->hasMany("App\FacturaCliente");
    }
    public function productos(){
        return $this->hasMany("App\ProductoElaborado");
    }

    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
}
