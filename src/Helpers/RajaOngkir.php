<?php

namespace Botble\RajaOngkir\Helpers;

use Illuminate\Support\Facades\Http;

class RajaOngkir
{
    protected static string $apiKey = 'b40b8114a4012df19b34330f7aeb78a8';
    protected static string $baseUrl = 'https://api.rajaongkir.com/starter';

    protected static function request(string $endpoint, array $params = [], string $method = 'GET')
    {
        $url = static::$baseUrl . $endpoint;
        $headers = [
            'key' => static::$apiKey,
            'Accept' => 'application/json',
        ];

        $response = $method === 'POST'
            ? Http::withHeaders($headers)->asForm()->post($url, $params)
            : Http::withHeaders($headers)->get($url, $params);

        return $response->json('rajaongkir.results') ?? $response->json('rajaongkir');
    }

    public static function getProvinces() { return static::request('/province'); }
    public static function getCities($provinceId) { return static::request('/city', ['province_id' => $provinceId]); }
    public static function getCost($origin, $destination, $weight, $courier) {
        return static::request('/cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
        ], 'POST');
    }
}
