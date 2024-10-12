<?php

namespace App\Http\Resources;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'product_name' => $this->product_name,
            'quantity' => (int)$this->quantity,
            'price' => (float)$this->price,
            'status' => OrderStatus::key($this->status, true),
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'created_at_for_humans' => $this->created_at ? $this->created_at->diffForHumans() : null,
        ];
    }
}
