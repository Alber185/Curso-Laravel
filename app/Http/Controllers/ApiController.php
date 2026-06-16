<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExternalService\ApiService;

class ApiController extends Controller
{
    public function __construct(protected ApiService $apiService)
    {
    }

    public function get()
    {
        $data = $this->apiService->getData();
        return response()->json($data);
    }
}
