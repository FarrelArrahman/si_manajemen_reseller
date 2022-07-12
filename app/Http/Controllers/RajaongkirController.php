<?php

namespace App\Http\Controllers;

use App\Traits\Rajaongkir;
use Illuminate\Http\Request;

class RajaongkirController extends Controller
{
    use Rajaongkir;
    
    public function provinceAPI()
    {
        return response()->json([
            'success' => true,
            'type' => 'rajaongkir_province_list',
            'message' => 'Daftar provinsi',
            'data' => $this->provinceAPI(),
            'statusCode' => 200
        ], 200);
    }

    public function cityAPI(Request $request)
    {
        return response()->json([
            'success' => true,
            'type' => 'rajaongkir_city_list',
            'message' => 'Daftar kabupaten/kota',
            'data' => $this->cityAPI($request->province),
            'statusCode' => 200
        ], 200);
    }

    public function costAPI(Request $request)
    {
        $shippingCost = $this->shippingCostAPI(
            $request->origin, 
            $request->destination, 
            $request->weight,
            $request->courier,
        );

        return response()->json([
            'success' => true,
            'type' => 'shipping_cost',
            'message' => 'Ongkos kirim',
            'data' => $shippingCost,
            'statusCode' => 200
        ], 200);
    }
}
