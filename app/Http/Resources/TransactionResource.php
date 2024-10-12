<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'amount' => $this->amount,
            'customer_name' => $this->customer_name,
            'notification_option' => $this->notification_option,
            'invoice_id' => $this->invoice_id,
            'invoice_url' => $this->invoice_url,
            'created_by' => $this->created_by
        ];
    }
}
