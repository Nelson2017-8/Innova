<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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
            'cedula.unique' => 'La cédula ":input" ya existe en la base de datos',
            'min' => 'El valor ":input" del campo ":attribute" no es válido, require de un minimo :min caracteres',
            'max' => 'El valor ":input" del campo ":attribute" no es válido, no puede pasar de :max caracteres',
            'password.max' => 'La contraseña es muy grande, no puede pasar de :max caracteres',
            "required" => 'El campo ":attribute" es requerido'
        ];
    }
    public function rules()
    {
        return [
            'primerNombre' => 'min:3|max:50|required',
            'primerApellido' => 'min:3|max:50|required',
            'cedula' => 'min:10|max:10|required|unique:clientes',
            'correo' => 'min:4|max:255|required|email|unique:clientes',
            'direccion' => 'max:255',
            'telefono_1' => 'min:10|max:20|required',
            'telefono_2' => 'max:20',
        ];
    }
}
