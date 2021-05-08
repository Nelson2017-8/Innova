<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RawMaterialRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function messages()
    {
        return [
            'min' => 'El valor ":input" del campo ":attribute" no es válido, require de un minimo :min caracteres',
            'max' => 'El valor ":input" del campo ":attribute" no es válido, no puede pasar de :max caracteres',
            "required" => 'El campo ":attribute" es requerido'
        ];
    }
    public function rules()
    {
        return [
            'nombre' => 'min:3|max:100|required',
            'detalles' => 'max:150',
            'precio' => 'numeric',
        ];
    }
}
