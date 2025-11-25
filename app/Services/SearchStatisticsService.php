<?php

namespace App\Services;

use App\Models\SearchQuery;

class SearchStatisticsService
{
    public function generateSearchStatistics()
    {
        return [
            'top_search_queries' => $this->topSearchQueries(),
            'search_volume_by_hour' => $this->searchVolumeByHour(),
            'average_duration_ms' => $this->averageDuration(),
            'total_queries' => SearchQuery::count(),
        ];
    }

    private function topSearchQueries(int $limit = 5): array
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

    private function searchVolumeByHour() : array
    {
        $volumenByHour = SearchQuery::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
        ->where('created_at', '>=', now()->subDay())
        ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->mapWithKeys(function ($row) {
                return [(int) $row->hour => (int) $row->count];
            })
            ->toArray();

        $allHours = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $allHours[] = [
                'hour' => $hour,
                'count' => $volumenByHour[$hour] ?? 0,
            ];
        }
        return $allHours;
    }
}
