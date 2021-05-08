<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierSaleRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'email' => 'El correo no es válido',
            'unique' => 'El valor ":input" ya existe en la base de datos',
            'email.unique' => 'El correo ":input" ya existe en la base de datos',
            'min' => 'El valor ":input" del campo ":attribute" no es válido, require de un minimo :min caracteres',
            'max' => 'El valor ":input" del campo ":attribute" no es válido, no puede pasar de :max caracteres',
            'password.max' => 'La contraseña es muy grande, no puede pasar de :max caracteres',
            "required" => 'El campo ":attribute" es requerido'
        ];
    }
    public function rules()
    {
        return [
            'correo' => 'max:150|email|unique:proveedores',
        ];
    }
}
