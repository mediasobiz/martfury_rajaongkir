<?php

namespace Botble\RajaOngkir\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    protected $apiKey;

    public function __construct()
    {
        // Langsung ambil dari config (atau .env jika pakai helper sendiri)
        $this->apiKey = config('services.rajaongkir.key') ?? env('RAJAONGKIR_KEY');
    }

    // Ambil daftar provinsi
    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey, // GUNAKAN 'key' bukan 'x-api-key'
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

        return response()->json($response->json());
    }

    // Ambil daftar kota berdasarkan provinsi
    public function getCities(Request $request)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey,
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/city/'.$request->province_id);

        return response()->json($response->json());
    }

   // Hitung ongkir
    public function getCost(Request $request)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey,
        ])->post('https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost
    ', [
            'origin'      => $request->origin,
            'destination' => $request->destination,
            'weight'      => $request->weight,
            'courier'     => $request->courier,
			'price'     => 'lowest',
        ]);

        return response()->json($response->json());
    }
}
