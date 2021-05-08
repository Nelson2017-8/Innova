<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $table = 'presupuestos';
    protected $fillable = [
        'nombre',
        'descripcion',
        'fechaInicial',
        'fechaFinal',
        'cotizacion',
        'aprobado'
    ];
    protected $hidden = [
        'cliente_id', 'created_at', 'id'
    ];
    public function cliente(){
        return $this->belongsTo("App\Cliente");
    }
    public function detalles(){
        return $this->hasMany("App\PresupuestoDetalle");
    }

    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
}
