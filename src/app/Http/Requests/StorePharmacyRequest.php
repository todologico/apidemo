<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePharmacyRequest extends FormRequest
{
    /**
     * dejo pasar el request siempre que el usuario este autenticado
     */
    public function authorize(): bool
    {
        return auth()->check();
    }


    public function rules(): array
    {
        return [
            'nombre'    => 'required|string|max:200',
            'direccion' => 'required|string|max:200',
            'latitud'   => 'required|numeric|between:-90,90',
            'longitud'  => 'required|numeric|between:-180,180',
        ];
    }
}
