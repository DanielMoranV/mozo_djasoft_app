<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'series' => $this->series,
            'number' => $this->number,
            'type' => $this->type,
            'amount' => $this->amount,
            'status' => $this->status,
            'issue_date' => $this->issue_date,
            'hash' => $this->hash,
            'concat' => $this->series . '-' . str_pad($this->number, 8, '0', STR_PAD_LEFT),
            'created_at' => $this->create_at,
        ];
    }
}