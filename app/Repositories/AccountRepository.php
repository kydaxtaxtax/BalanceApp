<?php

namespace App\Repositories;

use App\Models\Balance;
use App\Models\Operation;
use App\Http\Resources\AccountResource;
use App\Http\Resources\OperationResource;

class AccountRepository
{
    public function getAccountData($userId)
    {
        $balance = Balance::where('user_id', $userId)->first();

        $operations = Operation::whereHas('balance', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return [
            'balance' => new AccountResource($balance),
            'operations' => OperationResource::collection($operations),
        ];
    }
}
