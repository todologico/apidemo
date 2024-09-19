<?php

namespace App\Repositories\Pharmacy;

use App\Models\Pharmacy;
//use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection;
use DB;

class PharmacyRepository 
{

    /**
     * Guarda una farmacia     *
     * @param array $onePharmacy 
     * @return int id
     */

    public function storePharmacyRPY(array $pharmaData): int
    {
        $id = null;

        if(isset($pharmaData) and is_array($pharmaData)){

            //$id = Pharmacy::create($pharmaData)->id;
            $id = DB::table('pharmacies')->insertGetId($pharmaData); 
        }

        return $id;
    }

    //------------------------------------------------------

    /**
     * Busco una farmacia en particular*
     * @param int $id
     * @return Collection
     */

    public function showPharmacyRPY(int $id) :Collection
    {

        if(isset($id) and is_numeric($id)){
        
            //$pharmacy = Pharmacy::find($id);
            $pharmacy = DB::table('pharmacies')
                        ->select('id', 'nombre', 'direccion', 'latitud', 'longitud', 'created_at', 'updated_at')
                        ->where('id', '=', $id)
                        ->get();
        }

        return $pharmacy;
    }

    //--------------------------------------------------------

    /**
     * Formula de semiverseno haversine
     * Busco la farmacia más cercana segun latitud y longitud.
     * @param float $lat es latitud.
     * @param float $lon es longitud.
     * @return Pharmacy La farmacia más cercana.
     */

    public function findOnePharmacyRPY(float $lat, float $lon): ?Pharmacy
    {   
        $pharmacies = null;

        if(isset($lat) && isset($lon)){
         
            $pharmacies = Pharmacy::selectRaw("id,nombre,direccion,latitud,longitud,
                ( 6371 * acos( cos( radians(?) ) * cos( radians( latitud ) ) * cos( radians( longitud ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitud ) ) ) ) AS distancia",
                [$lat, $lon, $lat])
                ->orderBy('distancia')
                ->first();
        }    
         
        return $pharmacies;
    }    
}