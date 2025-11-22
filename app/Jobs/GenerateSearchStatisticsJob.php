<?php

namespace App\Jobs;

use App\Models\QueryStatsSnapshot;
use App\Services\SearchStatisticsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateSearchStatisticsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle(SearchStatisticsService $statsService): void
    {
        $stats = $statsService->generateSearchStatistics();
        QueryStatsSnapshot::create([
            'payload' => $stats,
        ]);
    }
}
