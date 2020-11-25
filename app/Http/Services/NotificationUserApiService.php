<?php

namespace App\Http\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class NotificationUserApiService
 * @package App\Http\Services
 */
class NotificationUserApiService
{
    public function notify()
    {
        try {
            $response = Http::get(env('NOTIFICATION_USER_API_SERVICE'));
            $response->throw();
            Log::info($response->body());
        } catch (RequestException $e) {
            Log::warning('Notify Error', ['message' => $e->getMessage(), 'statusHttp' => $e->getCode()]);
        }
    }
}
