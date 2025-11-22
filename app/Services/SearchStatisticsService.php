<?php

namespace App\Services;

use App\Models\SearchQuery;
use Illuminate\Database\Query\Builder;

class SearchStatisticsService
{
    public function generateSearchStatistics()
    {
        return [
            'top_search_queries' => $this->tppSearchQueries(),
            'search_volume_by_hour' => $this->searchVolumneByHour(),
            'average_duration_ms' => $this->averageDuration(),
            'total_queris' => SearchQuery::count(),
        ];
    }

    private function tppSearchQueries(int $limit = 5): array
    {
        return SearchQuery::selectRaw('search_string, COUNT(*) as count')
            ->groupBy('search_string')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    private function averageDuration(): float
    {
        return (float) SearchQuery::avg('duration_ms') ?? 0.0;
    }

    private function searchVolumneByHour()
    {
        return SearchQuery::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour')
            ->map(fn($row) => $row->count)
            ->toArray();
    }
}
