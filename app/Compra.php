<?php

namespace App;

//use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';
    protected $fillable = [
        'numFactura',
        'precioCompra',
		'limitGarantia',
		'fechaCompra',
	];
    protected $hidden = [
		'proveedor_id', 'id', 'created_at', 'updated_at'
    ];
    public function proveedor(){
        return $this->belongsTo("App\Proveedor");
    }
    public function insumosComprados(){
        return $this->hasMany("App\InsumoComprado");
    }
    public function insumosAlmacenados(){
        return $this->hasMany("App\InsumoAlmacendo");
    }
    // CODIFICA EL ID
    public function getEncrypIdAttribute(){
        return \App\Http\Controllers\TokenController::encriptar($this->attributes['id']);
    }
//    public function getGarantiaAttribute(){
//    	if ( !is_null($this->attributes['limitGarantia']) ){
//    		$diferencia = Carbon::now()->between($this->attributes['limitGarantia'], $this->attributes['fechaCompra']);
//    		$fecha = Carbon::parse($this->attributes['limitGarantia'])->format('d/m/Y');
//    		if ( $diferencia ){
//				return $fecha.' <span class="ml-1 badge badge-success">Disponible</span>';
//			}else{
//				return $fecha.' <span class="ml-1 badge badge-danger">Expirado</span>';
//			}
//		}else{
//    		return '';
//		}
//    }
}
