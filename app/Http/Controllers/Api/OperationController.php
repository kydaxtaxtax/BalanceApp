<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\OperationRepository;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    protected $operationRepository;

    public function __construct(OperationRepository $operationRepository)
    {
        $this->operationRepository = $operationRepository;
    }

    public function index(Request $request)
    {
        $searchDescription = $request->input('search_description');
        $orderColumn = $request->input('order_column', 'created_at');
        $orderDirection = $request->input('order_direction', 'desc');

        $operationsData = $this->operationRepository->getOperations($searchDescription, $orderColumn, $orderDirection);

        return response()->json($operationsData);
    }
}
