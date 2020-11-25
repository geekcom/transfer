<?php

namespace App\Http\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;

/**
 * Class ConsultExternalApiService
 * @package App\Http\Services
 */
class ConsultExternalApiService
{
    public function authorize()
    {
        try {
            $response = Http::get(env('CONSULT_EXTERNAL_API_SERVICE'));
            return $response->status();
        } catch (HttpResponseException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
