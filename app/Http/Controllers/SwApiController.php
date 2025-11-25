<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use App\Models\QueryStatsSnapshot;
use App\Services\SearchQueryLogger;
use App\Services\SwApiService;
use Illuminate\Support\Facades\Log;

class SwApiController extends Controller
{
    public function __construct(
        public SwApiService      $swApiService,
        public SearchQueryLogger $searchQueryLogger
    ){
    }

    public function list(ListRequest $request, string $type)
    {
        $startedAt = microtime(true);
        $search = $request->getSearch();

        try {
            $data = $this->swApiService->list($type, $search);
            $status = 'success';
        } catch (\Exception $e){
            $status = 'failed';
            Log::error(
                "Error getting $type - " . $e->getMessage(),
                $e->getTrace()
            );
        } finally {
            $durationMs = (int) ((microtime(true) - $startedAt) * 1000);
            $this->searchQueryLogger->logSearch(
                $type,
                $search,
                $durationMs,
                $status,
            );
        }
        if (empty($data)) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        return response()->json($data);
    }

    public function details(string $type, int $id)
    {
        try {
            $data = $this->swApiService->details($type, $id);
        } catch (\Exception $e){
            Log::error(
                "Error getting $type details for id $id - " . $e->getMessage(),
                $e->getTrace()
            );
            return response()->json(
                ['error' => 'Error fetching details'],
                500
            );
        }

        if (empty($data)) {
            return response()->json(['error' => 'No Results'], 404);
        }

        return response()->json($data);
    }

    public function stats()
    {
        $data = QueryStatsSnapshot::latest()->first()->toArray();
        return response()->json($data);
    }
}
