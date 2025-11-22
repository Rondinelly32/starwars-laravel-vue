<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class SWAPIClient extends Client
{
    public const BASE_URL = 'https://swapi.dev/api/';

    public function getPage(string $type, string $searchString, $page): array
    {
        try {
            $response = $this->get(
                self::BASE_URL . $type,
                [
                    'query' => [
                        'search' => $searchString,
                        'page' => $page,
                    ]
                ],
            );
        } catch (\Exception $e) {
            Log::error("Error fetching data from SWAPI: " . $e->getMessage());
            return [];
        }
        $data = json_decode($response->getBody()->__toString());
        Log::info("DATA: ", [$data]);
        return $data->results ?? [];
    }

    public function getDetails($type, int $id): array
    {
        $fullUrl = self::BASE_URL . "$type/$id";
        $response = $this->get($fullUrl);
        $data = json_decode($response->getBody()->__toString(), true);
        Log::info('DETAIL DATA: ', $data);

        return $data ?? [];
    }
}
