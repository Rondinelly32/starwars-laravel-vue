<?php

namespace App\Services;

use GuzzleHttp\Client;

class SwApiClient extends Client
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
            return [];
        }
        $data = json_decode($response->getBody()->__toString());

        return $data->results ?? [];
    }

    public function getDetails($type, int $id): array
    {
        $fullUrl = self::BASE_URL . "$type/$id";
        $response = $this->get($fullUrl);
        $data = json_decode($response->getBody()->__toString(), true);

        return $data ?? [];
    }
}
