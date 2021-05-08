<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
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
            'username' => 'min:3|max:150|required',
            'email' => 'min:4|max:300|required|email',
            'password' => 'max:150'
        ];
    }
}
