<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderResource extends JsonResource
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
            'order_no' => $this->order_no,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'telegram_or_phone' => $this->telegram_or_phone,
            'price' => $this->price,
            'location' => $this->location,
            'detail' => $this->detail,
            
            'item_code' => $this->item_code,
            'link' => $this->link,
            'link_korea' => $this->link_korea,
            'created_at' => $this->created_at,
        ];
    }
}
