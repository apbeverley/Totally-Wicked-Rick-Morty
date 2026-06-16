<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ApiException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RickAndMortyClient
{
    public function get(string $endpoint, array $params = []): array
    {
        $cacheKey = 'rick_morty_'.md5($endpoint.serialize($params));

        return Cache::remember($cacheKey, 3600, static function () use ($endpoint, $params) {
            try {
                $response = Http::withoutVerifying()->get('https://rickandmortyapi.com/api'.$endpoint, $params);
            } catch (ConnectionException $e) {
                ApiException::unavailable();
            }

            if ($response->status() === 404) {
                ApiException::notFound();
            }

            if ($response->status() === 429) {
                ApiException::rateLimited();
            }

            if ($response->successful() === false) {
                ApiException::unavailable();
            }

            return $response->json();
        });
    }
}
