<?php

namespace App\Jobs;

use App\Services\OperationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOperation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $amount;
    protected $operationType;
    protected $description;

    public function __construct($userId, $amount, $operationType, $description)
    {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->operationType = $operationType;
        $this->description = $description;
    }

    public function handle(OperationService $operationService)
    {
        $operationService->processOperation($this->userId, $this->amount, $this->operationType, $this->description);
    }
}
