<?php

namespace App\Services\Pharmacy;

use Illuminate\Support\Collection;
use App\Repositories\Pharmacy\PharmacyRepository;
use App\Models\Pharmacy; 


class PharmacyService 
{

    protected $pharmacyRepository;

    public function __construct(PharmacyRepository $pharmacyRepository)
    {
        $this->pharmacyRepository = $pharmacyRepository;
    }

    //-------------------------------------------------------
    
    /**
     * Guarda una nueva farmacia
     * @param array $validated
     * @return int id
     * @throws RuntimeException 
     */

    public function storePharmacySVE(array $pharmaData): int
    {

        $id = null;

        try {

            if(isset($pharmaData)){

            $pharmaData['created_at'] = now();  $pharmaData['updated_at'] = now();

            $id = $this->pharmacyRepository->storePharmacyRPY($pharmaData);

            }

        } catch (\Exception $e) {

            throw new \RuntimeException('No se pudo crear la farmacia: ' . $e->getMessage());
        }

        return $id;
    }

    //------------------------------------------------------

    /**
     * Muestra una farmacia
     * @return Collection
     */

    public function showPharmacySVE($id)
    {
        $pharmacy = null;

        try {

            if(isset($id)){

                $pharmacy = $this->pharmacyRepository->showPharmacyRPY($id);
            }

        } catch (\Exception $e) {

            throw new \RuntimeException('No se pudo encontrar la farmacia: ' . $e->getMessage());
        }

        return $pharmacy;
    }

    //------------------------------------------------------

    /**
     * Busca una farmacia por latitud y longitud.**
     * @param float $lat
     * @param float $lon
     * @return Pharmacy|null
     */

    public function findOnePharmacySVE(float $lat ,float $lon): ?Pharmacy
    {
        $pharmacies = null;

        try {

            if(isset($lat) && isset($lon)){

                $pharmacies = $this->pharmacyRepository->findOnePharmacyRPY($lat,$lon);
            }

        } catch (\Exception $e) {

            throw new \RuntimeException('No se pudo encontrar la farmacia cercana: ' . $e->getMessage());
        }

        return $pharmacies;      
    }
}
