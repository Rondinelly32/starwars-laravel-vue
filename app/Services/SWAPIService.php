<?php

namespace App\Services;


class SWAPIService
{
    public const PEOPLE_ENDPOINT = 'people';
    public const FILMS_ENDPOINT = 'films';
    public const CHARACTERS_RELATION = 'characters';

    public const RELATION_ENDPOINT = [
        self::CHARACTERS_RELATION => self::PEOPLE_ENDPOINT,
        self::FILMS_ENDPOINT => self::FILMS_ENDPOINT,
    ];

    public string $relation;

    public function __construct(public SWAPIClient $swapiClient)
    {
    }

    public function list($type, $searchString): array
    {
        $allResults = [];
        $page = 1;

        do {
            $results = $this->swapiClient->getPage($type, $searchString, $page);
            $allResults = array_merge($allResults, $results);
            $page++;
        } while (!empty($results));

        array_map(
            fn($item) => $item->id = basename($item->url)
            ,$allResults
        );

        return $allResults;
    }

    public function details($type, $id): array
    {
        $this->setRelation($type);

        $details = $this->swapiClient->getDetails($type,$id);
        $items = $details[$this->relation];

        foreach ($items as $index => $itemUrl) {
            $itemId = basename($itemUrl);
            $filmDetails = $this->swapiClient->getDetails(
                self::RELATION_ENDPOINT[$this->relation],
                $itemId
            );
            $items[$index] = $filmDetails;
            $items[$index]['id'] = $itemId;
        }

        $details = array_merge($details, [$this->relation => $items]);
        return $details;
    }

    private function setRelation($endpoint)
    {
        if ($endpoint == self::PEOPLE_ENDPOINT) {
            $this->relation = self::FILMS_ENDPOINT;
        } elseif ($endpoint == self::FILMS_ENDPOINT) {
            $this->relation = self::CHARACTERS_RELATION;
        }
    }
}
