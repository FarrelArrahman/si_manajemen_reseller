<?php 

namespace App\Traits;

use GuzzleHttp\Client;

trait Rajaongkir {
    
    public function provinceAPI()
    {
        $client = new Client(['base_uri' => env('RAJAONGKIR_BASE_URI', 'https://api.rajaongkir.com/')]);
        $response = $client->request('GET', env('RAJAONGKIR_PROVINCE', '/starter/province'), ['query' => ['key' => env('RAJAONGKIR_API_KEY', 'e22f1c6f62ab0ff49b35f91cf61a3362')]]);
        $json = json_decode($response->getBody());
        return $json->rajaongkir->results;
    }

    public function cityAPI($province)
    {
        $client = new Client(['base_uri' => env('RAJAONGKIR_BASE_URI', 'https://api.rajaongkir.com/')]);
        $response = $client->request('GET', env('RAJAONGKIR_CITY', '/starter/city'), ['query' => ['key' => env('RAJAONGKIR_API_KEY', 'e22f1c6f62ab0ff49b35f91cf61a3362'), 'province' => $province]]);
        $json = json_decode($response->getBody());
        return $json->rajaongkir->results;
    }

    public function shippingCostAPI($origin, $destination, $weight = 1000, $courier)
    {
        $client = new Client(['base_uri' => env('RAJAONGKIR_BASE_URI', 'https://api.rajaongkir.com/')]);
        $response = $client->request('POST', env('RAJAONGKIR_COST', '/starter/cost'), [
            'form_params' => [
                'key' => env('RAJAONGKIR_API_KEY', 'e22f1c6f62ab0ff49b35f91cf61a3362'),
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier,
            ],
        ]);

        $json = json_decode($response->getBody());
        
        return $json;
    }
}

?>