<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Pharmacy;
use App\Models\User;

class PharmacyCreationErrorTest extends TestCase
{
   // use RefreshDatabase;

    /**
     * prueba de creacion de farmacia con datos obligatorios faltantes, que regresa un json con errors
     * php artisan test --filter test_pharmacy_creation_with_error_missing_values
     * @return void
     */
    public function test_pharmacy_creation_with_error_missing_values()
    {

         //$this->withoutExceptionHandling();

         $user = User::first();

        // voy con datos inválidos (faltan campos obligatorios como direccion, latitud y longitud)
        $data = ['nombre' => 'farmacia pepe',];

        $response = $this->actingAs($user)->postJson('/api/pharmacies-store', $data);

        // dd($response->json());

        // reviso que la respuesta tiene un estado de error
        $response->assertJsonValidationErrors(['direccion', 'latitud', 'longitud']);
    }
}

