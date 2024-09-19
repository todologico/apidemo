<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Pharmacy;
use App\Models\User;

class PharmacyCreationTest extends TestCase
{
       //use RefreshDatabase;

    /**
     * prueba de creación ok de farmacia.
     * php artisan test --filter test_ok_pharmacy_creation
     * @return void
     */
    public function test_pharmacy_creation_ok(): void
    {

       // $this->withoutExceptionHandling();

       $user = User::first();
    
        $data = [
            'nombre' => 'farmacia de testing',
            'direccion' => 'av corrientes',
            'latitud' => 40.7128,
            'longitud' => -74.0061,
        ];

        $response = $this->actingAs($user)->postJson('/api/pharmacies-store', $data);

        // dd($response->json());

        $response->assertStatus(200);

        $response->assertJsonStructure(['message', 'Id insertado']);

        $this->assertDatabaseHas('pharmacies', [
            'nombre' => 'farmacia de testing',
            'direccion' => 'av corrientes',
            'latitud' => 40.7128,
            'longitud' => -74.0061,
        ]);

        $id = $response->json('Id insertado');
        
        $this->assertIsNumeric($id);

    }
}

