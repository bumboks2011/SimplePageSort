<?php

namespace App\Http\Controllers\Api\Attribute;

use App\Http\Controllers\Controller;
use App\Services\Attribute\AttributeService;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param AttributeService $service
     * @return void
     */
    public function __invoke(AttributeService $service)
    {
        return response()->json($service->get(), 200);
    }
}
