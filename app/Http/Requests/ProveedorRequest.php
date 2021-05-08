<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'correo' => 'El correo no es válido',
            'unique' => 'El valor ":input" ya existe en la base de datos',
            'correo.unique' => 'El correo ":input" ya existe en la base de datos',
            'min' => 'El valor ":input" del campo ":attribute" no es válido, require de un minimo :min caracteres',
            'max' => 'El valor ":input" del campo ":attribute" no es válido, no puede pasar de :max caracteres',
            "required" => 'El campo ":attribute" es requerido'
        ];
    }
    public function rules()
    {
        return [
            'nombre' => 'min:3|max:100|required',
            'correo' => 'max:150|email|required|unique:proveedores',
            'razonSocial' => 'min:3|max:255|required',
            'cod_postal' => 'min:3|max:10|required',
            'direccion' => 'min:3|max:255|required',
            'telefono_1' => 'min:10|max:20|required',
            'telefono_2' => 'max:20',
        ];
    }
}
