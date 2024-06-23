<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Balance;
use App\Models\Operation;
use InvalidArgumentException;
use App\Jobs\ProcessOperation;

class AddOperationToQueue extends Command
{
    protected $signature = 'add:operation {user_id} {amount} {operation_type} {description?}';
    protected $description = 'Add an operation to the operations queue';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->argument('user_id');
        $amount = $this->argument('amount');
        $operationType = $this->argument('operation_type');
        $description = $this->argument('description') ?? '';

        // Проверка наличия пользователя с указанным ID
        $userExists = Balance::where('user_id', $userId)->exists();
        if (!$userExists) {
            $this->error('User with the specified user_id does not exist.');
            return;
        }

        // Проверка на неотрицательную сумму
        if ($amount < 0) {
            $this->error('Amount must be a non-negative number.');
            return;
        }

        // Проверка корректности типа операции (дебет или кредит)
        if (!in_array($operationType, [Operation::TYPE_OPERATION_DEBIT, Operation::TYPE_OPERATION_CREDIT])) {
            $this->error('Invalid operation type. Operation type must be debit or credit.');
            return;
        }

        // Все проверки пройдены, добавляем операцию в очередь
        ProcessOperation::dispatch($userId, $amount, $operationType, $description)->onQueue('operations');

        $this->info('Operation added to operations queue.');
    }
}
