<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartialDeliveryItemResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product_name' => optional($this->product)->name,
            'unit_id' => $this->unit_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'real_unit_price' => $this->real_unit_price,
            'subtotal' => $this->subtotal,
            'note' => $this->note,
        ];
    }
}
