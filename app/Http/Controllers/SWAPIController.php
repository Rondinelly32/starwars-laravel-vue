<?php

namespace App\Http\Controllers;

use App\Models\QueryStatsSnapshot;
use App\Services\SWAPIService;
use Illuminate\Http\Request;

class SWAPIController extends Controller
{
    public function __construct(public SWAPIService $swapiService)
    {
    }

    public function list(Request $request, string $type)
    {
        $search = $request->validate([
            'search' => 'nullable|string'
        ])['search'] ?? '';

        $data = $this->swapiService->list($type, $search);
        if (empty($data)) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        return response()->json($data);
    }

    public function details(Request $request, string $type, int $id)
    {
        $data = $this->swapiService->details($type, $id);

        if (empty($data)) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        return response()->json($data);
    }

    public function stats()
    {
        $data = QueryStatsSnapshot::latest()->limit(1)->get()->toArray();
        return response()->json($data);
    }
}
