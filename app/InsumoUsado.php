<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsumoUsado extends Model
{
    protected $table = 'insumos_usados';
    protected $fillable = [
        'cantidad',
    ];
    protected $hidden = [
        'materia_prima_id', 'presupuestos_id', 'created_at', 'id'
    ];
    public function materiaPrima(){
        return $this->belongTo("App\MateriaPrima");
    }

    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
}
