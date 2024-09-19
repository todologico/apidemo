<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Pharmacy;
use App\Models\User;

class PharmacyFindTest extends TestCase
{
       //use RefreshDatabase;

    /**
     * prueba de busqueda ok de farmacia.
     * php artisan test --filter test_find_one_pharmacy
     * @return void
     */
    public function test_find_one_pharmacy(): void
    {
        $user = User::first();
    
        $data = [
            'nombre' => 'farmacia don tutte',
            'direccion' => 'av cordoba',
            'latitud' => 50.8503,
            'longitud' => 4.3517,
        ];

        //guardo al menos una farmacia
        $response = $this->actingAs($user)->postJson('/api/pharmacies-store', $data);

        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'Id insertado']);

        //--------------------------------------
        // busco una farmacia
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/farmacia?lat=19.4326&lon=-99.1332');

       // dd($response->json());

        // reviso la respuesta
        $response->assertStatus(200)
         ->assertJsonStructure(['nombre','direccion','latitud','longitud']);


    }
}

