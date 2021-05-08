<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompraRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

	public function messages()
	{
		return [
			'numFactura.unique' => 'La Factura ":input" ya existe en la base de datos',
			'min' => 'El valor ":input" del campo ":attribute" no es válido, require de un minimo :min caracteres',
			'max' => 'El valor ":input" del campo ":attribute" no es válido, no puede pasar de :max caracteres',
			"required" => 'El campo ":attribute" es requerido'
		];
	}
	public function rules()
	{
		return [
			'numFactura' => 'min:3|max:150|required|unique:compras',
			'proveedor_id' => 'required',
		];
	}
}
