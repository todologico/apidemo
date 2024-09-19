<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FindPharmacyRequest extends FormRequest
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
            'lat' => 'required|numeric|between:-90,90',
            'lon' => 'required|numeric|between:-180,180',
    ];
    }
}
