<?php

namespace App\Http\Resources;

use App\Models\Operation;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'email' => $this->balance->user->email,
            'amount' => $this->amount,
            'operation_type' => $this->operation_type,
            'status' => $this->status,
            'description' => $this->description
        ];
    }
}
