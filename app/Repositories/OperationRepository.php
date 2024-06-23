<?php

namespace App\Repositories;

use App\Models\Operation;
use App\Http\Resources\OperationResource;
use Illuminate\Support\Facades\DB;

class OperationRepository
{
    public function getOperations($searchDescription, $orderColumn, $orderDirection)
    {
        $isUserOperation = false;

        $query = Operation::when($searchDescription, function ($query) use ($searchDescription) {
            $query->where('description', 'like', '%' . $searchDescription . '%');
        })
            ->when(!auth()->user()->hasPermissionTo('operation-all'), function ($query) use (&$isUserOperation) {
                $query->whereHas('balance', function ($query) {
                    $query->where('user_id', auth()->id());
                });
                $isUserOperation = true;
            });

        if ($orderColumn == 'email') {
            $query->join('balances', 'operations.balance_id', '=', 'balances.id')
                ->join('users', 'balances.user_id', '=', 'users.id')
                ->select('operations.*')
                ->orderBy('users.email', $orderDirection);
        } else {
            $query->orderBy($orderColumn, $orderDirection);
        }

        $operations = $query->paginate(50);

        return [
            'is_user_operations' => $isUserOperation,
            'operations' => OperationResource::collection($operations)->response()->getData(true)
        ];
    }

    public function createOperation(int $balanceId, string $description, float $amount, string $operationType, string $status)
    {
        $data = [
            'balance_id' => $balanceId,
            'description' => $description,
            'amount' => $amount,
            'operation_type' => $operationType,
            'status' => $status,
        ];

        return Operation::create($data);
    }
}
