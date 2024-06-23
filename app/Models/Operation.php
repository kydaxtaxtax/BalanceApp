<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'amount', 'operation_type', 'status', 'balance_id'];
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';


    const TYPE_OPERATION_CREDIT = 'credit';
    const TYPE_OPERATION_DEBIT = 'debit';
    public function balance()
    {
        return $this->belongsTo(Balance::class);
    }
}
