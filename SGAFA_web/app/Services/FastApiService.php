<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FastApiService
{
    protected $baseUrl;

    public function __construct()
    {
        // En Docker, host.docker.internal apunta al host de Windows
        $this->baseUrl = "http://host.docker.internal:8080/api";
    }

    /**
     * Realiza una petición GET a la API
     */
    public function get($endpoint, $params = [])
    {
        $token = session('api_token');
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($this->baseUrl . $endpoint, $params);

        if ($response->failed()) {
            Log::error("API GET Error [$endpoint]: " . $response->body());
        }

        return $response;
    }

    /**
     * Realiza una petición POST a la API
     */
    public function post($endpoint, $data = [])
    {
        $token = session('api_token');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->post($this->baseUrl . $endpoint, $data);

        if ($response->failed()) {
            Log::error("API POST Error [$endpoint]: " . $response->body());
        }

        return $response;
    }

    /**
     * Petición sin token (ej: Login/Forgot Password)
     */
    public function guestPost($endpoint, $data = [])
    {
        return Http::withHeaders([
            'Accept' => 'application/json'
        ])->post($this->baseUrl . $endpoint, $data);
    }
}
