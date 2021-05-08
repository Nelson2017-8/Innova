<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaCliente extends Model
{
    protected $table = 'factura_clientes';
    protected $fillable = [
        'fechaEmision',
        'precioTotal',
        'numFactura',
    ];
    protected $hidden = [
        'vendedor_id', 'id', 'cliente_id', 'created_at'
    ];
    public function cliente(){
        return $this->belongsTo("App\Cliente");
    }
    public function facturaDetalle(){
        return $this->belongsTo("App\FacturaClienteDet");
    }

    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
}
