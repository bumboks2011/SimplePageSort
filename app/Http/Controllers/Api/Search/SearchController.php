<?php

namespace App\Http\Controllers\Api\Search;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Search\SearchRequest;
use App\Services\Search\SearchService;


class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param SearchRequest $request
     * @param SearchService $service
     * @return void
     */
    public function __invoke(SearchRequest $request, SearchService $service)
    {
        return response()->json($service->search($request), 200);
    }
}
