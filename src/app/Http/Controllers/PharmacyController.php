<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response; 
use Illuminate\Http\Request;
use App\Models\Pharmacy;
use App\Services\Pharmacy\PharmacyService;
use App\Http\Requests\StorePharmacyRequest;
use App\Http\Requests\FindPharmacyRequest;

use Log;

class PharmacyController extends Controller
{

    protected $pharmacyService;

    public function __construct(PharmacyService $pharmacyService)
    {
        $this->pharmacyService = $pharmacyService;
    }
    
    //-------------------------------------------------------
     /**
     * Guarda una farmacia
     * @param StorePharmacyRequest
     * @return JsonResponse
     */

    public function storePharmacy(StorePharmacyRequest $request)
    {
        $id=null;

        $pharmaData = $request->validated();

        try {

            $id =  $this->pharmacyService->storePharmacySVE($pharmaData);

            if(isset($id) and is_numeric($id)){
    
                return response()->json(['message' => 'Se Guardo correctamente.','Id insertado' => $id], Response::HTTP_OK);

            } else {

                return response()->json(['message' => 'No se inserto el registro.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
    
        } catch (\Exception $e) {

            Log::error('Error storePharmacy: ' . $e->getMessage());
            
            return response()->json(['message' => 'Hay un problema con esta operacion.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //--------------------------------------------------------

    /**
     * Obtiene una farmacia por id.*
     * @param int $id
     * @return JsonResponse
     */

    public function showPharmacy($id)
    {

        try {              
            
            if (!isset($id) || !is_numeric($id) || $id <= 0) {

                return response()->json(['message' => 'tipo ID no correcto.'], Response::HTTP_BAD_REQUEST);
            }      

            $pharmacy = $this->pharmacyService->showPharmacySVE($id);           

            return response()->json($pharmacy, Response::HTTP_OK);


        } catch (\Exception $e) {

            Log::error('Error showPharmacy: ' . $e->getMessage());

            return response()->json(['message' => 'Hay un problema, llama a soporte.'], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }


    //--------------------------------------------------------

    /**
     * Encuentra la farmacia cercana por latitud y longitud.
     * @param Request 
     * @return JsonResponse
     */

    public function findPharmacy(FindPharmacyRequest $request)
    {

        try {
            // Obtén los datos validados directamente desde el FormRequest
            $geoData = $request->validated();

            $lat = $geoData['lat'];
            $lon = $geoData['lon'];

            // busca farmacias usando el servicio de farmacia
            $pharmacies = $this->pharmacyService->findOnePharmacySVE($lat, $lon);

            if ($pharmacies) {

                return response()->json($pharmacies);
            
            } else {
            
                return response()->json(['message' => 'No hay farmacias cercanas'], Response::HTTP_NOT_FOUND);
            }

        } catch (\Exception $e) {

            // logging
            Log::error('Error findPharmacy: ' . $e->getMessage());

            return response()->json(['message' => 'Hay un problema.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
