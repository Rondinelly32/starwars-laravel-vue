<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

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

//    public function getPeopleList(string $searchString= ''): array
//    {
//        return $this->getList(self::PEOPLE_ENDPOINT, $searchString);
//    }
//
//    public function getPeopleDetails($id): array
//    {
//        return $this->getDetails(self::PEOPLE_ENDPOINT, $id);
//    }
//
//    public function getFilmsList($searchString = ''): array
//    {
//        return $this->getList(self::FILMS_ENDPOINT, $searchString);
//    }
//
//    public function getFilmDetails($id): array
//    {
//        return $this->getDetails(self::FILMS_ENDPOINT, $id);
//    }

    public function list($type, $searchString): array
    {
        $allResults = [];
        $page = 1;

        do {
            $results = $this->swapiClient->getPage($type, $searchString, $page);
            $allResults = array_merge($allResults, $results);
            $page++;
        } while (!empty($results));

        return $allResults;
    }

    public function details($type, $id): array
    {
        $this->setRelation($type);

        $details = $this->swapiClient->getDetails($type,$id);
        $items = $details[$this->relation];

        foreach ($items as $index => $itemUrl) {
            $itemId = basename($itemUrl);
            Log::info('BASENAME: ' . $itemId);
            $filmDetails = $this->swapiClient->getDetails(
                self::RELATION_ENDPOINT[$this->relation],
                $itemId
            );
            Log::info("FILM ID $itemId DETAILS: " , $filmDetails);
            $items[$index] = $filmDetails;
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
