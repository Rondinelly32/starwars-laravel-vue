<?php

namespace App\Services;

use App\Models\SearchQuery;

class SearchQueryLogger
{
    public function logSearch(
        string $type,
        string $searchString,
        float $durationMs,
        string $status
    ): void
    {
        SearchQuery::create([
            'type' => $type,
            'search_string' => $searchString,
            'duration_ms' => $durationMs,
            'status' => $status
        ]);
    }
}
