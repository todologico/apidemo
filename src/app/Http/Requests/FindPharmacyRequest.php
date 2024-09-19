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


    /**
     * Reglas de validación para latitud y longitud.
     */
    public function rules(): array
    {
        return [
            'lat' => 'required|numeric|between:-90,90',
            'lon' => 'required|numeric|between:-180,180',
        ];
    }

    /**
     * Preparar los datos para la validación.
     */
    protected function prepareForValidation()
    {
        // Esto asegura que los parámetros de la ruta se incluyan en la validación
        $this->merge([
            'lat' => $this->route('lat'),
            'lon' => $this->route('lon'),
        ]);
    }
    
}
