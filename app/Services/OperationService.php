<?php

namespace App\Services;

use App\Models\Balance;
use App\Models\Operation;
use App\Repositories\OperationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;

class OperationService
{
    protected $operationRepository;

    public function __construct(OperationRepository $operationRepository)
    {
        $this->operationRepository = $operationRepository;
    }

    public function processOperation($userId, $amount, $operationType, $description)
    {
        try {
            $balance = Balance::where('user_id', $userId)->firstOrFail();

            return DB::transaction(function () use ($balance, $userId, $amount, $operationType, $description) {
                if ($operationType === Operation::TYPE_OPERATION_DEBIT && $balance->amount < $amount) {
                    return $this->operationRepository->createOperation($balance->id, 'Insufficient funds to perform the operation', $amount, $operationType, Operation::STATUS_FAILED);
                }

                $operation = $this->operationRepository->createOperation($balance->id, $description, $amount, $operationType, Operation::STATUS_PENDING);

                if ($operation->status === Operation::STATUS_PENDING) {
                    if ($operationType === Operation::TYPE_OPERATION_DEBIT) {
                        $balance->amount -= $amount;
                    } else {
                        $balance->amount += $amount;
                    }

                    $balance->save();

                    $operation->update(['status' => Operation::STATUS_COMPLETED]);
                }

                return $operation;
            });
        } catch (\Exception $e) {
            return $this->operationRepository->createOperation($balance->id, 'Error while processing the operation: ' . $e->getMessage(), $amount, $operationType, Operation::STATUS_FAILED);
        }
    }
}
